<?php
/*
 * __________________ Request Command Encapsulated WebService Request pashkovdenis@gmail.com __________________
 */
class Request {
	
	
	public $type = null;
	public $name = null; 
	public $action =  null  ; 
	public $attributes = [ ];
	public $files = [ ];
	public $hasFiles = false;
	public $base64 = [ ];
	 
	public function __construct($name, $GET, $POST, $FILES ,$action='index') {
		$this->type = $_SERVER ['REQUEST_METHOD'];
		$this->name = $name;
		$this->action=   $action ;  
		
		foreach ( $GET as $name => $value ) {
			$this->attributes [$name] = ($value);
		}
		
		foreach ( $POST as $name => $value ) {
			$this->attributes [$name] = ($value);
			// try catch base64 images ;
			if (base64_encode ( base64_decode ( $value ) ) === $value) {
				$this->base64 [$name] = base64_decode ( $value );
			}
		}
		
		if (count ( $FILES ) > 0) {
			$this->hasFiles = true;
			foreach ( $FILES as $name => $f ) {
				$this->files [$name] = $f ["tmp_name"];
			}
		}
	}
	
	//
	public function contains($name) {  
	 
		if (in_array ( $name, $this->attributes ))
			return false;
		
		if (empty ( $this->attributes [$name] ))
			return false;
		
		return true;
	}
	
	/*
	 * get Attribute
	 */
	public function get($name) {
		$name = trim ( $name );
		if (isset ( $this->attributes [$name] ))
			return $this->attributes [$name]; 
		else
			return false ; 
		
	}
} 