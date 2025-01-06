<?php
require_once 'autobase.php';

if(isset($_GET['open']) && $_GET['open']){
    // example link "code-base/sodium.php?open=677b7b1eb0afa.bin"
    $image = file_get_contents("assets/sodium/{$_GET['open']}");
    $enkey = preg_replace("/\.(jpg|bin)/","",$_GET['open']);
    // header('Content-Disposition: form-data; name="fieldName"; filename="filename.jpg"');
    $deimg = Crypto\sodium::decrypt($image,$enkey);
    if(!$deimg){
        $deimg = "<i><b style=\"color:red\">Wrong data crypted image!</b></i>";
    }else{
        header("Content-Type:image/jpg");
        print $deimg;
        $deimg = null;
    }    
    die($deimg);
}

$folder = "assets/sodium";

print implode("<br>",[
"<b>=================================================================>",
"======== SODIUM CLASS (Singleton) EXAMPLE : Data to Encrypt ===========>",
"=================================================================></b>"
])."<br>";
$plaintext = '{"biodata":"20230703123216","nik":"6171044409040007","nama":"RESTI RAHMADEVI","alias":"Resti","gelar":"[\"\",\"\"]","nama_ayah":"JULIDESMAN","tpt_lahir":"Pontianak","tgl_lahir":"2004-09-04","gender":"2","agama":"1","kerjaan":"4","alamat":"{\"ktp\":[\"Jl. Khatulistiwa Gg.Purnajaya II No.168-A\",\"2\",\"4\",\"61\",\"6171\",\"6171040002\"],\"skrg\":[\"Jl. Khatulistiwa Gg. Purnajaya II No.168-A\",\"2\",\"4\",\"61\",\"6171\",\"6171040002\"]}","pendidikan":"{\"terakhir\":\"5\",\"riwayat\":\"[[\\\"3\\\",\\\"Sd N 06 Pontianak Utara\\\",\\\"2016\\\"],[\\\"4\\\",\\\"Smp N 15 Pontianak Utara\\\",\\\"2019\\\"],[\\\"5\\\",\\\"Sma N 5 Pontianak Utara\\\",\\\"2022\\\"]]\"}","telp":"0895370009657","sinyal":"[\"150\",\"40\",\"1\",\"2\",\"1\",\"1\",\"1\",\"1\",\"2\",\"3\",\"1\",\"1\",\"5\",\"1\",\"1\",\"1\",\"-\",\"-\"]","data_ortu":"{\"ayah\":[\"JULIDESMAN\",\"3\",\"Jl. Khatulistiwa Gg.Purnajaya II No.168-A\"],\"ibu\":[\"RUSMAYA\",\"9\",\"Jl. Khatulistiwa Gg.Purnajaya II No. 168-A\"]}","imigrasi":"{\"passport\":\"-\"}","data_sdr":"[[\"GABRIELLA YUNITA\",\"23\",\"2\",\"Jl. Khatulistiwa Gg.Purnajaya II No. 168-A\"],[\"SANDRA RAHMAYANTI\",\"20\",\"2\",\"Jl. Khatulistiwa Gg.Purnajaya II No. 168-A\"],[\"AZZIRA YUNITA\",\"11\",\"2\",\"Jl. Khatulistiwa Gg.Purnajaya II No. 168-A\"]]"}';
// $plaintext = file_get_contents("assets/images/arini.jpg");
var_dump($plaintext);
// Example Default Salt Key
$ciphertext = Crypto\sodium::encrypt($plaintext);
// Example Using Salt Key
$ciphertext = Crypto\sodium::encrypt($plaintext);
echo "<b>Encrypted Data [Binary]</b>";
var_dump($ciphertext);

echo "<b>Encryption codes</b>";
$dataCode = Crypto\sodium::encode(false);
var_dump($dataCode);
$get_keys = Crypto\sodium::encode();
$get_salt = $dataCode['salt'];

// save to file
file_put_contents("$folder/data.bin",$ciphertext);
// file_put_contents("$folder/$get_salt.bin",$ciphertext);

Crypto\sodium::close();
echo "<b>Decrypt Using Key [{$get_keys['key']}] & Nonce [{$get_keys['nonce']}]</b>";
var_dump(Crypto\sodium::decrypt(file_get_contents("assets/sodium/data.bin"),$get_keys));
echo "<b>Decrypt Using Salt Key [$get_salt]</b>";
var_dump(Crypto\sodium::decrypt(file_get_contents("assets/sodium/data.bin"),$get_salt));