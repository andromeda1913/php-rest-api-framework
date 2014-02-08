<?php
namespace Helpers;

/*
 * Memcache  Cache   
 * Simple Memcache Wrapepr  
 * 
 */

class Cached{

	public static function   isAviable(){
			if (class_exists('Memcache',false) || class_exists('memcache',false)) {
		  		$memcache = new \Memcache;
				$isMemcacheAvailable = @$memcache->connect("127.0.0.1",11211); 
				if ($isMemcacheAvailable) 
						return true; 
			}			
			return false;  
		}
	
		// Set Cache Var
		public static function set($name , $value  , $minutes =  MEMCACHE_DEFAULT_LIMIT){
			if(self::isAviable()){
				$memcache = new \Memcache;
				$memcache->connect("127.0.0.1",11211);
				$memcache->set($name, $value, MEMCACHE_COMPRESSED, $minutes);
				}				
		}
	
		// Get From Cache  
		public static function get($key){
			if(self::isAviable()){
				$memcache = new \Memcache;
			 	$memcache->connect("127.0.0.1",11211);
				return $memcache->get($key);
			}			
			return false ; 
		}
	
}