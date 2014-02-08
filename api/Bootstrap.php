<?php
/*
 * Bootstrap Classes For API Framework pashkovdenis@gmail.com
 */
class Bootstrap {
	public $command = null;
	public function __construct() {
		if (version_compare ( PHP_VERSION, '5.4.0' ) <= 0)
			throw new Exception ( "PHP 5,4   or   higher required  ." );
		spl_autoload_register ( array (
				"Bootstrap",
				"autoload" 
		) );
	}
	public static function autoload($class) {
		$class = str_replace ( "\\", "/", $class );
		$class .= ".php";
		if (strstr ( $class, "Controllers" ) && file_exists ( $class ) == false)
			throw new \Exception ( "Class Not Found ", 404 );
		if (file_exists ( $class ))
			require_once $class;
	}
	
	/*
	 * Route Path request Route Returns Request Object :
	 */
	public function route() {
		$url = str_replace ( SITE_NAME, "", $_SERVER ["REQUEST_URI"] );
		$turl = explode ( "?", $url );
		$url = array_shift ( $turl );
		$t2 = explode ( SITE_NAME, $url );
		$url = array_pop ( $t2 );
		$u = explode ( "/", $url );
		$method = "Version";
		$action = 'getResponse';
		
		if (strstr ( $_SERVER ["REQUEST_URI"], "ajax" ))
			$this->ajax = true;
		if ($u [START_PATH] == "" || $u [START_PATH] == "/")
			$this->command = "Version";
		else
			$this->command = ucfirst ( $u [START_PATH] );
		
		if (isset ( $u [START_PATH + 1] ) && $u [START_PATH + 1] != "") {
			$action = strtolower ( $u [START_PATH + 1] );
		}
		foreach ( $u as $k => $v ) {
			if ($k >= START_PATH && isset ( $u [$k] )) {
				
				if (! isset ( $_GET [$v] ) && isset ( $_GET [$u [$k - 1]] ) == false)
					if (isset ( $u [$k + 1] ))
						$_GET [$v] = $u [$k + 1];
			}
		}
		
		return new Request ( $this->command, $_GET, $_POST, $_FILES, $action );
	}
} 