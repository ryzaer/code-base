<?php 

class inClassInclude {

	private static $static;
	private $add;

	public function __construct(){
		// start here ..a
		date_default_timezone_set("asia/jakarta");
		$this->add = (object)[];
	}

	public function head(){
		echo "header<br>";
	}

	public static function element(){
		if(!self::$static){
			self::$static = new inClassInclude();
		}
		return self::$static;
	}

	public function addElement($string){
		echo $string;
	}

	public function renderElement($function){
		
		$elem = (object)[];
		$elem->page = null;
		$elem->data = [];
		$elem->add = (object)[];
		$elem->add->page = false;
		$elem->add->html = null;

		if(is_callable($function)){
			call_user_func($function, $elem);
		}

		$part_html = null;

		if($elem->add->html){
			$part_html .= $elem->add->html;
		}
		
		$this->add->html = null;
		
		$footer_page = false;
		if($elem->add->page){
			$this->head();
			$footer_page = true;
		}

		unset($elem);

		if($footer_page){
			include "files/elem.php" ;
		}

		if($this->add->html){
			$part_html .= $this->add->html;
		}

		if($part_html){
			$this->addElement($part_html);
		}

		if($footer_page){
			$this->foot();
		}


	}

	public function foot(){
		echo "footer";
	}

}