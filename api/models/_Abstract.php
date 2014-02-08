<?php
namespace models ; 

/*
 * Abstract Model    
 * pashkovdenis@gmail.com  
 * 2014    
 *  
 * 
 */

abstract class _Abstract { 
	
	protected $table;
	private $last_id;
	private $count;
	private $data;
	private $connection;
	private $sql;
	private $ordering;
	private $limit;
	private $where = array ();
	 
	
	// connect to database ;
	public function __construct() { 
		
		$this->data = array ();
		$this->table = false;
		$this->last_id = 0;
		$this->count = 0;
		$this->ordering = "";
		$this->limit = "";
	
		try{

			$this->connection = new \PDO("mysql:host=".HOST.";dbname=".DATABASE, USER, PASS);  
 			if (DEVELOP== true){
 				$this->connection->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
 			}
		} 
 		catch(\PDOException $e) {
			echo $e->getMessage();
		}
		
		
		}
	
	// set Limiter
	public function setLimiter($l = '') {
		$this->limit = $l;
		return $this;
	}
	public function setTable($table) {
		$this->table = $table;
		return $this;
	}
	
	public function delete($id){
		$this->setSql("DELETE FROM ##  WHERE id='{$id}' ")->exec(); 
		if($this->selectBy("id", $id)) 
				return false; 		
		return true; 
	}
	// 
	public function selectAll(){
		return $this->setSql("SELECT * FROM ##")->loadList() ; 
	}
	// set Ordering
	public function setOrdering($r = "") {
		$this->ordering = $r;
		return $this;
	}
	// set Raw Sql ;
	public function setSql($sql) {
		if (is_string ( $sql ))
			$this->sql = $sql;
		   $this->sql= str_replace("##",   $this->table,  $this->sql) ; 
		return $this;
	}
	public function getSql() {
		return $this->sql;
	}
	// Set Where Extensions
	public function where($field, $value) {
		if (! strstr ( $field, "LIKE" ))
			$field .= "=";
		$this->where [] = $field . "'" . $value . "'";
		return $this;
	}
	
	// this metho will update By post
	public function updateBypost($post = []) {
		$str = "";
		$c = 0;
		 
			
		
		foreach ( $post as $k => $v ) {
			if ($c > 0)
				$str .= ", ";
			if ($k == "start" || $k == "end")
				$v = strtotime ( $v ); 
			else{ 
			
			if (strstr($k , "start") || strstr($k , "_end")  ||  strstr($k , "end_"))
				$v = strtotime ( $v ); 
			} 
	 
				
				if($k=="enable"){
					if($v!='1') 
							$v=0 ; 
				}
			
			$str .= " `{$k}`='{$v}' ";
			
			$c ++;
		}
		
		$this->setSql ( "UPDATE ## SET " . $str . " WHERE id='{$post["id"]}' " )->exec ();
	}
	 
	
	public function time_elapsed_string($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
        }
    }
}

	
	
	/*
	 * Insert Values By Arary :   
	 * pashkovdenis@gmail.com  
	 * 2014  
	 *  
	 */ 
	
	
	
	public function generalInsert($data = []) {
		$str = " INSERT INTO ## SET  ";
		$c = 0;
		foreach ( $data as $key => $v ) {
			if ($c > 0)
				$str .= ", ";
			
			
			if ($key == "start" || $key == "end")
				$v = strtotime ( $v );
			if (strstr($key , "start") || strstr($key , "_end") ||  strstr($key , "end_"))
				$v = strtotime ( $v );
			
			
			
			$str .= " `$key` = '" . mysql_real_escape_string ( $v ) . "' ";
			$c ++;
		}
		$this->setSql ( $str )->exec (); 
		return  $this->connection->lastInsertId() ;
	}
	
   
	/*
	 * Execute Methods  .   
	 * 
	 * 
	 * 
	 */
	
	public function exec() {
		if ($this->sql == "")
			throw new \Exception ( "Empty Request " );
		
		if (count ( $this->where ))
			$this->sql .= " WHERE " . join ( " AND ", $this->where );
		$this->sql = str_replace ( "##", $this->table, $this->sql );
		$this->sql .= " " . $this->ordering . " " . $this->limit;
		
		// execute   mysql query  
		$state =   $this->connection->prepare($this->sql) ;  
		$res =  $state =  $state->execute();  
	 
		if (! $res)
			throw new \Exception ( "Mysql Error " .  $this->connection->errorInfo() . $this->sql );
		
		$this->sql = "";
		$this->where = array ();
		$this->limit = "";
		$this->ordering = '';
		
		return $res;
	}
	
	
	
	
	 /*
	  * Select By  
	  * 
	  */
	
	public function selectBy($field, $v, $select=[]) {
 		$values =  "";    
 		if (count($select)>0)  
 			$values   =  "SELECT ".join(",", $select) ; 
 		else 
 			$values  = "SELECT * " ;  
 		$this->sql =  $values .  " FROM {$this->table} WHERE {$field}='{$v}'  " ;  
 		$STH =  $this->connection->query($this->sql) ; 
 		$STH->setFetchMode(\PDO::FETCH_OBJ);
 		return $STH->fetch() ;
 	}
	 

 	
	// selectByAll 
	public function selectByAll($field, $v) {
		return $this->setSql ( "SELECT * FROM {$this->table} WHERE " . $field . "='" . $v . "' " )->loadList ();
	}
 
	
	
	// Load Single Row
	public function load() {
			$STH =  $this->connection->query($this->sql) ; 
 		    $STH->setFetchMode(\PDO::FETCH_OBJ);
 			 return $STH->fetch() ;
	}
	 
	
	/*
	 * Fetch All REsults    
	 * 
	 */
	
	public function loadList() {
		$result  = [] ;   
		$STH = $this->connection->query($this->sql);
		$STH->setFetchMode(\PDO::FETCH_OBJ);
		while($row = $STH->fetch())  
			$result[] =   $row ;  
		return $result  ;  
	}
	
	
	/* _________________  
	 * 
	 * Generate New Token 
	 * _________________ 
	 */  
	   	
	
	public function createToken($data_users = [] ){ 
		
		$hash = md5 ( rand ( 0, 887009 ) . $_SERVER ["REMOTE_ADDR"] . session_id () );
		$data = [
		"key" => $hash,
		"date" => time ()
		];  
		$data = array_merge($data ,  $data_users) ;  
		file_put_contents ( BASE_DIR . "/tmp/" . $hash, serialize ( $data ) );  
		
		return $hash ; 
		
		
	}
	 
	
	/* __________________  
	 * Get Data From Token   
	 * _________________ 
	 */
	  
	public function  getFromToken($name ,  $var){
		$data=   file_get_contents(BASE_DIR, "/tmp/".$name) ; 
		$array = unserialize($data);   
		if (is_array($array) && isset($array[$var]))  
			return  $array[$var];   
		else 
			return false;  	
	}
	
	 
	
	
	
	
	
	
}