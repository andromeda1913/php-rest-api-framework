<?php
 /*
  * Response  Object   That implements   Json Response  
  * pashkovdenis@gmail.com  
  * 2014  
  * 
  */


 class Response implements JsonSerializable{
	 
	 private $data      =  [] ; 
	 private $status    =    true ; 
	 private $version   =   VERSION ;  
	 public  $error     =   false;   
	 public  $error_messg  = ""; 
	  
	 
	 
	 public function __construct($status  =  true,  $error = false ,  $error_m =  ""){
	 	$this->error =  $error ;  
	 	$this->status = $status ;  
	 	$this->error_messg =  $error_m;  
	  }
	 
	 // Serialize  Out 
	public function jsonSerialize() {
		 return [
		 	"ver"=>$this->version  ,  
		 	"status"=>$this->status  , 
		 	"error"=>$this->error , 
		 	"error_msg"=> $this->error_messg ,  
		 	"data" => $this->data ,  
		 ]; 
	}

	// insert $data  
	public function add($key ,  $val=null   ){
		
		
		
		 if  (!isset($this->data[$key])) {

		  if (is_string($val))	
		 	$this->data["{$key}"]  =  "{$val}";  
			else
				$this->data["{$key}"] = $val ; 
		 
		 }
		 
		 
		 return $this;  
	}  
	
	
	
	
	
	
	
	
	
	
	
	
	
	  
}

