<?php 
namespace db;
/*
 * PDO CLASS CRUD for Mysql [singleton]
 * Source	: https://github.com/ryzaer/php-crud-mysql-singleton
 * Author	: Riza TTNT
 * Desc		: PHP PDO Mysql CRUD Object with Costum LONGBLOB
 * Date Created : 15th, Oct 2016
 * Last Updated : 21st, Feb 2023
 * License 	: MIT
 * 
 * 
 * Costumize your recomanded extension file above (LONGBLOB)
 * upload file to db depend your SQl allow packet setting OR PHP memory limit size 
 */ 

final class mysql extends \PDO {

	private	$stmt,$base;
	private static $conn,$class;	
	public	$format = "tmp|mp4|webm|mp3|ogg|aac|zip|png|gif|jpeg|jpg|bmp|svg|pdf";

	/*
	 * Login pattern  ------> 
	 * connect pattern $login = ['user','password','your_database_name','localhost','port','engine'];
	 * and input to your db engine class  $db = Manage\Data\mysql::open(...$login);
	 * 
	 * very start use ------>	
	 * $db = Manage\Data\mysql::open(DB_USER,DB_PASSWORD,DB_NAME,DB_HOST,DB_PORT,DB_DRIVER);    
	 * 
	 * or simple connect to all your db
	 * 
	 * $db = Manage\Data\mysql::open('root','123');
	 */ 

	public function __construct(...$Engine)
	{	
		$Engine = $this->parse(...$Engine);
		if($Engine->dbu && $Engine->dbp){
			// set param databases
			$this->base = (object)[];								
			$this->base->auths = $this->hash($Engine);								
			$this->base->prime = $Engine->dbn;								
			$this->base->build = $Engine->dbn;	
			$this->base->table = null;								
			try{	
				parent::__construct("{$Engine->type}:host={$Engine->dbh};port={$Engine->port}",$Engine->dbu,$Engine->dbp);	
			}
			catch(\PDOException $e){	
				$msg = $e->getCode();
				$msg = isset($Engine->error[$msg])? $Engine->error[$msg] : $Engine->error['unknown'] ;
				die($msg);
			}
		}
		
	}

	public static function open(...$engine){		
		if(self::$conn && self::$conn->base->auths !== self::$conn->hash($engine)){
			self::$conn = null;
			self::$conn = new mysql(...$engine);
		}else if(!self::$conn){
			self::$conn = new mysql(...$engine);
		}
		return self::$conn;
	}

	public static function close(){
		if(self::$conn){
			self::$conn = null;
		}
		return self::$conn;
	}
	// short command
	public function name($db){return $this->database($db);}
	public function tb($db){return $this->table($db);}
	
	public function database($db)
	{
		$dbname = null;
		if(!is_numeric($db) && is_string($db) && $db){
			$dbname = trim($db);
		}		
		if(!$this->base->prime){
			$this->base->prime = $dbname;
		}
		if(is_bool($db) && $db == false){
			$this->base->build = $this->base->prime;
		}else{
			$this->base->build = !$this->base->build ? $dbname : $this->base->prime ; 
		}		
		return $this;
	}
	public function table($tb)
	{
		if(!is_numeric($tb) && is_string($tb) && $tb){
			$this->base->table = trim($tb);
		}
		return $this;
	}

	private function hash($hash){
		$cript = '';
		if(is_array($hash)){
			$hash = $this->parse(...$hash);
		}
		if(is_object($hash) && isset($hash->dbu)){
			$cript=[];
			$stop =false;
			unset($hash->error);
			foreach ($hash as $key => $value) {
				if(is_string($value)){
					$cript[]=$value;
				}
			};
			$cript = implode(';',$cript);
		}	
		return md5($cript);
	}
	
	private function parse(...$pattern) {
		$init = isset($pattern[0])? $pattern[0] : null;
		$info = true;
		if($init == 'sql'){
			$info = false;
			$arrs = isset($pattern[1]) && is_array($pattern[1])? $pattern[1] : [];
			$sprs = isset($pattern[2]) && is_string($pattern[2])? $pattern[2] : "AND";
			if(!empty($arrs)){
				$str=[];
				foreach ($arrs as $key => $value) {
					$str[] = "$key='".str_replace("'","\'",$value)."'";
				}
				$sprs = implode(" $sprs ", $str);
			}
			return $sprs;
		}
		if($init == 'table'){
			$mode = isset($pattern[1]) && is_bool($pattern[1])? $pattern[1] : true;
			if($mode){
				$this->base->build = $this->base->build ? $this->base->build : $this->base->prime;
			}else{
				$this->base->build = null;
			}
			if(!$this->base->table){
				$this->base->build = null;
			}		
			return $this->base->build ? "{$this->base->build}.{$this->base->table}" : null;
		}
		// default mysql info connect
		if($info){
			return (object)[
				'dbu' 	=> isset($pattern[0])? $pattern[0] : null,
				'dbp' 	=> isset($pattern[1])? $pattern[1] : null,
				'dbn' 	=> isset($pattern[2])? $pattern[2] : null,
				'dbh' 	=> isset($pattern[3])? $pattern[3] : 'localhost',
				'port' 	=> isset($pattern[4])? $pattern[4] : 3306, 
				'type' 	=> isset($pattern[5])? $pattern[5] : 'mysql',
				'error' => [
					"unknown"	=> "<i style='color:red'>Unknown Error!</i>",
					"0"			=> "<i style='color:red'>Unknown Driver! (Check Your login pattern again)</i>",
					"1045" 		=> "<i style='color:red'>Access Denied! (Check Your login pattern again)</i>",
					"1049" 		=> "<i style='color:red'>Unknown Table! (Seems database not found)</i>",
					"2002" 		=> "<i style='color:red'>Not Connect!</i>",
					"23000"	 	=> "<i style='color:red'>Duplicate keys</i>",
					"23001" 	=> "<i style='color:red'>Some other error</i>",
					"42000" 	=> "<i style='color:red'>Syntax error or access violation</i>",
					"08007" 	=> "<i style='color:red'>Connection failure during transaction</i>"
				]
			];
		}
		
	}

	/*
	 * ////////////////////////////////////////////////////////////////////////////////////////////////////
	 * Using insert function "insert" in multi array "not a multi dimension of array" ;
	 * Or you may encode an [array("multi dimension of array")] values into json code, see an example test3 ;
	 * ////////////////////////////////////////////////////////////////////////////////////////////////////
	 * foreach($post1 as $rand => $values){		
	 * >>  $data_post[] = array("test1" => $post1[$rand], "test2" => $post2[$rand], "test3" => json_encode(array($post3[$rand])));		
	 * }
	 * OR in single array;
	 * >>  $data_post = array("test1" => $post1 , "test2" => $post2, "test3" => $post3);
	 * >>  ----- then execution using ----------->>>
	 * >>  $db->insert($data_post);
	 * >>  
	 * >>  //////////////////// insert with BLOB /////////////////////////////////////////
	 * >>  $data_post = array("name" => "file1" , "size" => 1232212, "galeri" => "/foo.jpg");
	 * >>  $db->insert($data_post, true);
	 * >>  ----- 1st true to active BLOB  ------->>> 
	 * >>  /////////////////////////////////////////////////////////////////////////////////////
	 */ 
	
	public function insert($rows=array(), $BLOB=false)
	{
		$table  = $this->parse('table');
		$result = null;
		if($table && $rows){
			$command 	= "INSERT INTO $table";
			$arr_mode 	= false;
			
			foreach ($rows as $keys)
			{ 
				if(is_array($keys)){
					$arr_mode = true;
					$parameter[] = '(' . implode(',', array_fill(0, count($keys), '?')) . ')';
					foreach($keys as $element){ $obj_rows[] = trim($element); }
				}		
			}			
			
			foreach ((($arr_mode)? $keys : $rows ) as $key => $value)
			{
				$sub_rows[] = $key;
				(!$arr_mode)? ( $parameter[] = ":$key" ) 	: null ;
				(!$arr_mode)? ( $obj_rows[]  = trim($value))	: null ;
			}
			
			$command .= " (".implode(',', $sub_rows).") VALUES ";
			$params	  = implode(',',$parameter);
			$command .= ($arr_mode)? $params : "($params)" ;
			$except   = implode('|', explode('|', trim($this->format)));
			
			$this->stmt = parent::prepare($command);		
			
			for($i=0;$i < count($obj_rows);$i++){
				$sum[$i] 	= ($arr_mode)? $i+1 : ":{$sub_rows[$i]}" ;
				$pdo_sum[$i] 	= is_numeric($obj_rows[$i])? \PDO::PARAM_INT : \PDO::PARAM_STR ;
				$var[$i] 	= $BLOB? ((preg_match('/^.*\.('.$except.')$/i',strtolower($obj_rows[$i]))>0)?  file_get_contents($obj_rows[$i]) : $obj_rows[$i] ) : $obj_rows[$i] ;	
				$pdo_var[$i] 	= $BLOB? ((preg_match('/^.*\.('.$except.')$/i',strtolower($obj_rows[$i]))>0)?  \PDO::PARAM_LOB : $pdo_sum[$i] ) : $pdo_sum[$i] ;	
				$this->stmt->bindParam($sum[$i],$var[$i],$pdo_var[$i]);			
			}	

			$this->stmt->execute();		
			$result = $this->lastInsertId();
		}
		return $result;
	}
	
	/*
	 * Using delete function "delete"  ;
	 * >>  $db->delete(array("id" => "data_id"));
	 */	
  
	public function delete($where=array())
	{
		$table  = $this->parse('table');
		$result = null;
		if($table){
			$command = "DELETE FROM $table";		
			$list = array(); $param = array();
			foreach ($where as $key => $value)
			{
				$list[]	 = "$key=:$key";
				$param[] = "\":$key\":\"$value\"";
			}
			$command	.= ' WHERE '.implode(' AND ', $list);
			$param 		 = json_decode('{'.implode(",",$param).'}',true);
			$this->stmt	 = parent::prepare($command);

			$result = $this->stmt->execute($param);
		}
		return $result;
	}

	/*
 	 * Using update function "update" ;
 	 * >>  ////////////////////simple update/////////////////////////////////
 	 * >>  $data_post 	= ["str1"=>"7172878939","str2"=>"pic_of_arini","str3"=>"arini.jpg"];
 	 * >>  $id  		= ["id" => 1,"id_tool" => 5];
 	 * >>  $db->update($data_post, $id);
 	 * >>  
 	 * >>  ////////////////////complete update////////////////////////////////
 	 * >>  ----- by adding 'or' string ($or)------------>>>
 	 * >>  $or  		= ["ssd" => "more fastest", "hdd" => "lil bit fast"];
 	 * >>  $db->update($data_post, $id, true, true, $or));
 	 * >>  ----- 1st true to active BLOB  ------->>>
 	 * >>  ----- 2nd true to active LIKE  ------->>>
 	 * >>  ----- both false as default  --------->>> 
 	 * >>  ----- output >> UPDATE table SET str1=:str1,str2=:str2,str3=:str3 WHERE id LIKE CONCAT('%', :id, '%') AND id_tool LIKE CONCAT('%', :id_tool, '%') AND ( ssd LIKE CONCAT('%', :ssd, '%') OR hdd LIKE CONCAT('%', :hdd, '%') )
 	 * >>  ////////////////////////////////////////////////////////////////////////////
 	 */	

	public function update($sets=array(), $where=array(), $BLOB=false, $LIKE=false, $OR=array())
	{
		$table  = $this->parse('table');
		$result = null;
		if($table){
		
			$update	 = "UPDATE $table SET ";
			$optdata = [];
			foreach($sets as $key => $values)
			{			
				$rdata[]  = ":$key";
				$vdata[]  = $values;			
				$sdata[]  = "$key=:$key"; 
			}
			
			if(!empty($where)){
				foreach($where as $key => $values)
				{
					$rdata[]  = ":$key";
					$vdata[]  = $values;			
					$udata[]  = ($LIKE)? "$key LIKE CONCAT('%', :$key, '%')" : "$key=:$key";
				}
				$optdata[] = implode(" AND ",$udata);
			}		
				
			if(!empty($OR)){
				foreach($OR as $key => $values){
					$rdata[]  = ":$key";
					$vdata[]  = $values;
					$odata[]  = ($LIKE)? "$key LIKE CONCAT('%', :$key, '%')" : "$key=:$key"; 
				}
				$optdata[] = "( ".implode(" OR ",$odata)." )";
			}
			
			$update .= implode(',',$sdata);		
			$update .= ' WHERE '.implode(" AND ", $optdata);
			$except  = implode('|', explode('|', trim($this->format)));
			
			$this->stmt  = parent::prepare($update);
			for($i=0; $i < count($rdata); $i++){		
				$pdo_sum[$i] = is_numeric($vdata[$i])? \PDO::PARAM_INT : \PDO::PARAM_STR ;
				$var[$i] 	 = $BLOB? ((preg_match('/^.*\.('.$except.')$/i',strtolower($vdata[$i]))>0)?  file_get_contents($vdata[$i]) : $vdata[$i] ) : $vdata[$i] ;	
				$pdo_var[$i] = $BLOB? ((preg_match('/^.*\.('.$except.')$/i',strtolower($vdata[$i]))>0)?  \PDO::PARAM_LOB : $pdo_sum[$i] ) : $pdo_sum[$i] ;		
				$this->stmt->bindParam($rdata[$i], $var[$i],$pdo_var[$i]);	
			}			
			$result = $this->stmt->execute();
		}	
		return $result;
	}	

	/*
 	 * This is simple function "select" (can be customize) ;
 	 * 
 	 * >>  $show = $db->select("column1='id_or_data_to_show'", null, 3); // 3 is limit data to show;
 	 * >>  if(!$show){
 	 * >>       echo 'Not Found!';
 	 * >>  }else{
 	 * >>     foreach ($result as $data){  
 	 * >>       echo $data['row'];
 	 * >>     }
 	 * >>  }
 	 * >> another custom pattern for grouping
 	 * >> $db->select("range_date BETWEEN '2018-07-20' AND '2018-08-20' GROUP BY range_date", "range_date DESC", null, "range_date, count(*) as vals");
 	 */
	public function select($where=null, $order=null, $limit=null, $rows=null)
	{	
		$table  = $this->parse('table');
		$rows =	($rows)?  "$rows" : "*";
		$result = array();
		if($table){			
			$command  =	"SELECT $rows FROM $table";
			if(is_array($where)){
				$args = [];
				foreach ($where as $key => $val) {
					$args[] = "$key='$val'";
				}
				$command .= " WHERE ".implode(' AND ', $args);
			}else{
				$command .=	($where)? " WHERE $where" 		: null ;
			}

			$command .= ($order)? " ORDER BY $order " 	: null ; 
			$command .= ($limit)? " LIMIT $limit" 		: null ;	
			$this->stmt = parent::prepare($command);
			$this->stmt->execute();			
			while($check = $this->stmt->fetch(\PDO::FETCH_ASSOC)){
				$result[] = $check;
			}
		}
		return $result;
	}
	public function exec($command){
		return $this->sql($command);
	}
	public function sql($command)
	{
		$this->stmt = parent::prepare($command);
		$this->stmt->execute();	
		return $this;
	}
	public function count(){
		return $this->stmt->fetchColumn();
	}
	public function fetch(){
		$result = [];
		while($check = $this->stmt->fetch(\PDO::FETCH_ASSOC)){
			$result[] = $check;
		}
		return $result;
	}

	/*
 	 * Using create table  function "create" (Default engine MyISAM & Charset utf8) ;
 	 * >>  	$myrow = array( 
 	 * >>  	  "ID" 		=> "INT(11) AUTO_INCREMENT PRIMARY KEY", 
 	 * >>     "Prename"  	=> "VARCHAR(50) NOT NULL", 
 	 * >>     "Name"	=> "VARCHAR(250) NOT NULL",
 	 * >>     "Postcode" 	=> "VARCHAR(50) NOT NULL",
 	 * >>     "Country" 	=> "VARCHAR(50) NOT NULL" );
 	 * >>  	$db->create("tb_foo", $myrow, "InnoDB", "latin1");
 	 */		

	public function create($item_rows=array(), $engine="MyISAM", $charset="utf8")
	{
		$table = $this->parse('table');
		$exist = array();
		if($table){
			$check = parent::query("SHOW TABLES LIKE $table");
			$exist = $check->fetchAll(\PDO::FETCH_COLUMN);
			if($item_rows && empty($exist)){
				$command  = "CREATE TABLE IF NOT EXISTS `$table` (";
				foreach($item_rows as $x => $y){
					$items[]	 = "`$x` $y";
				}
				$command .= implode(",", $items);
				$command .= ") ENGINE=$engine DEFAULT CHARSET=$charset;";
				parent::exec($command);
			}
		}
		return $exist;
	}

	/*
	 * get totaldata
	 */
	// public function count($where=null)
	// {
	// 	$table  = $this->parse('table');
	// 	$result = array();
	// 	if($table){
	// 		$command  = "SELECT count(*) FROM `$table`"; 
	// 		$command .=	($where)? " WHERE $where" : null ;
	// 		$this->stmt = parent::prepare($command);
	// 		$this->stmt->execute();
	// 		$result = $this->stmt->fetchColumn(); 
	// 	}
	// 	return $result; 
	// }
	
	private function push($where=null, $query=[])
	{	$result = array();
		if(!empty($query) && $this->insert($query)){
			$result = $this->select($where);	
		}
		return $result;
	}

	
}