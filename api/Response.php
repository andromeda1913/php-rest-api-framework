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
	  
	 
	 private $encoding  = "windows-1251" ;
	 private $root    =  "root";
	 private $childs  =  []  ;
	 private $current_element ;
	 
	 
	 private $child_tree =  [] ; 
	 
	 
	 
	 
	 //  Init For  XML  REsponse :   
	 public function init($root , $encoding =  "" ){
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
	 	else{
	 		 $this->childs[$child] =  $this->current_element  ;
	 	 
	 	
	 	}
	 
	 	if (!$parent)
 	 	$this->child_tree[]=  $this->childs ; 
	 	
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
	 	
	 	
	 	foreach($this->child_tree as $c)
	 	foreach ($c as $name =>$obj){
	 		  
	 		
	 		$str.= "<{$name} ".$this->renderAttributes($obj)." >";
	 
	 		if (!empty($obj->value))
	 			$str.="{$obj->value}" ;
	 		 
	 		 
	 		// Render  Inner Elemnts :
	 		if (isset($obj->childs) &&  count($obj->childs)){
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
	 	if (isset($obj->childs) &&  count($obj->childs)){
	 	$str.=  $this->renderInner($obj->childs);
	 }
	 $str.= "</{$name}>" ;
	 }
	 return $str ;
	 }
	 
	 
	 
	 
	 
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
		 if  (!isset($this->data[$key])) 
			$this->data["{$key}"]  =  "{$val}";  
		 return $this;  
	}  
	
	
	
	
	
	
	
	
	
	
	
	
	
	  
}

