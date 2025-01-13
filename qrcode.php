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

$md1 = isset($_GET['logo']) && isset($icons[$_GET['logo']])? "$icdir/{$icons[$_GET['logo']]}" : "$icdir/restaptk.png";
$md2 = isset($_GET['text'])? urldecode($_GET['text']) : 'https://polrestapontianak.org/skck/registrasi';
//quality level, size, border, bg color, fore color
$md3  = isset($_GET['size'])? explode(",",$_GET['size']) : [];
$size = count($md3)==3 ? $md3 : ['medium',17,1];


$qr = new \QRcode\generate($md2,$size[0]);
$qr->logo($md1,4,2.75);
//$qr->logo($md1);

// example streaming png file
// $qr->size($size[1],$size[2],0x121212); // black
//$qr->size($size[1],$size[2]);
// $qr->size($size[1],$size[2],0x022178); // blue

// example export file
$qr->save('assets/qrcode.png');
header('Content-type: text/html');
$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="assets/icons/restaptk.png" type="image/png">
	<title>QR Code | $md2</title>
</head>
<body>
	<style>
		body { margin: 0; background-color: #000; color: #fff; }
	</style>
	<center><img width="500" src="assets/qrcode.png" /><h4>$md2</h4></center>
</body>
</html>
HTML;
print $html;

// generate qr
$qr->load();

