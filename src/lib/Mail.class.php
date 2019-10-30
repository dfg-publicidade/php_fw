<?php

    /*
     * Copyright (C) 2017 DFG Studio.
     *
     * This library is free software; you can redistribute it and/or
     * modify it under the terms of the GNU Lesser General Public
     * License as published by the Free Software Foundation; either
     * version 2.1 of the License, or (at your option) any later version.
     *
     * This library is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
     * Lesser General Public License for more details.
     *
     * You should have received a copy of the GNU Lesser General Public
     * License along with this library; if not, write to the Free Software
     * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
     * MA 02110-1301  USA
     */

    /**
     * Mail utilitary class
     */
    class Mail {

        /**
         * Send a mail (email_template.html must be created)
         * @param $from Origin address
         * @param $fromName Origin name
         * @param $to Destiny address
         * @param $toName Destiny name
         * @param $subject Subject
         * @param $message Message
         * @param $attachments Anexos
         * @return true if mail is sent / false if not
         */
        static function sendFrom($from, $fromName, $to, $toName, $subject, $message, $attachments = null) {
            return self::sendFromTemplate($from, $fromName, $to, $toName, $subject, $message, 'email_template.html', $attachments);
        }

        /**
         * Send a mail (template must be created)
         * @param $from Origin address
         * @param $fromName Origin name
         * @param $to Destiny address
         * @param $toName Destiny name
         * @param $subject Subject
         * @param $message Message
         * @param $template Template
         * @param $attachments Anexos
         * @return true if mail is sent / false if not
         */
        static function sendFromTemplate($from, $fromName, $to, $toName, $subject, $message, $template, $attachments = null) {
            $mail             = new PHPMailer();
            $mail->IsSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->Port       = EMAIL_PORT;
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = 'tls';
            $mail->Username   = EMAIL_USER;
            $mail->Password   = EMAIL_PASSWD;

            $mail->From     = $from;
            $mail->FromName = $fromName;

            $mail->AddAddress($to, $toName);

            $mail->IsHTML(true);
            $mail->CharSet = 'utf-8';

            $mail->Subject = $subject;

            $body = file_get_contents(RES_PATH . $template);

            $body = str_replace('###TEXT###', $message, $body);

            $mail->Body = $body;

            if ($attachments && is_array($attachments)) {
                foreach ($attachments as $attachment) {
                    $mail->AddAttachment($attachment);
                }
            }

            $enviado = $mail->Send();

            $mail->ClearAllRecipients();
            $mail->ClearAttachments();

            if (!$enviado) {
                error_log("Mailer Error: " . $mail->ErrorInfo);
            }

            return $enviado;
        }

        /**
         * Send a mail to multiple recipients (email_template.html must be created)
         * @param $from Origin address
         * @param $fromName Origin name
         * @param $list Destiny address list
         * @param $subject Subject
         * @param $message Message
         * @param $attachments Anexos
         * @return true if mail is sent / false if not
         */
        static function sendToList($from, $fromName, $list, $subject, $message, $attachments = null) {
            return self::sendFromTemplateToList($from, $fromName, $list, $subject, $message, 'email_template.html', $attachments);
        }

        /**
         * Send a mail to multiple recipients (template must be created)
         * @param $from Origin address
         * @param $fromName Origin name
         * @param $list Destiny address list
         * @param $subject Subject
         * @param $message Message
         * @param $template Template
         * @param $attachments Anexos
         * @return true if mail is sent / false if not
         */
        static function sendFromTemplateToList($from, $fromName, $list, $subject, $message, $template, $attachments = null) {
            $mail             = new PHPMailer();
            $mail->IsSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->Port       = EMAIL_PORT;
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = 'tls';
            $mail->Username   = EMAIL_USER;
            $mail->Password   = EMAIL_PASSWD;

            $mail->From     = $from;
            $mail->FromName = $fromName;

            foreach ($list as $to => $toName) {
                $mail->AddBCC($to, $toName);
            }

            $mail->IsHTML(true);
            $mail->CharSet = 'utf-8';

            $mail->Subject = $subject;

            $body = file_get_contents(RES_PATH . $template);

            $body = str_replace('###TEXT###', $message, $body);

            $mail->Body = $body;

            if ($attachments && is_array($attachments)) {
                foreach ($attachments as $attachment) {
                    $fileinfo = pathinfo($attachment);
                    $mail->AddAttachment($fileinfo['filename'], $attachment);
                }
            }

            $enviado = $mail->Send();

            $mail->ClearAllRecipients();
            $mail->ClearAttachments();

            return $enviado;
        }

    }
    