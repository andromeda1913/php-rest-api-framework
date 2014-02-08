<?php

/*
 * __________________________________________ Api Entry Point pashkovdenis@gmail.com date stamp : 1262014 project: --------- _________________________________________
 */

require_once 'config.php'; 

if (DEVELOP == true)
{
	error_reporting(E_ALL) ;   
	ini_set("display_errors", true) ;
} 


$boot = new Bootstrap ();
Observer::init ( $boot );


if (FORMAT == "JSON") {
	
	try {
		
		echo json_encode ( Observer::execute () );
	} catch ( Exception $e ) {
		echo json_encode ( new Response ( false, true, $e->getMessage () ) );
	}
} else {

 



}
 
 