<?php require_once "../autobase.php";

$time = microtime(true);
$text = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum, aliquid consequuntur quisquam minus in rerum consequatur sed reprehenderit. Est, fugit cupiditate officiis error natus consequuntur obcaecati perspiciatis veniam deserunt nihil!';
// $text = 'rizaus_admin|Vi@ri322@|rizaus_resta';
$ctnt = strlen($text);
$salt = 'This-is-my-secret';
print "<br>SALT KEY : $salt<br><br>";
print implode("<br>",[
"=================================================================================>",
"==== OPEN SSL NEW CUSTOM ENCRYPTION RESTA EXAMPLE (BASE64 VALUE) (algo:tiger160,3) =====>",
"=================================================================================>",
]);
$hash = __fn::open_method('en_tiger',$text,$salt);
$ctns = strlen($hash);
print "<br>Encrypted Text : $hash ($ctns cahrs)<br>Decrypted Text : ".__fn::open_method('de_tiger',$hash,$salt)." ($ctnt chars)<br><br>";

print implode("<br>",[
"=================================================================>",
"==== OPEN SSL ENCRYPTION EXAMPLE (HASH VALUE) (algo:haval160,3) =======>",
"=================================================================>"
]);
$hash = __fn::open_method('eh_haval2',$text,$salt);
$ctns = strlen($hash);
print "<br>Encrypted Text : $hash ($ctns cahrs)<br>Decrypted Text : ".__fn::open_method('dh_haval2',$hash,$salt)." ($ctnt chars)<br><br>";

print implode("<br>",[
"=============================================================================>",
"==== OPEN SSL ENCRYPTION EXAMPLE (BASE64 VALUE) (default algo : ripemd160) ========>",
"=============================================================================>"
]);

$hash = __fn::open_method('encrypt',$text,$salt);
$ctns = strlen($hash);
print "<br>Encrypted Text : $hash ($ctns cahrs)<br>Decrypted Text : ".__fn::open_method('decrypt',$hash,$salt)." ($ctnt chars)";

$time = number_format((microtime(true) - $time),5);
print "<br><i>execution time : $time</i>";