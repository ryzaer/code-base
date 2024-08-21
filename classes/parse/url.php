<?php
namespace parse;
/*
 * URL CLEAN CLASS
 * Source	: https://github.com/ryzaer
 * Author	: Riza TTNT
 */ 

class url {

	private $param;
	public $keys,$rkey,$depth = 0;

	function __construct ($void=null){
		// set configuration
		foreach ([
			'APP_URL_AUTH' => '_a_',
			'APP_URL_META' => '_m_',
			'APP_URL_PAGE' => '_p_',
			'APP_UID_INDEX' => 'home',
			'APP_DATA_URL' => substr(urldecode($_SERVER['REQUEST_URI']),1),
		] as $key => $val) {
			if(!defined($key))
				// define constants default keys if none
				define($key,$val);
		}
		
		$this->set_param($void)->rkey = [
			APP_URL_AUTH => false,
			APP_URL_PAGE => true,
			APP_URL_META => true,
		];
	}

	function add_keys($keys){
		$this->keys = $this->keys? $this->keys.','.$keys : $keys;
	}
 
	function add_rkey($keys){
		$rslt = [];
		if(is_array($keys)){
			foreach($keys as $key => $val){
				if($val == true || $val == false){
					$rslt[$key] = $val;
				}
			}
			$rslt = is_array($this->rkey) ? array_replace($this->rkey,$rslt) : $rslt;
		}
		if(is_string($keys)){
			foreach($this->filter_url_params($keys) as $key){
				$rslt[$key] = false;
			}
		}
		$this->rkey = $rslt	;
	}

	function filter_url_params($str){
		/* this filter only for keys & will return array */
		return preg_split('~,~',preg_replace(['/[][}{)(;:\/\"\'&%$]/','/,+/'],',',$str),-1,PREG_SPLIT_NO_EMPTY);
	}

	function filter_remove_keys($args){
		if(is_string($this->rkey)){
			$this->add_rkey($this->rkey);
		}
		
		$keyremove = $this->rkey;
		$datas = [];
		if($keyremove){
			$rmv_key = [];
			foreach ($keyremove as $urlkey => $value) {
				if(isset($args[$urlkey])){
					if($value){
						$datas[$args[$urlkey]] = "";
					}
					$rmv_key[$urlkey] = $args[$urlkey];
					unset($args[$urlkey]);
				}
			}
		}
		
		$args = $datas ? array_replace($datas, $args) : $args ;	
		
		$main = null;
		$fill = [];
		$nums = 0;
		foreach($args as $key => $dta){
			if($nums > 0){
				$fill[$key] = $dta; 
			}else{
				$main = $key ;
			}
			$nums++;
		}

		return [$main, $fill];
	}

	function isnan_ampersand($str){
		
		$param = explode('/',$str);

		/* set page/ main key */
		$comb  = [];
		$comb[$param[0]] = "";

		unset($param[0]);
		$param = array_map('ltrim', array_values($param));
		/* filter data url clean */
		
		$keys  = $this->keys;
		if($keys){
			foreach($this->filter_url_params($keys) as $num => $str){
				$comb[$str] = isset($param[$num])? $param[$num] : "";
			}
		}else{
			$key=[];$val=[];
			foreach ($param as $num => $str) {
				if($num % 2 == 0){
					$key[] = $str;
				}else{
					$val[] = $str; 
				}
			}			
			
			foreach ($key as $k => $v) {
				$comb[$v] = isset($val[$k])? $val[$k] :"";
			}

		}

		return $this->filter_remove_keys($comb);
	}

	function str_secure($arr){
		$args = '/<\/?script[^>]*>|eval\(.*\)|(\)|\'|"|‘|′|`)(\s+)?(admin|id|or|OR|=)(\s+)?(\(|\'|"|‘|′|`)|(-|"|\')(\s+)?(-|"|\')/i';
		if(is_array($arr)){
			$rslt = [];
			foreach($arr as $s => $tr){
				if(is_array($tr)){
					$rslt[$s] = $this->str_secure($tr);
				}else{
					$rslt[$s] = preg_replace($args,"",$tr);
				}			 
			}
		}else{
			$rslt = preg_replace($args,"",$arr);
		}		
		return $rslt;
	}
	function set_param($this_param=null){
		$this->param = $this_param && is_string($this_param) ? $this_param : APP_DATA_URL;
		$this->param = preg_replace(['/\?+/','/&+/','/=+/','/\.(php|htm(l)?|(a|j)sp)/'],['?','&','=',''],$this->str_secure($this->param));
		$this->depth = count(explode('/',$this->param)) - 1;	
		return $this;	
	}
	function obj($Array) {     
		$object = (object)[];
		foreach ($Array as $key => $value) {
			if (is_array($value)) {
				$value = $this->obj($value);
			}
			$object->$key = $value;
		}
		return $object;
	}
	function get(...$arrparam){
		$skey=null;
		$obj =false;
		for($i=0;$i<2;$i++) {
			if(isset($arrparam[$i]) && is_bool($arrparam[$i])){
				$obj=$arrparam[$i];
			}
			if(isset($arrparam[$i]) && is_string($arrparam[$i])){
				$skey=$arrparam[$i];
			}
		}
		
		$result   = [];
		if($skey=='secure_post'){
			$result = $this->str_secure($_POST);
		}else{				
			$equal = preg_match('/[?&]/i',$this->param);
			if(!$equal){	
				$param = $this->isnan_ampersand($this->param);											
			}else{
				$comb  = [];
				foreach (preg_split("/[?&]/i",$this->param) as $over => $lay) {
					$break = explode("=",$lay);
					$comb[$break[0]] = isset($break[1])? $break[1] : "";  
				} 
				$param = $this->filter_remove_keys($comb);
			}
			// separating url depth of main	
			$mainkey = explode('/',$param[0]);			
			// if clean mode, subs will be empty
			$main = !$mainkey[0] || $mainkey[0] == 'index' ? APP_UID_INDEX : $mainkey[0] ;
			$subs = [];
			foreach ($mainkey as $key => $value) {
				if($key > 0)
				$subs[]=$value;
			}
			$sums = count($subs);			
			$name = $sums > 0 ? $subs[$sums-1] : $main ; 
			unset($subs[$sums-1]);  		
			$subs = count($subs) > 0 ? $subs : [] ;
			
			$result['main'] = $main;
			$result['subs'] = $subs;
			$result['name'] = $name;
			$dataprm = [] ;
			foreach ($param[1] as $ks => $kv) {
				if($ks !=='')
					$dataprm[$ks] = $kv ;
			}
			$result['data'] = $dataprm;
			
			$result = isset($result[$skey])? $result[$skey] : $result;

			if($obj){
				$result = $this->obj($result);
			}			
			// underproject
			if($skey=='request'){
				$result = $r_post + $param[1];
			}
		}	
		return $result;
	}	
}