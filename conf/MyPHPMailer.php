<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class MyPHPMailer extends PHPMailer
{
	public static $mail_source = 'copat01@gmail.com';
	public function __construct($exceptions = null)
    {
       parent::__construct($exceptions);
       //Server settings
	   //$this->SMTPDebug = 2;                                 // Enable verbose debug output
	   

	     ///#### solution 1
	    $this->isSMTP();                                      // Set mailer to use SMTP
	    $this->Host = 'smtp.mapcom-group.com;smtp.gmail.com';  // Specify main and backup SMTP servers
	    $this->SMTPAuth = true;                               // Enable SMTP authentication
	    $this->Username = 'lgs_soft@mapcom-group.com';                 // SMTP username
	    $this->Password = 'logisoft';                           // SMTP password
	    $this->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	    $this->Port = 587;                                    // TCP port to connect to
	    
	    ///#### fin solution 1

	    ///#### solution 2
	    /*$this->isSMTP();
	    $this->SMTPAuth = true; // authentication enabled
		$this->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		$this->Host = "smtp.gmail.com";
		$this->Port = 465; // or 465; // or 587
		$this->IsHTML(true);
		$this->Username = "copat01@gmail.com";
		$this->Password = "copatri1";*/
	    ///#### fin solution 2
		$this->CharSet = 'UTF-8';
	    
    }
    public function sendMail($subject, $message, $destinataires = array(), $attachement = '',$emetteur = '', $is_html_message = true)
	{
		if(trim($emetteur)=='') $emetteur = self::$mail_source;
		try { 

		    //Recipients
		    $this->setFrom($emetteur, 'Mailer');
		    foreach($destinataires as $adresseMail )
			{
				if(trim($adresseMail)!='')
				{
					$this->AddAddress($adresseMail);
				}				 
			} 
		    //Attachments
		    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		    //Content
		    $this->isHTML($is_html_message);                                  // Set email format to HTML
		    $this->Subject = $subject;
		    if($is_html_message)
		    {
		    	$this->Body    = $message; //'This is the HTML message body <b>in bold!</b>';
		    }
		    else
		    {
		    	$this->AltBody = $message; //'This is the body in plain text for non-HTML mail clients';
		    }
    		$this->send();
		    return true;
		} catch (Exception $e) {
		    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
		return false;
	}
}

?>