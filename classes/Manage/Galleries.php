<?php
namespace Manage;

class Galleries {

	private $static;

	public function __construct($dirs=[]){
		$this->folders = [];
		foreach ($dirs as $value) {
			if(is_dir($value)){
				$this->folders[] = $value; 
			}
		}
	}

	public function open_video($type){
		$args = [];
		foreach ($this->folders as $value) {
			if($path = opendir($value)){
				while( ($base = readdir($path)) !== false){
					preg_match("/\.+/",$base,$dots);
					$subroot = "$value/$base";
					if(is_dir($subroot) && !$dots){	
						$file = [];
						if($subpath = opendir($subroot)){
							while( ($subfile = readdir($subpath)) !== false){
								preg_match($type,$subfile,$match);
								if($match && is_file("$subroot/$subfile")){
									$paths = pathinfo(preg_replace("/\\\\/","/","$subroot/$subfile"));
									$file[] = $paths['basename'];
								}
							}
						}
						$path1 = explode("/",preg_replace("/\\\\/","/",$subroot));
						$path2 = $path1[count($path1)-1];
						unset($path1[count($path1)-1]);
						$args[$path2]['base'] = implode('/',$path1);					
						$args[$path2]['info'] = array_merge($this->get_info("$subroot/info.txt"),['cover' => null]);				
						$args[$path2]['file'] = $file;				
					}
				}
			}
		}
		return $args;
	}

	public function get_info($info_txt,$prms=[]){
		$args=[];
		$subs=[];
		$prms=$prms ? $prms : [
			'director',
			'language',
			'category',
			'synopsis',
			'summary',
			'writers',
			'release',
			'origin',
			'studio',
			'album',
			'stars',
			'genre',
			'tags',
		];
		if(file_exists($info_txt)){
			$file = file_get_contents($info_txt);			
			foreach(explode("\n",preg_replace(['/\n+/','/\s+/'],['\n','\s'],$file)) as $n => $val){
				$data = explode(':',$val);
				$subs[$data[0]] = $data[1];
			}			
		}

		foreach ($prms as $key) {
			if(isset($subs[$key])){
				$args[$key] = $subs[$key];
			}else{
				$args[$key] = null;
			}
		}

		return $args ? $args : $prms;
	}

	public function open_files($type){
		$args = [];
		foreach ($this->folders as $value) {
			foreach(new \RecursiveDirectoryIterator($value) as $file){
				if($file->isFile()){
					$open = preg_replace("/\\\\/","/",$file->getFilename());
					preg_match($type,$open,$match);
					if($match){
						$args[] = pathinfo($file->getRealPath());
					}
				}
			}
		}
		return $args;
	}

	public function get_artist($url_site=null){
		$args=[];
		if($url_site){
			$site = \__fn::get_site($url_site);
			$site = explode('/table>/',$site);
			//$site = \__fn::dom_site($site);
			//var_dump($site->find('div',1)->find('table'));
			var_dump($site);
		}

		return $args;
	}

	public function check_file($regs=null){
		$open = [];
		if($regs){
			// default another expression files;
			$exps = $regs; 			
			if($regs == 'auds'){
				$exps = '/\.(m(4a|ka|p3)|wav|oog|aac)/';
			}
			if($regs == 'vids'){
				$exps = '/\.(m(3u|4v|kv|p(4|e?g?))|wmv|avi|3gp|ts)/';
			}
			if($regs == 'pics'){
				$exps = '/\.(jp(f|x|m|e?g?2?)|(t|g)if(f)?|ico|(pn|sv)g)/';
			}
			if($exps && $this->folders){
				if($regs == 'vids'){
					$open = $this->open_video($exps);
				}else{
					$open = $this->open_files($exps);
				}
			}
		}
		return $open;
	}
}