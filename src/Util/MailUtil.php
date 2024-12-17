<?php

namespace App\Util;

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
       
    }
}
