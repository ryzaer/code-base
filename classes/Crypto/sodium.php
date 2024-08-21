<?php
namespace Crypto;

class sodium {
	private $nonce,$key,$base,$uniq;
	function __construct($salt=false,$base64=false){
		$salt = $salt?$salt:uniqid();
		$this->base  = $base64;
	
		if(is_array($salt)){
			$this->key   = $salt['key'];
			$this->nonce = $salt['nonce'];
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
	function encrypt($data) {
		if(!$this->nonce && !$this->key){
			return false;
		}
		$enc = sodium_crypto_secretbox($data,$this->nonce,$this->key);
		return $this->base ? base64_encode($enc) : $enc;
	}
	function decrypt($data) {
		if(!$this->nonce && !$this->key){
			return false;
		}
		$data = $this->base ? base64_decode($data) : $data;
		return sodium_crypto_secretbox_open($data,$this->nonce,$this->key);
	}
	function encode(){
		if(!$this->nonce && !$this->key){
			return [];
		}
		return [
			'uid' => $this->uniq,
			'key' => $this->key,
			'nonce' => $this->nonce
		];
	}
}