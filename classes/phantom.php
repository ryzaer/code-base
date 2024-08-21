<?php

class phantom {

	private static $stmt;

	public function __construct($args=null){
		$this->stdjs = is_string($args) ? $args : <<<JS
// phantomjs binary must installed
// official : https://phantomjs.org/download.html
// community : https://bitbucket.org/ariya/phantomjs/downloads/ latest 
var page = require('webpage').create();    
	page.viewportSize  = {width: 962, height: 1200};
	page.customHeaders = {
		'User-Agent' : 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:42.0) Gecko/20100101 Firefox/42.0',
		'Accept': '*/*',
		'Accept-Language': 'nb-NO,nb;q=0.9,no-NO;q=0.8,no;q=0.6,nn-NO;q=0.5,nn;q=0.4,en-US;q=0.3,en;q=0.1',
		'Connection': 'keep-alive'
	};
	page.open('https://polrestapontianak.org/home/cover',function(){
		page.render('cover.png');phantom.exit();
	});
JS;
	}

	public static function curl($url){
		if(self::$stmt){
			self::$stmt = $std = new phantom();
		} 
		

	}
}