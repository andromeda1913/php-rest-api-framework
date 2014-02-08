<?php  
use commands\Version;
/*  __________________________
  *  Observer Class     For  Api  Framework 
  *  pashkovdenis@gmail.com   
  * _________________________ 
  * 
  */
   
 class Observer {

	
	private 		 $init = false ;   
	public static 	 $observer  = null    ;    	
	private			 $boot  =null   ;     
	 
	 
	
 	 public static function init(Bootstrap $boot){
		 	if (self::$observer==null) 
		 	self::$observer = new self(); 
		    self::$observer->boot  = $boot  ;
		    self::$observer ->init = true  ;
	 }

	 
	 /* __________________
	  * 	Find Command.    
	  * 	Execute  Request.   
	  * __________________ 
	  * 
	  */
	 
	public static function execute(){
		$result  = ""; 
	    $request =  self::$observer->boot->route();   	
	  	if ($request){ 
	  	 
		    if (file_exists( BASE_DIR. "/commands/". $request->name.".php")){
		    	// try to Execute Method . 
				 $class =  "commands\\".$request->name ; 
				 
				 $method = new $class ();  
				 $method->attach(self::$observer);   
				 
				 if ($method->isCacheAllowed()  &&  $method->hasInCache($request->action)){
				 	 
				 	return  $method->hasInCache($request->action); 	
				 }		 

				   // Main Method  that Runs    Everytime :   
				   $resp =  $method->execute($request);  
             
				 
				 	 
				 	if(  $method->canHandle() &&  $method->hasResponse()) {
				  
				 	
				 	 if (!method_exists($method, $request->action)){
				 	 	
				 	 	$resp=  new Response(false,true, "Method not found : ".  $request->action); 
				 	 	return $resp ; 
				 	 }else{
				 	 	$resp =   $method->{$request->action}($request);
				 	 	
				 	 	 
				 	  
				 	 }	
					  if (isset($method->toke_required[$request->action])){
					 	$token  = $request->get("token") ;  
					 	 
					 	 if ( ! $method->isValidToken($token) )  { 
					 	 	  $resp=  new Response(false,true, "Wrong token or Token null ".  $token) ; 
					 	 	 return $resp;  					 	 	
					 	   }
					 	
					 }
					 
					  
					 if ($method->isCacheAllowed() &&  $method->isMethodCacheAllowed($request->action)) {
					 	$method->addIntoCache($request->action, $resp) ;  
					   }
				   return $resp  ;  
					 
				}
					
					else {
						 $resp =   new Response(false, true , "Method unable to handle request");  
						 return $resp;   
					}
					  
 		  	}else{
		    	throw  new Exception("Method not found Exception ". $request->name);
		    } 
	    }  
	    if  ($result instanceof  JsonSerializable) 
	    	$result = json_encode($result); 
	    else  
	    	throw new Exception("no command Found ") ;
		return $result ; 
	}
	  
	

	  // TestDB : 
	  public static function testDB(){
		(new test()) 
			->testInsert()  
			->testSelect() ; 
	}
	 
	
	/*
	 * Get Post PAram   
	 * 
	 */
	public function getPostParam($param) {
		if ($this->getRequestType () == 'POST') {
			if (isset ( $_POST [$param] ) && is_array ( $_POST [$param] ))
				return $_POST [$param];
				
			if (isset ( $_POST [strtolower ( $param )] ))
				return ($_POST [strtolower ( $param )]);
		} elseif ($this->getRequestType () == "PUT") {
			parse_str ( file_get_contents ( "php://input" ), $post_vars );
			return $post_vars;
		}
	
		return false;
	}
	
	
	/*
	 * Get get Param 
	 */
	public function getGetParam($param, $default = '') {
		if (isset ( $_GET [strtolower ( $param )] ))
			return ($_GET [strtolower ( $param )]);
		if ($default != '')
			return $default;
	
		if (isset ( $_GET [strtolower ( $param )] ) == false && isset ( $_POST [$param] ))
			return $_POST [$param];
	
		return false;
	}
	
	
	
	/*
	 * Upload Files  
	 * 
	 */
	public function UploadFile($path = '', $prefix = ''  ) {
		$name2 = false;
		if ($path == '')
			$path = BASE_DIR . $path;
		foreach ( $_FILES as $file ) {
			if (isset ( $file ["tmp_name"] )) {
				$name2 = $prefix . $file ["name"];
				move_uploaded_file ( $file ["tmp_name"], $path . $prefix . $file ["name"] );
			}
		}
		
	 	//  try to find   base 64 encoded   data   in post  and upload   it .   
		foreach ( $_POST as $name => $value ) {
		 
		  	if (base64_encode ( base64_decode ( $value ) ) === $value) {   
		  		 
		  
				 $data =  base64_decode ( $value );
			 	$name2  =   $prefix. date("Y-m-d"). rand(0,9999). ".png" ; 
			 	file_put_contents($path. $name2, $data) ; 
			 	return  $name2 ;  
		  	}
		} 
 
		return $name2;
	}
	
	
	
	public function UploadFiles($path = '', $prefix = '' ) {
		$name = [ ]; // Array of files uploaded . 
		if ($path == '')
			$path = BASE_DIR . $path;
		foreach ( $_FILES as $files ) {
			// Server
			foreach ( $files ["tmp_name"] as $index => $filed ) {
				move_uploaded_file ( $files ["tmp_name"] [$index], $path . $prefix . $files ["name"] [$index] );
				$name [] = $prefix . $files ["name"] [$index];
			}
				
		 
		}
		return $name;
	}
	
	
	
	// Create of Thumbnail  :  
	public function createThumbnail($filename, $prefix =  '_small' ){
		
		if (empty($filename)) 
			 return false ; 
		$im = null  ; 
		
		$final_width_of_image = 200;
		if(preg_match('/[.](jpg)$/', $filename)) {
			$im = imagecreatefromjpeg($filename);
		} else if (preg_match('/[.](gif)$/', $filename)) {
			$im = imagecreatefromgif( $filename);
		} else if (preg_match('/[.](png)$/', $filename)) {
			$im = imagecreatefrompng( $filename);
		}
		
		if (isset($im) && $im ){
		$ox = imagesx($im);
		$oy = imagesy($im);
		$nx = $final_width_of_image;
		$ny = floor($oy * ($final_width_of_image / $ox));
		$nm = imagecreatetruecolor($nx, $ny);
		imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);
		imagejpeg($nm,   $filename.  $prefix);
		}
		
		
		 return $this  ; 
		
	}
	
	
	
	
	
	
	
	
	// send file 
	public function sendFile($filename = 'file.csv', $data = '') {
		// OUPUT HEADERS
		header ( "Pragma: public" );
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Cache-Control: private", false );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Disposition: attachment; filename=" . $filename . ";" );
		header ( "Content-Transfer-Encoding: binary" );
		echo $data;
		exit ( 0 );
	} 
	
	
	
	
	/*
	 * 
	 * Filters  
	 * Safe Html   
	 * Auto Link .  
	 *  
	 */ 

	
	public function auto_link($str) {
	 	// don't use target if tail is follow
		$regex ['file'] = "gz|tgz|tar|gzip|zip|rar|mpeg|mpg|exe|rpm|dep|rm|ram|asf|ace|viv|avi|mid|gif|jpg|png|bmp|eps|mov";
		$regex ['file'] = "(\.($regex[file])\") TARGET=\"_blank\"";
		// define URL ( include korean character set )
		$regex ['http'] = "(http|https|ftp|telnet|news|mms):\/\/(([\xA1-\xFEa-z0-9:_\-]+\.[\xA1-\xFEa-z0-9:;&#=_~%\[\]\?\/\.\,\+\-]+)([\.]*[\/a-z0-9\[\]]|=[\xA1-\xFE]+))";
		// define E-mail address ( include korean character set )
		$regex ['mail'] = "([\xA1-\xFEa-z0-9_\.\-]+)@([\xA1-\xFEa-z0-9_\-]+\.[\xA1-\xFEa-z0-9\-\._\-]+[\.]*[a-z0-9]\??[\xA1-\xFEa-z0-9=]*)";
		// If use "wrap=hard" option in TEXTAREA tag,
		// connected link tag that devided sevral lines
		$src [] = "/<([^<>\n]*)\n([^<>\n]+)\n([^<>\n]*)>/i";
		$tar [] = "<\\1\\2\\3>";
		$src [] = "/<([^<>\n]*)\n([^\n<>]*)>/i";
		$tar [] = "<\\1\\2>";
		$src [] = "/<(A|IMG)[^>]*(HREF|SRC)[^=]*=[ '\"\n]*($regex[http]|mailto:$regex[mail])[^>]*>/i";
		$tar [] = "<\\1 \\2=\"\\3\">";
	
		// replaceed @ charactor include email form in URL
		$src [] = "/(http|https|ftp|telnet|news|mms):\/\/([^ \n@]+)@/i";
		$tar [] = "\\1://\\2_HTTPAT_\\3";
	
		// replaced special char and delete target
		// and protected link when use html link code
		$src [] = "/&(quot|gt|lt)/i";
		$tar [] = "!\\1";
		$src [] = "/<a([^>]*)href=[\"' ]*($regex[http])[\"']*[^>]*>/i";
		$tar [] = "<A\\1HREF=\"\\3_orig://\\4\" TARGET=\"_blank\">";
		$src [] = "/href=[\"' ]*mailto:($regex[mail])[\"']*>/i";
		$tar [] = "HREF=\"mailto:\\2#-#\\3\">";
		$src [] = "/<([^>]*)(background|codebase|src)[ \n]*=[\n\"' ]*($regex[http])[\"']*/i";
		$tar [] = "<\\1\\2=\"\\4_orig://\\5\"";
	
		// auto linked url and email address that unlinked
		$src [] = "/((SRC|HREF|BASE|GROUND)[ ]*=[ ]*|[^=]|^)($regex[http])/i";
		$tar [] = "\\1<A HREF=\"\\3\" TARGET=\"_blank\">\\3</a>";
		$src [] = "/($regex[mail])/i";
		$tar [] = "<A HREF=\"mailto:\\1\">\\1</a>";
		$src [] = "/<A HREF=[^>]+>(<A HREF=[^>]+>)/i";
		$tar [] = "\\1";
		$src [] = "/<\/A><\/A>/i";
		$tar [] = "</A>";
	
		// restored code that replaced for protection
		$src [] = "/!(quot|gt|lt)/i";
		$tar [] = "&\\1";
		$src [] = "/(http|https|ftp|telnet|news|mms)_orig/i";
		$tar [] = "\\1";
		$src [] = "'#-#'";
		$tar [] = "@";
		$src [] = "/$regex[file]/i";
		$tar [] = "\\1";
	
		// restored @ charactor include Email form in URL
		$src [] = "/_HTTPAT_/";
		$tar [] = "@";
	
		// put border value 0 in IMG tag
		$src [] = "/<(IMG SRC=\"[^\"]+\")>/i";
		$tar [] = "<\\1 BORDER=0>";
	
		// If not MSIE, disable embed tag
		if (! strstr ( $_SERVER ['HTTP_USER_AGENT'], "MSIE" )) {
			$src [] = "/<embed/i";
			$tar [] = "&lt;embed";
		}
	
		$str = preg_replace ( $src, $tar, $str );
		return $str;
	} 
	
	
	 
	
	public function saveHTML($str) {
		$approvedtags = array (
				"p" => 2, // 2 means accept all qualifiers: <foo bar>
				"b" => 1, // 1 means accept the tag only: <foo>
				"i" => 1,
				"a" => 2,
				"em" => 1,
				"sub" => 1,
				"sup" => 1,
				"br" => 1,
				"strong" => 1,
				"blockquote" => 1,
				"tt" => 1,
				"hr" => 1,
				"li" => 1,
				"ol" => 1,
				"ul" => 1,
				"img" => 2,
				"span" => 2
		);
	
		$keys = array_keys ( $approvedtags );
	
		// Remove head, script, and comment tags.
		// $str = preg_replace( "/(?im)<head .*>.*<\/head *>/", "", $str );
		$str = preg_replace ( "/<head *>.*<\/head *>/", "", $str );
		$str = preg_replace ( "/<script .*>.*<\/script *>/", "", $str );
		$str = preg_replace ( "/<\?.*\?>/", "", $str );
		$str = preg_replace ( "/<!--.*-->/", "", $str );
		$str = preg_replace ( "/<[[:space:]]*([^>]*)[[:space:]]*>/", "<\\1>", $str );
		$str = preg_replace ( "/<a([^>]*)href=\"?([^\"]*)\"?([^>]*)>/", "<a href=\"\\2\">", $str );
		$str = preg_replace ( '/(?Uis)\<a *href="javascript:.*\<\/a>/', '', $str );
	
		$tmp = "";
	
		while ( preg_match ( "/<([^> ]*)([^>]*)>/", $str, $reg ) ) {
			$i = strpos ( $str, $reg [0] );
			$l = strlen ( $reg [0] );
			if ($reg [1] [0] == "/")
				$tag = strtolower ( substr ( $reg [1], 1 ) );
			else
				$tag = strtolower ( $reg [1] );
				
			if (in_array ( $tag, $keys ) && $a = $approvedtags [$tag]) {
				if ($reg [1] [0] == "/")
					$tag = "</$tag>";
				elseif ($a == 1)
				$tag = "<$tag>";
				else
					$tag = "<$tag " . $reg [2] . ">";
			} else {
				$tag = "";
			}
			$tmp .= substr ( $str, 0, $i ) . $tag;
			$str = substr ( $str, $i + $l );
		}
		$str = $tmp . $str;
	
		return $str;
	}
	
	
	/*
	 * 
	 * Compress Text
	 */	
	public   function  compress ($buffer)
	{
		$search = array(
				'/\>[^\S ]+/s', //strip whitespaces after tags, except space
				'/[^\S ]+\</s', //strip whitespaces before tags, except space
				'/(\s)+/s'  // shorten multiple whitespace sequences
		);
		$replace = array(
				'>',
				'<',
				'\\1'
		);
		$buffer = preg_replace($search, $replace, $buffer);
		return $buffer;
	}
	
	
	
	
	
	
	
	
}
