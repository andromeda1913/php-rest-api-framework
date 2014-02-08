<?php 
 session_start() ; 
/*________________________
 * Api config service File   
 * Database Connections    
 * Paths 
 * pashkovdenis@gmail.com 
 * 2014 
 * ______________________
 */ 

  
 define ( "HOST", "localhost" );
 define ( "USER", "root" );
 define ( "PASS", "123123" );
 define ( "DATABASE", "rest" ); 
 define ( "BASE_DIR", __DIR__ );  
 define ( "START_PATH", 3);      //  Used for parsing url   
 define ( "DEVELOP",true);   
 define("SITE_NAME","API FLP PROJECT "); 
 define("VERSION" , "1.0") ; 
 
 define("MEMCACHE_DEFAULT_LIMIT",  80) ;  //  Memcahe limit 
 define("FORMAT","JSON"); //  Default Format of  output  For FLP  :  
 define("DEFAULT_PAGE_LIMIT", 25);   // 
 define("DEFAULT_TOKEN_LIMIT", 100); //  minutes ;   
  
 
 
 // Mail settings :    
 define ( "SMTP_HOST", "smtp.gmail.com" );
 define ( "SMTP_USER", "" );
 define ( "SMTP_PASS", "" );
 define ( "NOTIFY_EMAIL", "" );
 // End   Settings :
 // End   Settings :  
 
 
 
include_once 'Bootstrap.php';