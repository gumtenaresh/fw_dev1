<?php

/**
 *  Mailer Utility class
 */
class Mailer {

    /**
     *  Generic function to send authenticated email via smtp
     */
    public function sendMail($to, $subject, $body, $from, $attachments = false, $priority = 2, $cc = null, $bcc = null) { //v0.3
        include_once (DOC_ROOT_PATH . '/libs/PHPMailer/class.phpmailer.php');
        //return false;

        $from_name = null;
        $from_email = null;
        $to_name = null;
        $to_email = null;

        $mail = new PHPMailer();

        if ($to[0] == ':') {
            return false;
        } // this email address is disabled

        if (preg_match('/(.+)<(.+)>/i', $from, $matches)) {
            if (!empty($matches[1])) {
                $from_name = trim($matches[1]);
            }
            if (!empty($matches[2])) {
                $from_email = trim($matches[2]);
            }
        }

        if (empty($from_email)) {
            $from_email = $from;
        }

        if (preg_match('/(.+)<(.+)>/i', $to, $matches)) {
            if (!empty($matches[1])) {
                $to_name = trim($matches[1]);
            }
            if (!empty($matches[2])) {
                $to_email = trim($matches[2]);
            }
        }

        if (empty($to_email)) {
            $to_email = $to;
        }

        if (is_array($body)) {
            if (!empty($body['html'])) {
                $mail->IsHTML(true);
                $mail->Body = $body['html'];
                $htmlbody = $body['html'];
            }
            if (!empty($body['text'])) {
                $mail->AltBody = $body['text'];
                $body = $body['text'];
            }
        } else {
            $mail->Body = $body;
        }

        //Add Cc
        if (!empty($cc)) {
            if (strstr($cc, ',')) {
                $t = explode(',', $cc);
                foreach ($t as $cc_email) {
                    $mail->AddCC($cc_email);
                }
            } else {
                $mail->AddCC($cc);
            }
        }

        //Add Bcc
        if (!empty($bcc)) {
            if (strstr($bcc, ',')) {
                $t = explode(',', $bcc);
                foreach ($t as $bcc_email) {
                    $mail->AddCC($bcc_email);
                }
            } else {
                $mail->AddBCC($bcc);
            }
        }

        # Priority?
        if ($priority == 3) {
            $pri = 1;
        } elseif ($priority == 1) {
            $pri = 5;
        } else {
            $pri = 3;
        }
        // Attachments
        if ($attachments !== false) {
            foreach ($attachments as $attach) {
                $file = $attach['tmp_name']; //we're assuming attachments are coming from $_FILES
                if (is_file($file)) {
                    # File name of Attachment
                    echo $filename = $attach['name'];
                    $mail->AddAttachment($file, $filename);
                }
            }
        }

        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = MAIL_HOST;
        $mail->Port = SMTP_PORT;
        $mail->Username = MAIL_AUTHENTICATION_EMAIL;
        $mail->Password = MAIL_AUTHENTICATION_PASSWORD;
        $mail->SMTPKeepAlive = 'true';
        $mail->SMTPDebug = false;
        if (USE_SECURE_CONNECTION) {
            $mail->SMTPSecure = 'ssl';
        } else {
            $mail->SMTPSecure = 'tls';
        }
        $mail->Hostname = $_SERVER['SERVER_NAME'];
        $mail->From = $from_email;
        $mail->FromName = str_replace('"', '', $from_name);
        $mail->Sender = $from_email;
        $mail->Subject = $subject;
        $mail->AddAddress($to_email, $to_name);
        $mail->Priority = $pri;
        if (!$mail->Send()) {
            $mail_sent = FALSE;
        } else {
            $mail_sent = TRUE;
        }

        $mail->ClearAddresses();
        $mail->SmtpClose();

        return $mail_sent;
    }

    public function sendMail2($to, $subject, $body, $from, $attachments = false, $priority = 2, $cc = null, $bcc = null) { //v0.3
        include_once (DOC_ROOT_PATH . '/libs/PHPMailer/class.phpmailer.php');
        //return false;

        $from_name = null;
        $from_email = null;
        $to_name = null;
        $to_email = null;

        $mail = new PHPMailer();

        if ($to[0] == ':') {
            return false;
        } // this email address is disabled

        if (preg_match('/(.+)<(.+)>/i', $from, $matches)) {
            if (!empty($matches[1])) {
                $from_name = trim($matches[1]);
            }
            if (!empty($matches[2])) {
                $from_email = trim($matches[2]);
            }
        }

        if (empty($from_email)) {
            $from_email = $from;
        }

        if (preg_match('/(.+)<(.+)>/i', $to, $matches)) {
            if (!empty($matches[1])) {
                $to_name = trim($matches[1]);
            }
            if (!empty($matches[2])) {
                $to_email = trim($matches[2]);
            }
        }

        if (empty($to_email)) {
            $to_email = $to;
        }

        if (is_array($body)) {
            if (!empty($body['html'])) {
                $mail->IsHTML(true);
                $mail->Body = $body['html'];
                $htmlbody = $body['html'];
            }
            if (!empty($body['text'])) {
                $mail->AltBody = $body['text'];
                $body = $body['text'];
            }
        } else {
            $mail->Body = $body;
        }

        //Add Cc
        if (!empty($cc)) {
            if (strstr($cc, ',')) {
                $t = explode(',', $cc);
                foreach ($t as $cc_email) {
                    $mail->AddCC($cc_email);
                }
            } else {
                $mail->AddCC($cc);
            }
        }

        //Add Bcc
        if (!empty($bcc)) {
            if (strstr($bcc, ',')) {
                $t = explode(',', $bcc);
                foreach ($t as $bcc_email) {
                    $mail->AddCC($bcc_email);
                }
            } else {
                $mail->AddBCC($bcc);
            }
        }

        # Priority?
        if ($priority == 3) {
            $pri = 1;
        } elseif ($priority == 1) {
            $pri = 5;
        } else {
            $pri = 3;
        }
        // Attachments
        if ($attachments !== false) {
            foreach ($attachments as $attach) {
                $file = $attach['tmp_name']; //we're assuming attachments are coming from $_FILES
                if (is_file($file)) {
                    # File name of Attachment
                    echo $filename = $attach['name'];
                    $mail->AddAttachment($file, $filename);
                }
            }
        }

        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = MAIL_HOST;
        $mail->Port = SMTP_PORT;
        $mail->Username = MAIL_AUTHENTICATION_EMAIL;
        $mail->Password = MAIL_AUTHENTICATION_PASSWORD;
        $mail->SMTPKeepAlive = 'true';
        $mail->SMTPDebug = false;
        if (USE_SECURE_CONNECTION) {
            $mail->SMTPSecure = 'ssl';
        } else {
            $mail->SMTPSecure = 'tls';
        }
        $mail->Hostname = $_SERVER['SERVER_NAME'];
        $mail->From = $from_email;
        $mail->FromName = str_replace('"', '', $from_name);
        $mail->Sender = $from_email;
        $mail->Subject = $subject;
        $mail->AddAddress($to_email, $to_name);
        $mail->Priority = $pri;
        if (!$mail->Send()) {
            $mail_sent = FALSE;
        } else {
            $mail_sent = TRUE;
        }

        $mail->ClearAddresses();
        $mail->SmtpClose();

        return $mail_sent;
    }

}