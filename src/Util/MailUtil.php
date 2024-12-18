<?php

namespace App\Util;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailUtil
{
    /**
     * Envoie un e-mail à l'aide d'un objet PHPMailer.
     *
     * @param PHPMailer $mailer
     * @return bool True si l'e-mail est envoyé avec succès, False sinon.
     * @throws Exception
     */
    public static function sendMail(PHPMailer $mailer): bool
    {
       try {
        $mailer->send();
        echo 'Message envoye avec succès';
        return true;
       } catch (Exception $e) {
        echo 'Le message n a pas ou etre envoye . Erreur : {$mailer->ErrorInfo}';
       }
       return false;
    }

}
