<?php
    /* Example with SMTP: 
        $emails = 'tufik2@gmail.com, tufik2@hormail.com'
        OR
        $emails = array();
        array_push($emails, array('email', 'name')); 
        array_push($emails, array('email', 'name'));
        
        $cuppa->mail->configure("user@gmail.com", "password", "smtp.gmail.com", 465, "ssl");
        echo $cuppa->mail->send("fromName", "fromEmail", "affair", emails,  body, body);
    */
	class SendMail{
        public $mailer = null;
        public $transport = null;
        
        function configure($user = "", $pass = "", $host = "localhost", $port = 25, $security = null){
            require_once("swiftMailer/swift_required.php");
            $this->transport = Swift_SmtpTransport::newInstance($host, $port, $security);
            if($user) $this->transport->setUsername($user);
            if($pass) $this->transport->setPassword($pass);
            $this->mailer = Swift_Mailer::newInstance($this->transport);
        }
        function send($fromName, $fromEmail, $subject, $to = "", $body = "", $cc = "", $bcc = ""){
            $message = Swift_Message::newInstance();
            $message->setSubject($subject);
            $message->setFrom(array($fromEmail => $fromName));
            //++ sent to
                if($to){
                    if(is_string($to)){
                        $to = explode(",", $to);
                        for($i = 0; $i< count($to); $i++){ $message->addTo( trim($to[$i]), trim($to[$i]) ); }
                    }else{
                        forEach($to as $email){
                            if(is_string($email)) $message->addTo(trim($email));
                            else $message->addTo(trim($email[0]), trim($email[1]));
                        }
                    }
                }
            //--
            //++ cc
                if($cc){
                    if(is_array($cc)){
                        $tmp = array();
                        forEach($cc as $i => $email){
                            if(is_string($email)) array_push($tmp,"<".trim($email).">");
                            else array_push($tmp, trim($email[1])." <".trim($email[0]).">");
                        }
                        $cc = join(",", $tmp);
                    }
                    $headers .= 'Cc: '.$cc."\r\n";
                }
            //--
            //++ bcc
                if($bcc){
                    if(is_string($bcc)){
                        $bcc = explode(",", $bcc);
                        for($i = 0; $i< count($bcc); $i++){ $message->addBcc( trim($bcc[$i]), trim($bcc[$i]) ); }
                    }else{
                        forEach($bcc as $email){
                            if(is_string($email)) $message->addBcc(trim($email));
                            else $message->addBcc(trim($email[0]), trim($email[1]));
                        }
                    }
                }
            //--
            $message->setBody($body, 'text/html');
            $this->mailer->send($message, $failedRecipients);
            if(count($failedRecipients)) return 0;
            else return 1;
		}
        function sendNative($fromName, $fromEmail, $subject, $to = "", $body = "", $cc = "", $bcc = ""){
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: '.$fromName.' <'.$fromEmail.'>' . "\r\n";
            //++ to
                if($to){
                    if(is_array($to)){
                        $tmp = array();
                        forEach($to as $i => $email){
                            if(is_string($email)) array_push($tmp,"<".trim($email).">");
                            else array_push($tmp, trim($email[1])." <".trim($email[0]).">");
                        }
                        $to = join(",", $tmp);
                    }
                }
            //--
            //++ cc
                if($cc){
                    if(is_array($cc)){
                        $tmp = array();
                        forEach($cc as $i => $email){
                            if(is_string($email)) array_push($tmp,"<".trim($email).">");
                            else array_push($tmp, trim($email[1])." <".trim($email[0]).">");
                        }
                        $cc = join(",", $tmp);
                    }
                    $headers .= 'Cc: '.$cc."\r\n";
                }
            //--
            //++ bcc
                if($bcc){
                    if(is_array($bcc)){
                        $tmp = array();
                        forEach($bcc as $i => $email){
                            if(is_string($email)) array_push($tmp,"<".trim($email).">");
                            else array_push($tmp, trim($email[1])." <".trim($email[0]).">");
                        }
                        $bcc = join(",", $tmp);
                    }
                    $headers .= 'Bcc: '.$bcc."\r\n";
                }
            //--
            return @mail($to, $subject, $body, $headers);
        }
	}
?>