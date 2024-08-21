<?php
namespace Manage;

class SecureVideo {

	private $dirs,$file;

	public function source($path,$string){
		$this->dirs =[];
		$this->file =[];
		foreach (explode(',',$string) as $val ) {
			if(is_dir("$path/$val")){
				$this->dirs[] = $val;
			}

			if(file_exists("$path/$val.bin")){
				$this->file[] = $val;
			}
		}
	}

	public function create(){

		var_dump($this->dirs);
		var_dump($this->file);

	}
}