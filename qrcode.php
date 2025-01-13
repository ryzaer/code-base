<?php
require_once('autobase.php');
$icdir = 'assets/icons';
$icons = [
	'silent'    => 'silent.png',
	'kresna'    => '106.png',
	'srena'     => 'asren.png',
	'dokkes'    => '115.png',
	'polri'     => 'polri.png',
	'mabes'     => '0.png',
	'kalbar'    => '12.png',
	'sdm'       => '104.png',
	'bareskrim' => '105.png',
	'sabhara'   => '108.png',
	'binmas'    => '109.png',
	'humas'     => '112.png',
    'lantas'    => '107.png',
    'play' 		=> 'playstore.pn',
    'apps'  	=> 'appstore.p'
];

// var_dump($_GET);die();

$md1 = isset($_GET['logo']) && isset($icons[$_GET['logo']])? "$icdir/{$icons[$_GET['logo']]}" : "$icdir/local.png";
$md2 = isset($_GET['text'])? urldecode($_GET['text']) : 'polresta pontianak kota';
//quality level, size, border, bg color, fore color
$md3  = isset($_GET['size'])? explode(",",$_GET['size']) : [];
$size = count($md3)==3 ? $md3 : ['medium',17,0];


$test = new \QRcode\generate($md2,$size[0]);
$test->logo($md1,3.5,2.75);
//$test->logo($md1);
//$test->size($size[1],$size[2]);
$test->size($size[1],$size[2],0x121212);
//$test->save('test.png');
$test->load();