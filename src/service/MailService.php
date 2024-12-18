<?php
namespace App\Service;
require '../Util/PHPMailer/src/PHPMailer.php';
require '../Util/PHPMailer/src/SMTP.php';
require '../Util/PHPMailer/src/Exception.php';

use App\Entity\JetonInscription;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function createMailerFromJetonInscription(JetonInscription $jetonInscription): PHPMailer
    {
        $mailer = new PHPMailer(true);

        $mailer->isSMTP();
        $mailer->Host = 'smtp.gmail.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = "no-reply@example.com";
        $mailer->Password = 'isck oeef rekg dogo';
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Port = 587;

        // Configuration de l'e-mail
        $mailer->setFrom('no-reply@example.com', 'No Reply');
        $mailer->addAddress($jetonInscription->getMail()); // Adresse email de l'utilisateur
        
        $mailer->isHTML(true);
        $mailer->Subject = 'Confirmez votre inscription';
        $mailer->Body = 'Cliquez sur ce lien pour confirmer votre inscription : <a href="http://localhost:8000/confirm/' . $jetonInscription->getJeton()->getJeton() . '">Confirmer</a>';
        
        return $mailer;
    }
}
