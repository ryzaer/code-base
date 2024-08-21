<?php
namespace Indo;

class wilayah {

	private $db;

	public function __construct(...$conn){
		if(isset($conn[0]) && is_a($conn[0],'Manage\Data\mysql')){
			$this->db = $conn[0];
		}
		return $this;
	}
	public static function propinsi($code){}
	public static function kabupaten($code){}
	public static function kecamatan($code){}
	public static function kelurahan($code){}
	public function get(...$args){
		// p = province (default)
		// r = regency (kab/kota)
		// d = district (kec)
		// s = sector (kel/desa)
		$item = isset($args[0])? $args[0] : 'p';
		$code = isset($args[1])? $args[1] : null;
		if($this->db){
			// api key is next time project
			if($item == 'p'){
				return $this->province($code);
			}
			if($item == 'r'){
				// need for code province
				return $this->regency($code);
			}
			if($item == 'd'){
				// need for code regency
				return $this->district($code);
			}
			if($item == 's'){
				// need for code regency
				return $this->sector($code);
			}
		}else{
			return null;
		}
	}


}