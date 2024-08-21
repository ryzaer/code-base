<?php
namespace Tools;

class formatter {

	private static $stat;
	private $key,$cntr,$tree,$text,$info=[],$result=[];

	public function __construct($args=[]){
		// start here ..
	}

	function push($key, $arg){
		$this->arg_push = $arg;
		if(is_array($this->arg_push)){
			if(isset($this->arg_push[0])){
				$arg = function($fn){
					$fn->data($fn->arg_push);
				};
			}else{
				
				$arg = function($fn){
					foreach($fn->arg_push as $key => $val){
						$fn->data($key,$val);
					}
				};
			}

			$this->data($key, $arg);
		}
	}

	private function parse($val)
	{
		$text = null;
		if($val !== '__()' || $val !== '__fn()'){
			$text = $val;
			preg_match('/(\n+|\r+)/is',$val,$mtch);
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
		}
		return $text;
	}

	private function nl2br($str,$sign=null)
	{
		$text = $str;
		$rslt = [];
		preg_match('/\n/is',$str,$mth);
		if($mth){			
			foreach (explode("\n",$str) as $value) {
				$rslt[] = "$sign$value";
			}
			$text=implode("\n",$rslt);
		}
		return $rslt ? $text : "$sign$text";
	}
	private function prettier($arrs,$sign){
		$key = array_keys($arrs);
		$max = [];
		$spf = [];
		foreach ($key as $cntr) {
			$spf[]="$cntr%s";
			$max[]=strlen($cntr);
		}
		$val = array_values($arrs);
		$dmax = max($max);
		$dump = [];
        for ($i=0; $i < count($val) ; $i++) { 
            $dump[] = sprintf($spf[$i],str_repeat(' ',($dmax - $max[$i]))).$sign.' '.$this->parse($val);
        }
		return $dump;
	}
	function data(...$arg)
	{
		$key = $arg[0] ? $arg[0] : "";
		$val = isset($arg[1]) ? $arg[1] : $key ;

		$tree = $this->tree ? $this->tree : 0 ;
		$uid = is_callable($key) ? '__fn()' : $key;
		$var = is_callable($val) ? '__fn()' : $val;
		$fstdata = isset($this->__fst_data)? $this->__fst_data : 0;
		// if callable key
		if($uid == '__fn()'){			
			$this->result[] = [$tree,$uid,""];
			$this->cntr++;
			if(is_callable($val)){
				$this->tree = $tree+1 ;
				call_user_func($val,$this);	
			}
		}else{
			if(!is_array($key) && $uid !== $var){
				if($this->key !== $key){
					$this->key = $key;	
					$this->result[] = [$tree,$uid,$var];
					$this->cntr++;
					if(is_callable($val)){
						$this->tree = $tree+1 ;
						call_user_func($val,$this);
					}
				}

			}else{
				foreach ($key as $k => $v) {
					if(is_numeric($k) && !is_array($v) && !is_callable($v))
						$this->result[] = [$tree,"$v",'__()',$fstdata];
						$this->cntr++;
				}
			}
			
		}
		if(isset($this->__fst_data)){unset($this->__fst_data);}

		$this->tree = $tree;
	}

	function emit()
	{
		$output = null;
		if($this->text == 'yaml'){
			$parser = [];
			$schema = [];
			foreach ($this->result as $key => $value) {
				if($value[1] == '__fn()'){
					$this->setArray = 1;
					$this->minus = 1;
				}else{
					$sArr = null;
					
					if(isset($this->minus) || $value[2] == '__()'){
						$value[0] = abs($value[0]-1);
						$value[0] = $value[0] > 1 ? $value[0] - 1 : $value[0];
					}

					if(isset($this->setArray)){						
						$sArr = $this->setArray > 0 ? "- " : "  " ;
					}
					
					if($value[2] == '__()'){
						// array set on first data
						if(isset($value[3])){
							$value[0] = $value[3] == 1 ? 0 : $value[0];
						}	
						$sArr = "- " ;
					}

					// $value[0] = isset($this->setArray) ? $value[0]+1 : $value[0];
					$rptr = str_repeat(" ",$value[0]);

					if(isset($this->info[$key])){
						$schema[$value[0]][] = 0;
						$addSpc = isset($this->setArray) ? "  " : null;
						$parser[][$value[0]][] = "$addSpc$rptr{$this->info[$key]}";
					}		
						
					$vars = "$rptr$sArr{$value[1]}";	
					$vals = isset($value[2]) ? $this->parse($value[2]) : null;	
					$schema[$value[0]][] = $value[2] == '__()' ? 0 : strlen($vars);											
					$parser[][$value[0]][] = [$vars,$vals];
					
					if(isset($this->setArray)){						
						$this->setArray = 0;
					}					
				}
				
			}

			// $output = array_filter($this->result);
			//$output = implode("\n",$result);
			
			$pretty = [];
			foreach ($parser as $key => $value) {				
				$parse_pretty = [];
				foreach ($value as $k => $v) {
					if(is_array($v[0])){
						$dmax = max($schema[$k]);
						$klen = strlen($v[0][0]);
						$dmin = abs($dmax - $klen);
						$dmin = $dmin > 0 ?  $dmin - $k :  $dmin ;
						$akey = sprintf("{$v[0][0]}%s",str_repeat(" ",$dmin)); 
						// $akey = is_string($v[0][0]) ? $slen : $v[0][0];
						$aval = $v[0][1] == '__fn()' ? ":" : ": {$v[0][1]}" ;
						$aval = $v[0][1] == '__()' ? "" : $aval ;
						$parse_pretty = "$akey$aval" ;
					}else{
						$parse_pretty = $v[0];
					}
				}
				$pretty[] = $parse_pretty;
			}
			$output = implode("\n",$pretty)."\n...";
		}
		if(self::$stat){
			self::$stat = null;
		}
		return $output;
	}

	function info($str){
		if(is_array($str)){
			$str = \Tools\formatter\table::commentAscii($str);
		}else{
			$str = "# $str";
		}
		$this->info[$this->cntr-1] = $str;
	}
	
	static function yaml(...$args){
		self::$stat = self::$stat ? self::$stat : new formatter();
		self::$stat->text = 'yaml';
		self::$stat->cntr = 0;
		self::$stat->__fst_data = true;
		$str = !is_numeric($args[0]) && is_string($args[0]) ? $args[0] : null; 
		$var = isset($args[1]) && is_callable($args[1]) ? $args[1] : $str;
		if($str){
			self::$stat->cntr = 1;
			self::$stat->info[] = self::$stat->nl2br($str,"# ")."\n---";
		}else{
			self::$stat->info[] = "---";
		}
		if($var){
			call_user_func($var,self::$stat);
		}

		return self::$stat;
	}
}