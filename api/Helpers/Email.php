<?php
/*
 * Simple  Helper To Send   Email By   SMTP ;   
 * pashkovdenis@gmail.com   
 * 2014  
 *  
 */ 
namespace Helpers;

require BASE_DIR."/Helpers/class.pop3.php" ; 
require BASE_DIR."/Helpers/class.phpmailer.php" ;
require BASE_DIR."/Helpers/class.smtp.php" ;

class Email extends _Abstract{

	
	private $to ;  
	private $html =  false; 
	private $text  = "";   
	private $subject =  "";   
	private $from  =  ""; 
	private $mailer ; 
	
	// set From  Email 
	// Constant By Default  
	
	public function __construct($from=""){ 
		
		if(empty($from))
		$this->from = NOTIFY_EMAIL; 
 		else
 		$this->from = $from  ; 
 		
 		$this->mailer =  new \PHPMailer() ; 
 		$this->mailer->IsHTML(true); 
 		$this->mailer->IsSMTP() ;  
 		$this->mailer->Host =  SMTP_HOST  ; 
 		$this->mailer->Username = SMTP_USER ;  
 		$this->mailer->Password=  SMTP_PASS ; 
 		$this->mailer->SetFrom(NOTIFY_EMAIL) ; 
 		  
	}
	
	
	public function setTo($mail ){
		$this->to = $mail ;  
		$this->mailer->AddAddress($mail) ; 
		return $this; 
	}
	
	//setHTML  Boolean 
	public function setHTML($h=false ){
		$this->html = (bool) $h ; 
		 $this->mailer->IsHTML($this->html) ;  
		return $this; 
	}
	 
	//setText ; 
	public function setText($t){
		$this->mailer->MsgHTML($t) ; 
		$this->mailer->AltBody  = strip_tags($t) ; 
		  
		return $this; 
	}
	
	
	// set Subject  
	public function setSubject($sub= "" ){ 
	 	$this->mailer->Subject = $sub;  
	 	 return $this; 
	}
	
	
	 //  Try to Send Email : 
	public function send(){ 
	 	$this->mailer->Send();  		
	}
	
	
	

}

