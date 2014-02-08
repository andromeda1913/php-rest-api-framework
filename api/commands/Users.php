<?php
namespace commands;

use models\Users as MUsers;
class Users extends _Abstract{ 
	
	 // has Response  
	public function hasResponse(){
		return true ; 
	} 
	
	 
	
	// Simple Register Method   for  testing :   
	public function register(\Request $req = null ){
		if  ($req->contains("username") && $req->contains("email")){
			 $model =  new  MUsers() ;  
			 
			 // Create user and add id  in response  
			 $this->responseObject->add("user_id",  
			 		$model->insertUser( $req->get("username") , $req->get("email"))); 
			
			
		} else{
			$this->setError("username ,email required") ;
		}
		return $this->responseObject ;
	}
	
	
	// Simple Login  Method  
	
	public function login(\Request $req = null){
		$model =  new  MUsers() ;  
		$username  = $req->get("username") ; 
		
		if ($username){
			 // try to find out user  
			$id =  $model->login($username); 
			if ($id) {
				$this->responseObject->add("user_id", $id) ; 
				$this->responseObject->add("token", $model->createToken()) ; 
				
			}else{
				$this->setError("User Not  Found") ; 
			}
			
		}else{
			$this->setError("Please enter username") ;
		}
		
		return $this->responseObject ;  
	}
	
	
	// get List of users :   
	public function getlist(\Request $req = null){
		 $model =  new MUsers(); 
		 $this->requireToken() ; // Token Required For that method ;  
		  $all = $model->selectAll(); 
		  $this->responseObject->add("all", $all) ; 
		return $this->responseObject; 
	}
	
	
	
}
 