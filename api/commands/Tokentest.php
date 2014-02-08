<?php

namespace commands;
/*
 * Test Token Controller
 */
class Tokentest extends _Abstract {
	public function __construct() {
		$this->responseObject = new \Response ();
	}
	public function testtoken() {
		$this->requireToken ();
		$this->responseObject->add ( "success", "YES" );
		return $this->responseObject;
	}
}
 