<?php 
/*
 * Provides XML REsponse  
 * Api Rest Framework  
 * pashkovdenis@gmail.com  
 * 
 */ 


class ResponseXML {

	
	private $encoding  = "windows-1251" ;  
	private $root    =  "root";   
	private $childs  =  []  ; 
	private $current_element ; 
	
	public function __construct($root , $encoding =  "" ){
		$this->root   = $root ;  
		if (!empty($encoding) ) 
			$this->encoding  = $encoding ; 
		$obj  = new stdClass() ;
		$obj->value = "";
		$obj->attributes = [ ]; 
		$obj->childs =  [] ; 
		$this->current_element =  $obj ;  
		
	}
	
	
	//  creating  
	public function createChild(  $child , $parent = false ){
		$obj  = new stdClass() ; 
		$obj->value = "";  
		$obj->attributes = [ ];   
		
		$this->current_element  =  $obj  ; 
				if ($parent &&  isset($this->childs[$parent]))  
				 $this->childs[$parent]->childs[$child]  = $this->current_element ;     
				else 
				$this->childs[$child] =  $this->current_element  ;  
		
		return $this->current_element;  		
	}
	
	
	//Add Value into the Child  :  
	public function addValue($value){ 
		$this->current_element->value  =   strip_tags($value) ; 
		return $this;  	
	}
	
		
	// addd Attribute into the Child :   
	public function add_attribute($name ,  $value){
		$this->current_element->attributes[$name] =  $value  ;  
		return $this;  		
	}
	 
	//  REnder  XML    REsponse :  
	public function __toString(){
		
		$str = '<?xml version="1.0" encoding="'.$this->encoding.'"?>
				<'.$this->root.' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">';  
		 foreach ($this->childs as $name =>$obj){
		 	$str.= "<{$name} ".$this->renderAttributes($obj)." >";
		 	  
		 	 if (!empty($obj->value))  
		 	 	$str.="{$obj->value}" ;  
		 	 
		 	 
		 	 // Render  Inner Elemnts :   
		 	 if (count($obj->childs)){
		 	  	 $str.=  $this->renderInner($obj->childs); 
		 	  }

		 	  
		 	$str.= "</{$name}>" ;
		 } 		
		return $str."</{$this->root}>";  
	}
	
	
	
	
	
	private function renderAttributes($obj){
		$str=  ""; 
		if (isset($obj->attributes )) {
		 	foreach($obj->attributes as $n =>$v)   
				$str =  " {$n}= '{$v}'  "; 
		 }
		 	return $str ;  
	}
	
	
	
	
	
	///  render Inner Elemtns   
	private function renderInner( $objects =  []  ){
		 $str=  ""; 
			foreach($objects as $name => $obj){
				$str.= "<{$name} ".$this->renderAttributes($obj)." >";
			 	if (!empty($obj->value))
					$str.="{$obj->value}" ;
				 // Render  Inner Elemnts :
				if (count($obj->childs)){
					$str.=  $this->renderInner($obj->childs);
				}
			 	$str.= "</{$name}>" ;
			 }  	
		return $str ; 
	}
	
	

}
 