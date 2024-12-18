<?php

namespace App\Controller;

use App\Entity\Jeton;
use App\Entity\JetonInscription;
use App\Entity\Utilisateur;
use App\Repository\JetonInscriptionRepository;
use App\Repository\JetonRepository;
use App\Entity\ExpirationUtil;
use App\Util\HasherUtil;
use App\Util\TokenGeneratorUtil;
use App\Util\MailUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PHPMailer\PHPMailer\PHPMailer;
use App\Service\JetonService;
use App\Service\MailService;

class InscriptionController extends AbstractController
{

    private $emailService;
    private $jetonService;

    public function __construct(
        MailService $emailService,
        JetonService $jetonService
    ) {
        $this->emailService = $emailService;
        $this->jetonService = $jetonService;
    }


    #[Route('/inscription', name: 'inscription', methods: ['POST'])]
    public function inscription(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            // Récupérer les données JSON de la requête
            $data = json_decode($request->getContent(), true);
    
            // Vérifier si les champs requis sont présents
            if (!isset($data['nom'], $data['dateNaissance'], $data['email'], $data['motDePasse'], $data['dureeJeton'])) {
                return new JsonResponse([
                    'status' => 'error',
                    'data' => null,
                    'error' => [
                        'code' => 400,
                        'message' => 'Données manquantes.'
                    ]
                ], 400);
            }
    
            // Vérifier si l'email existe déjà
            $existingUser = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $data['email']]);
            if ($existingUser) {
                return new JsonResponse([
                    'status' => 'error',
                    'data' => null,
                    'error' => [
                        'code' => 409,
                        'message' => 'Cet email est déjà utilisé.'
                    ]
                ], 409);
            }
    
            // Hachage du mot de passe
            $hashedPassword = HasherUtil::hashPassword($data['motDePasse']);
    
            // Créer un objet Jeton avec durée par défaut
            // $jeton = $this->jetonService->createJeton();
            $jeton = new Jeton();
    
            // Insérer le jeton dans la base
            $entityManager->getRepository(Jeton::class)->insertJeton($jeton);  // Utilisation d'EntityManager pour insérer
    
            // Créer un JetonInscription
            $jetonInscription = new JetonInscription(
                $data['email'], 
                $hashedPassword, 
                $data['nom'], 
                new \DateTime($data['dateNaissance']),
                $jeton // L'objet Jeton déjà créé
            );
            
            // Insérer JetonInscription dans la base
            $entityManager->getRepository(JetonInscription::class)->insertJetonInscription($jetonInscription);  // Utilisation d'EntityManager pour insérer
    
            // Créer un objet PHPMailer pour envoyer l'e-mail avec le lien de validation
            $mailer = $this->emailService->createMailerFromJetonInscription($jetonInscription);
    
            // Envoie l'e-mail
            if (!MailUtil::sendMail($mailer)) {
                return new JsonResponse([
                    'status' => 'error',
                    'data' => null,
                    'error' => [
                        'code' => 500,
                        'message' => 'Erreur lors de l\'envoi de l\'e-mail.'
                    ]
                ], 500);
            }
    
            // Réponse en cas de succès
            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'message' => 'Veuillez vérifier votre e-mail pour confirmer votre inscription.'
                ]
            ], 200);
    
        } catch (\Exception $e) {
            // Gestion des erreurs
            return new JsonResponse([
                'status' => 'error',
                'data' => null,
                'error' => [
                    'code' => 500,
                    'message' => $e->getMessage()
                ]
            ], 500);
        }
    }
    

    #[Route('/confirm/{token}', name: 'confirm', methods: ['GET'])]
    public function confirmInscription(string $token, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            // Chercher le jeton d'inscription par son token
            $jetonInscription = $entityManager->getRepository(JetonInscription::class)->findOneBy(['token' => $token]);

            if (!$jetonInscription) {
                return new JsonResponse([
                    'status' => 'error',
                    'data' => null,
                    'error' => [
                        'code' => 404,
                        'message' => 'Jeton d\'inscription introuvable.'
                    ]
                ], 404);
            }

            // Vérifier si le jeton est expiré (méthode de l'entité JetonInscription)
            if ($jetonInscription->isExpired()) {
                return new JsonResponse([
                    'status' => 'error',
                    'data' => null,
                    'error' => [
                        'code' => 410,
                        'message' => 'Le jeton a expiré.'
                    ]
                ], 410);
            }

            // Créer un nouvel utilisateur en utilisant le constructeur de JetonInscription
            $utilisateur = new Utilisateur(
                $jetonInscription->getMail(),
                $jetonInscription->getMdp(),
                $jetonInscription->getNom(),
                $jetonInscription->getDateNaissance()
            );

            // Insérer l'utilisateur dans la base de données
            $entityManager->getRepository(Utilisateur::class)->save($utilisateur);

            // Supprimer le jeton d'inscription après validation
            $entityManager->getRepository(JetonInscription::class)->remove($jetonInscription);

             // Supprimer le jeton correspondant à l'inscription
            $jeton = $jetonInscription->getJeton(); // Supposons que la relation entre JetonInscription et Jeton existe
            if ($jeton) {
                $entityManager->getRepository(Jeton::class)->remove($jeton);
            }

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'message' => 'Inscription confirmée avec succès.'
                ]
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'data' => null,
                'error' => [
                    'code' => 500,
                    'message' => $e->getMessage()
                ]
            ], 500);
        }
    }

    
}
