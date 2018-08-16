<?php 

namespace Hcode;

use Rain\Tpl;


Class Mailer {
	Const USERNAME = "bleblebe@gmail.com";
	const PASSWORD = "blabla";
	const NAME_FROM = "Mvc Softwares";
	private $mail;
	public function __construct($toAddress, $toName, $subject, $tplName, $data = array())
	{

		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/email/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed
		);

	Tpl::configure($config);

	$tpl = new Tpl;

	foreach ($data as $key => $value) {
		
		$tpl->assign($key,$value);
	}

	$html = $tpl->draw($tplName,true);

		//Create a new PHPMailer instance
	$this->mail = new \PHPMailer;

	//Tell PHPMailer to use SMTP
	$this->mail->isSMTP();
	$this->mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$this->mail->SMTPDebug = 0;

	//Set the hostname of the mail server
	$this->mail->Host = 'smtp.gmail.com';
	// use
	// $this->email->Host = gethostbyname('smtp.gmail.com');
	// if your network does not support SMTP over IPv6

	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$this->mail->Port = 587;

	//Set the encryption system to use - ssl (deprecated) or tls
	$this->mail->SMTPSecure = 'tls';

	//Whether to use SMTP authentication
	$this->mail->SMTPAuth = true;

	//Username to use for SMTP authentication - use full email address for gmail
	$this->mail->Username = Mailer::USERNAME;

	//Password to use for SMTP authentication
	$this->mail->Password = Mailer::PASSWORD;

	//Set who the message is to be sent from
	$this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);

	//Set an alternative reply-to address
	//$this->email->addReplyTo('replyto@example.com', 'First Last');

	//Set who the message is to be sent to
	$this->mail->addAddress($toAddress, $toName);
	

	//Set the subject line
	$this->mail->Subject = $subject;

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$this->mail->msgHTML($html);
	$this->mail->msgHTML(utf8_decode($html));
	//Replace the plain text body with one created manually
	$this->mail->AltBody = 'This is a plain-text message body';

	//Attach an image file
	//$this->email->addAttachment('images/phpmailer_mini.png');

	//Section 2: IMAP
	//IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
	//Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
	//You can use imap_getmailboxes($imapStream, '/imap/ssl') to get a list of available folders or labels, this can
	//be useful if you are trying to get this working on a non-Gmail IMAP server.
	}
	public function send(){

		return $this->mail->send();
	}


}

 ?>