<?php

namespace commands;
/*
 * ____________________________ This Class Provides Token functionality : pashkovdenis@gmail.com 2014 : _____________________________
 */
class Token extends _Abstract {
	public function __construct() {
		$this->responseObject = new \Response ();
	}
	public function getToken(\Request $req) {
		$hash = md5 ( rand ( 0, 887009 ) . $_SERVER ["REMOTE_ADDR"] . session_id () );
		$data = [ 
				"key" => $hash,
				"date" => time () 
		];
		file_put_contents ( BASE_DIR . "/tmp/" . $hash, serialize ( $data ) );
		$this->responseObject->add ( "token", $hash );
		return $this->responseObject;
	}
}
	
