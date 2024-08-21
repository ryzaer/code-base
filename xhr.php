<?php
/*manambahkan fungsi getallheader di PHP-FPM server nginx*/
// for send header
define('ALLOW_HEADER','x-request-with');
define('ALLOW_ORIGIN','*');
define('ALLOW_METHOD','GET,POST');//GET, POST, PUT, DELETE, OPTIONS

header_remove('ALLOW_ORIGIN');
header("Access-Control-Allow-Origin: ".ALLOW_ORIGIN);
header('Access-Control-Allow-Headers: '.ALLOW_HEADER);
header('Access-Control-Allow-Methods: '.ALLOW_METHOD);
//header('Content-Type: application/json');

(isset($_GET['test']) or $_GET['test'] == 'ajax') or die(); 
if (!function_exists('getallheaders')) {
    function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
var_dump(getallheaders());
if(!isset($_COOKIE["TestCookie"])){
    setcookie("TestCookie", "ini_test_coockie_".time(), time()+60);
}
//setcookie("TestCookie", null, -1, '/'); //unset cookie