<?php
namespace Crypto;

class ssl {

	private static $stmt;

	static function encrypt($string, $crypt=6621) {
		self::$stmt = self::$stmt ? self::$stmt : new ssl();
		return self::$stmt->open_method('encrypt',$string, $crypt);
	}
	static function decrypt($string, $crypt=6621) {
		self::$stmt = self::$stmt ? self::$stmt : new ssl();
		return self::$stmt->open_method('decrypt',$string, $crypt);
	}
	private function open_method($action, $string, $crypt) {
		if(defined('APP_CRYPT')){
			$crypt = !APP_CRYPT || is_array(APP_CRYPT) || is_bool(APP_CRYPT)? $crypt : APP_CRYPT;
		}
		if(!function_exists('open_crypto_parse')){
			function open_crypto_parse($act, $string, $method, $key, $iv){
				$str = false;
				if($act == 'encrypt'){
					$str  = base64_encode(openssl_encrypt($string, $method, $key, 0, $iv));
					$pars = substr($str, 0, -2);
					$args = substr($str, strlen($pars));
					foreach(['0=','==','09'] as $char){
						if($args == $char){
							$str = $pars;
						}
					}									
				}        
				if($act == 'decrypt'){
					$str = openssl_decrypt(base64_decode($string), $method, $key, 0, $iv);
				}        
				return $str;
			}
		}					
		return open_crypto_parse($action,$string,"AES-256-CBC",hash('sha256', substr($crypt,0,ceil(strlen($crypt)/2))),substr(hash('sha256', substr($crypt,ceil(strlen($crypt)/2))), 0, 16));
	}
}