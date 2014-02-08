<?php
/*
 * Simple  Helper To Send   Email By   SMTP ;   
 * pashkovdenis@gmail.com   
 * 2014  
 *  
 */ 
namespace Helpers;

class Email extends _Abstract{

	
	private $to ;  
	private $html =  false; 
	private $text  = "";   
	private $subject =  "";   
	private $from  =  ""; 
	
	
	// set From  Email 
	// Constant By Default  
	public function __construct($from=""){ 
		if(empty($from))
		$this->from = NOTIFY_EMAIL; 
 		else
 		$this->from = $from  ; 
 		
	}
	
	
	public function setTo($mail ){
		$this->to = $mail ; 
		return $this; 
	}
	
	//setHTML  Boolean 
	public function setHTML($h=false ){
		$this->html = (bool) $h ; 
		return $this; 
	}
	 
	//setText ; 
	public function setText($t){
		$this->text = $t ;  
		return $this; 
	}
	
	
	// set Subject  
	public function setSubject($sub= "" ){ 
		$this->subject = $sub ; 
		return $this; 
	}
	
	
	 //  Try to Send Email : 
	public function send(){ 
		
		
		
		
	}
	
	
	

}

