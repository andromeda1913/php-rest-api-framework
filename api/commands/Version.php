<?php
namespace commands;
/*
 * Simple Command Versio 
 * Display version  Number   
 * 
 * 
 */

class Version extends _Abstract{
	
	
	public function hasResponse(){
		return  true ; 
	}
		
	public function getResponse(){
		
     	 $this->allowCache() ; // Allow Caching for that method .  
		 $this->responseObject =  new \Response(true,false);  
		 $this->responseObject->add("version", VERSION) ; 
		 $this->responseObject->add("time",time()); 
		 $this->responseObject->add("service",SITE_NAME);
		 return $this->responseObject ;
		 
	}

	
	public function about(){
	 
		$this->allowCache() ; // Allow Caching for that method .
		$this->responseObject =  new \Response(true,false);
		$this->responseObject->add("version", VERSION) ;
		$this->responseObject->add("time",time());
		$this->responseObject->add("service", file_get_contents(BASE_DIR."/serviceDesc.txt"));
	    return $this->responseObject  ; 
	}
	
	
	
	
	
	
}
