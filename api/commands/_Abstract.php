<?php

namespace commands;

use Helpers\Cached;
/*
 * Abstract Command Interface pashkovdenis@gmail.com
 */
class _Abstract {
	public $observer = null;
	public $hasresponse = true;
	public $responseObject = null;
	public $request = null;
	public $name = null;
	public $cached_allowed = [ ]; // list of the methods that can be cahced .
	public $cached = [ ];
	private $is_cache_allowed = false;
	public $toke_required = [ ]; // handle list of required token for methods .
	public function attach(\Observer $ob) {
		$this->observer = $ob;
		$this->responseObject = new \Response ();
		
		if (isset ( $this->cached [get_class ( $this )] ) == false)
			$this->cached [get_class ( $this )] = [ ];
		if (Cached::isAviable ()) {
			$this->cached_allowed = true;
			
			if (Cached::get ( get_class ( $this ) )) {
				$this->cached [get_class ( $this )] = Cached::get ( get_class ( $this ) );
			}
		}
		return $this;
	}
	public function isCacheAllowed() {
		return $this->cached_allowed;
	}
	
	/*
	 * Required Token
	 */
	public function requireToken() {
		$callers = debug_backtrace ();
		$method = $callers [1] ['function'];
		$this->toke_required [$method] = 1;
		return $this;
	}
	
	// Enable and Disable Cache
	public function allowCache() {
		$callers = debug_backtrace ();
		$method = $callers [1] ['function'];
		$this->cached [get_class ( $this )] [$method] = 1;
		return $this;
	}
	public function isMethodCacheAllowed($name) {
		return isset ( $this->cached [get_class ( $this )] [$name] );
	}
	
	/*
	 * set Error Message
	 */
	public function setError($msg) {
		$this->responseObject = new \Response ( false, true, $msg );
	}
	public function disableCache() {
		$callers = debug_backtrace ();
		$method = $callers [1] ['function'];
		unset ( $this->cached [get_class ( $this )] [$method] );
		return $this;
	}
	public function addIntoCache($name, $obj) {
		$this->cached [get_class ( $this )] [$name] = $obj;
		
		Cached::set ( get_class ( $this ), $this->cached [get_class ( $this )] );
		return $this;
	}
	public function hasInCache($name) {
		if (isset ( $this->cached [get_class ( $this )] [$name] ) && $this->cached [get_class ( $this )] [$name] instanceof \Response)
			return $this->cached [get_class ( $this )] [$name];
		else
			return false;
	}
	public function Execute(\Request $req = null) {
		if ($this->observer == null)
			throw new \Exception ( "Observer not set for command " . __CLASS__ );
		$this->responseObject->add ( "method", get_class ( $this ) );
		$this->request = $req;
		// $this->responseObject->add("override","Ovverrride default execute method") ;
	}
	public function hasResponse() {
		return $this->hasresponse;
	}
	public function getResponse() {
		if ($this->responseObject == null)
			throw new \Exception ( "The is no Output   IN  " . __CLASS__ . "Command  " );
		return $this->responseObject;
	}
	public function canHandle() {
		return true;
	}
	
	/*
	 * ______________________ Check Where ver yoken is valid ______________________
	 */
	public function isValidToken($token) {
		if (! empty ( $token )) {
			
			if (file_exists ( BASE_DIR . "/tmp/" . $token )) {
				$data = unserialize ( file_get_contents ( BASE_DIR . "/tmp/" . $token ) );
				if ($data ["key"] == $token)
					return true;
			}
		}
		return false;
	}
}
  