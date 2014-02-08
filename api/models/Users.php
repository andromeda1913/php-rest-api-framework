<?php
namespace models;

class Users extends _Abstract{ 
	
	
		public function __construct(){
			parent::__construct() ; 
			$this->setTable("users") ;  // set Table  For  that model ;   
			
			
		}
	
	
		
		// 
		public function insertUser($username , $email){ 
			$id = $this->generalInsert([
				"username"=>$username, 
				"email"=>$email , 
			 ]); 
			return $id  ;
		}
	
		
		// try to select record   by  username  
		public function login($username){
			 $loaded  = $this->selectByAll("username", $username) ;  
		 
			 if ($loaded[0]->id)
			 	return $loaded[0]->id ; 
			 else
			 	 return false; 
			 
		}
		
		
		
		
	
}
 