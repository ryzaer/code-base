<?php

class jurnal {

	private static $static;
	private $db,$rslt;
	public function __construct(){
		$this->rslt = [];
		$folder = preg_replace(['/(\\\\|\/)classes.*/','/\\\\/'],['','/'],__DIR__);
		$phpset = implode("\n",[
			"define('DB_USER','');",
			"define('DB_PASS','');",
			"define('DB_NAME','');",
			"define('DIR_COVER','$folder/assets/images');",
			"define('DIR_THUMB','$folder/assets/images/thumbnails');",
		]);
		$this->config = "$folder/config.php";
		if(!file_exists($this->config)){
			file_put_contents($this->config,"<?php\n$phpset");
		}
		return $this;
	}
	private static function this_class(){
		if(!self::$static){
			self::$static = new jurnal();
		}
		return self::$static;
	}
	public function grab_url($url){
		$rslt = \__fn::get_site($url,200); // curl data html for code 200 only
		if($rslt){
			foreach (['script','noscript','style','head'] as $tag) {
				$rslt = preg_replace("/<$tag(.*?)>(.*?)<\/$tag>/is",'',$rslt);
			}
			$rslt = preg_replace(['/<!.*-->/',"/\n+/is"],['',"\n"],$rslt);
		}
		return $rslt;
	}

	public function cleanTitle($str)
	{
		$str = preg_replace(['/[^0-9a-zA-Z]/','/\s+/s'],' ',strtolower($str));
		return preg_replace('/\s/is','-',trim($str));
	}

	public function titleUID($str){
		$id = substr(md5($str),rand(1,26),6);
		if($this->db->table('tb_jurnal')->select("title_uid='$id'")){
			return $this->strUID($str);
		}else{
			return $id;
		}
	}
	public function db(...$arg){
		$usr = isset($arg[0]) && is_string($arg[0]) && $arg[0] ? $arg[0] : DB_USER ;
		$pwd = isset($arg[1]) && is_string($arg[1]) && $arg[1] ? $arg[1] : DB_PASS ;
		$dbn = isset($arg[2]) && is_string($arg[2]) && $arg[2] ? $arg[2] : DB_NAME ;
		$this->db = \Manage\data\mysql::open($usr,$pwd,$dbn);
		return $this->db;
	}
	public static function get(...$args){
		//open class and include constant
		$self = self::this_class();
		require_once $self->config;	
		
		$init = isset($args[0]) && is_string($args[0]) && $args[0] ? array_map('ltrim',preg_split('/\:/',$args[0],-1,PREG_SPLIT_NO_EMPTY)) : [];
		// parameter tag tribunews
		$prm1 = isset($init[0]) && $init[0] ? $init[0] : null ;
		// parameter tag link search
		$prm2 = isset($init[1]) && $init[1] ? $init[1] : null ;
		// maximum dom links
		$nums = isset($args[1]) && is_numeric($args[1])? $args[1] : 5 ;
		$nums = $nums == 0 ? true : $nums;
		$conf = false;
		if($prm1 == 'tribun_tags' && $prm2 && DB_USER && DB_PASS && DB_NAME && DIR_COVER && DIR_THUMB){
			$conf = true;
			foreach ([DIR_COVER,DIR_THUMB] as $dir) {
				file_exists($dir) or mkdir($dir,0755,true); 
			}
			if(!function_exists('curl_tribun_article')){
				function curl_tribun_article($self,$link){
					$result = [];
					$html = $self->grab_url("{$link}?page=all");
					if($html){
						// remove all here
						foreach ([
							'/<p class="baca">(.*?)<\/p>/',
							'/<span class="more">(.*?)<\/span>/'
						] as $regex) {
							$html = preg_replace($regex,'',$html);
						}
						// header('Content-Type:text/plain');
						// echo $html;die();
						$html = \__fn::dom_site($html);

						$text = [];	
						$nums = 0 ;
						foreach ($html->find('p') as $value) {
							$page = trim(strip_tags($value->innertext));
							if($nums == 0){
								$page = preg_replace('/TRIBUNPONTIANAK.CO.ID, PONTIANAK/', '<strong>PONTIANAK</strong>',$page);
							}
							$text[] =  "<p>$page</p>";
							$nums++;
						}

						$text = implode('',$text);
						
						$tags = [];
						foreach ($html->find('a[class=rd2]') as $value) {
							$tags[] =  trim(strip_tags($value->innertext));
						}

						$tags = $tags ? implode(';',$tags) : '';

						$result = [
							'html' => $text,
							'tags' => $tags
						];
					}

					return $result;
				}
			}

			$link = 'https://pontianak.tribunnews.com/';
			$html = $self->grab_url("{$link}tag/{$prm2}?page=1");
			$db = $self->db();
			if($html){				
				$html = \__fn::dom_site($html);					
				foreach($html->find('ul[class=lsi]') as $e => $value){
					$rslt['title_uid'] = '';	
					$rslt['title_url'] = '';	
					$rslt['title_post'] = '';	
					$rslt['cover'] = '';
					$rslt['snippet'] = '';
					$rslt['article'] = '';
					$rslt['author'] = 'humas';
					$rslt['status'] = 2;
					$rslt['date']   = '';
					$rslt['tags']   = '';
					$rslt['cats']   = 1;	
					$num = 0;
					$tmd = 1;
					foreach ($value->find('li') as $v) {
						$allow_all = false;
						if($num < $nums){
							$allow_all = true;
						}
						if(is_bool($nums)){
							$allow_all = $nums;
						}
						if($allow_all){
							$check_title = $self->cleanTitle($v->find('a',1)->innertext);
							if(!$db->table('tb_jurnal')->select("title_url='$check_title'")){
								//echo $v->find('a',1)->href."<br>";
								$rslt['title_uid']  = $self->titleUID($check_title);
								$rslt['title_url']  = $check_title;
								$rslt['title_post'] = trim($v->find('a',1)->innertext);
								$rslt['snippet'] = ucfirst(trim(preg_replace('/\.+/','',$v->find('h4',0)->innertext)));

								// date modify
								$datetime = new \DateTime(substr($v->find('a',1)->href,strlen($link),10)." 08:00:00");
								$datetime->modify("+{$tmd}23{$tmd} seconds");
								$rslt['date']   = $datetime->format("Y-m-d H:i:s");	

								// dom per post						
								$grab = curl_tribun_article($self,$v->find('a',1)->href);
								$rslt['article'] = $grab['html'];
								$rslt['tags']    = $grab['tags'];
								$rslt['cover']   = preg_replace('/thumbnails2/','images',$v->find('img',0)->src);
								
								// join in result
								$self->rslt[] = $rslt;
								$num++;$tmd++;
							}
						}
					}
				}
			}
		}
		if($conf){
			return $self->rslt ;
		}else{
			http_response_code(403);
			die('<b style="color:red"><i>check your configuration!</i><b>');
		}
	}
}