<?php
include('autobase.php');
$cp_user = "rizaus";
$cp_pwd = "@kodak31ca";
$uri = "https://riza.us";
$url = "$uri:2083/login";
$cookies = "assets/cookies.stats.txt";

function curl_opt_set($url,$cookies,$user){
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies); // Save cookies to
    curl_setopt($ch, CURLOPT_POSTFIELDS, $user);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100020);
    $f = curl_exec($ch);
    $h = curl_getinfo($ch);
    curl_close($ch);
    return [$f,$h];
}

$get = curl_opt_set($url,$cookies,"user=$cp_user&pass=$cp_pwd");
$f = $get[0];
$h = $get[1];


if ($f == true and strpos($h['url'],"cpsess"))
{
  // Get the cpsess part of the url
 $pattern="/.*?(\/cpsess.*?)\/.*?/is";
 $preg_res=preg_match($pattern,$h['url'],$cpsess);
}

$token  = $cpsess[1];
$to_url = 'biyanha.resptk.net';

$get = curl_opt_set("$uri:2083$token/awstats.pl",$cookies,"config=$to_url&ssl=&lang=en&user=$cp_user&pass=$cp_pwd");
//$data = \__fn::get_site("$uri:2083/$token/awstats.pl?config=$to_url&ssl=&lang=en");
file_put_contents("assets/cookies.stats.html",$get[1]);
