<?php
namespace Crypto;

class sodium {
	private static $stmt;
	private $nonce,$key,$base,$uniq,$salt,$encrypt,$decrypt;
	function __construct($salt=null,$base64=false){
		
		$salt = $salt ? $salt : uniqid();	
		$this->base = $base64;
		$this->salt = $salt;

		if(is_array($salt)){
			$this->key   = $salt['key'];
			$this->nonce = $salt['nonce'];
			$this->salt  = null;
		}
	
		if(is_numeric($salt) || is_string($salt)){
			// hashing algo haval195,5 byte data
			$hash = base64_encode(hash('haval192,5',$salt));  
			$uid  = [
				str_split(hash('adler32',substr($hash,56)),2),
				str_split(hash('crc32b',substr($hash,56)),2)
			];      
			$this->key   = substr($hash,0,32);
			$this->nonce = substr($hash,32,24);			
			$this->uniq  = [
				'crc4' => "{$uid[1][3]}{$uid[0][0]}",
				'crc6' => "{$uid[1][2]}{$uid[0][1]}{$uid[0][2]}",
				'crc8' => "{$uid[0][3]}{$uid[1][0]}{$uid[1][3]}{$uid[0][1]}",
			];

		}
	}
	static function encrypt($data,$salt=null,$base64=false) {
		if(!self::$stmt)
			self::$stmt = new self($salt,$base64);
				
		$enc = sodium_crypto_secretbox($data,self::$stmt->nonce,self::$stmt->key);
		self::$stmt->encrypt = self::$stmt->base ? base64_encode($enc) : $enc;
		return self::$stmt->encrypt;	}
	static function decrypt($data,$salt=null,$base64=false) {
		if(!self::$stmt)
			self::$stmt = new self($salt,$base64);
				
		$data = self::$stmt->base ? base64_decode($data) : $data;
		self::$stmt->decrypt = sodium_crypto_secretbox_open($data,self::$stmt->nonce,self::$stmt->key);
		return self::$stmt->decrypt;
	}
	static function encode($get=true){
		$output = [];
		if(self::$stmt){
			if(!$get)
				$output['uid'] = self::$stmt->uniq;
			$output['key'] = self::$stmt->key;
			if(!$get)
				$output['salt'] = self::$stmt->salt;
			$output['nonce'] = self::$stmt->nonce;
		}
		return isset($output[$get]) ? $output[$get] : $output;
	}
	static function close($get=true){
		if(self::$stmt)
			self::$stmt = null;
		return null;
	}

}