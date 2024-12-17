<?php
namespace App\Service;

use App\Entity\JetonInscription;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function createMailerFromJetonInscription(JetonInscription $jetonInscription): PHPMailer
    {
        $mailer = new PHPMailer(true);
        
        // Configuration de l'e-mail
        $mailer->setFrom('no-reply@example.com', 'No Reply');
        $mailer->addAddress($jetonInscription->getMail()); // Adresse email de l'utilisateur
        
        $mailer->isHTML(true);
        $mailer->Subject = 'Confirmez votre inscription';
        $mailer->Body = 'Cliquez sur ce lien pour confirmer votre inscription : <a href="https://yourwebsite.com/confirm/' . $jetonInscription->getJeton()->getJeton() . '">Confirmer</a>';
        
        return $mailer;
    }
}
