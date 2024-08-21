<?php
require_once 'autobase.php';

function generatePass() {
    if(!function_exists('randPassword')){
        function randPassword($alphabet,$num)
        {
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < $num; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            return $pass;
        }
    }
    $pass[]=implode(randPassword('[{*!~_#|-@+?$=}]',10));
    $pass[]=implode(randPassword('abcdefghijklMNOPQRSTUVWXYZ',5));
    $pass[]=implode(randPassword('ABCDEFGHIJKLmnopqrstuvwxyz',5));
    $pass[]=implode(randPassword('1234567890',5));
    $pass = randPassword(implode($pass),8);
    return implode($pass); //turn the array into a string
}

$auth = parse\uuid::v2(); // random generate id with math
$salt = generatePass();
echo "Version 2 (hexdec) : auto generate id $auth<br>";
$rand = parse\uuid::v4($auth,$salt);
echo "Version 4 (algo md4) static  : authentication id $auth, from salt word : $salt, output : $rand<br>";
$algo = "haval128,5";
// $salt = "*BB3605-SKCK";
echo "Version 5 (algo md5) default : generate id from salt word : $salt, with algo : $algo, output : ".parse\uuid::v5($salt,$algo)."<br>";
echo 'Version 6 : generate 6 unique characters ('.parse\uuid::v6().'), or adding combinations '.parse\uuid::v6('*','-SKCK').'<br>';