<?php
namespace Tools\formatter;

// yaml still underproject

class yaml {

	private $yml,$cmd,$sub,$info=[];
	private static $stat;

	private function parse($val)
	{
		$text = $val;
		preg_match('/(\n+|\r+|-)/is',$val,$mtch);
		if($mtch){
			$text = '"'.preg_replace('/\"/is',"\\\"",trim($val)).'"';
		}else{
			if(is_bool($val)){
				$text = $val ?  'true' : 'false';
			}
			if(is_null($val)){
				$text = 'null';
			}
			if(is_numeric($val)){
				$formatter = new \NumberFormatter('en_US', \NumberFormatter::DECIMAL);
				$text = $formatter->parse($val);
			}
		}
		return $text;
	}
	
	static function title(...$str){
		$dtext=[];
		foreach ($str as $val) {
			if(is_array($val)){
				$dtext[]=\Tools\formatter\table::Ascii($val);
			}
			if(is_string($val)){
				$dtext[]=$val;
			}
		}
		$str = implode("\n",$dtext);
		self::$stat = self::$stat ? self::$stat : new yaml();
		self::$stat->info = [];
		
		foreach(explode("\n",$str) as $dcmt){
			self::$stat->info[] = "# $dcmt";
		}
		if(self::$stat->info)
			self::$stat->info = implode("\n",self::$stat->info)."\n";
	}

	function info($str){
		if(is_array($str)){
			$str = \Tools\formatter\table::Ascii($str);
		}
		$on = count($this->yml);
		for ($i=0; $i < $on; $i++) { 
			if($i == $on-1){
				$this->cmd[] = $str;
			}else{
				$this->cmd[] = "";
			}
		}
	}
	
	static function data(...$arg)
	{
		$key = !is_callable($arg[0]) && !is_numeric($arg[0]) && is_string($arg[0]) ? $arg[0] : "";
		$val = $key ? ( isset($arg[1]) ? $arg[1] : "" ) : $arg[0] ;
		
		self::$stat = self::$stat ? self::$stat : new yaml();
		self::$stat->adnum = 0;
		if(is_callable($val)){
			self::$stat->add($key,$val);
			$val = "";
		}else{
			self::$stat->yml[] = [0,$key,$val];
		}
	}
	function add(...$arg)
	{
		$key = !is_callable($arg[0]) && !is_numeric($arg[0]) && is_string($arg[0]) ? $arg[0] : "";
		$val = $key ? ( isset($arg[1]) ? $arg[1] : "" ) : $arg[0] ;
		$num = $this->adnum+1;
		if(is_callable($val)){
			$this->yml[] = [$num-1,$key,''];
			$this->adnum = $num;
			call_user_func($val,$this);	
		}else{
			$this->yml[] = [$num-1,$key,$val];
			$this->adnum = $num-1;
		}	
		$this->adnum = $num-1;
	} 

	function rstl($variable){
		$rst=[];
		foreach ($variable as  $value) {
			$rst[] = str_repeat(" ",$value[0])."$value[1]:";
		}
		return implode("",$rst);
	}
	static function emit()
	{
		$result = [];
		if(self::$stat){
			foreach (self::$stat->yml as $key => $value) {
				if($value){
					
					$vals = isset($value[2]) ? trim(self::$stat->parse($value[2])) : null;
					$vals = $vals ? " $vals" : null;
					if($value[1]){						
						$array = isset(self::$stat->array) && self::$stat->array ? str_repeat(" ",$value[0]-2)."- " : str_repeat(" ",$value[0]);
						$result[] = "$array$value[1] :$vals";
						self::$stat->array = 0;
					}else{
						self::$stat->array = 1;	
					}
					if(isset(self::$stat->cmd[$key]) && self::$stat->cmd[$key]){
						foreach(explode("\n",self::$stat->cmd[$key]) as $dcmt){
							$result[] = str_repeat(" ",$value[0])."# $dcmt";
						}
					}
				}
			}
		}
		$info = self::$stat->info ? self::$stat->info : null ;
		self::$stat = null;
		var_dump($result);
		return "$info---\n".implode("\n",$result)."\n...";
	}
}