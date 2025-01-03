<?php
require_once 'autobase.php';

// $plaintext = json_encode([
// "data" => "oke",
// "nama" => "grand master",
// "item" => [
//     "sword",
//     "knife",
//     "shield",
//     ]    
// ]);

$plaintext = '{"biodata":"20230703123216","nik":"6171044409040007","nama":"RESTI RAHMADEVI","alias":"Resti","gelar":"[\"\",\"\"]","nama_ayah":"JULIDESMAN","tpt_lahir":"Pontianak","tgl_lahir":"2004-09-04","gender":"2","agama":"1","kerjaan":"4","alamat":"{\"ktp\":[\"Jl. Khatulistiwa Gg.Purnajaya II No.168-A\",\"2\",\"4\",\"61\",\"6171\",\"6171040002\"],\"skrg\":[\"Jl. Khatulistiwa Gg. Purnajaya II No.168-A\",\"2\",\"4\",\"61\",\"6171\",\"6171040002\"]}","pendidikan":"{\"terakhir\":\"5\",\"riwayat\":\"[[\\\"3\\\",\\\"Sd N 06 Pontianak Utara\\\",\\\"2016\\\"],[\\\"4\\\",\\\"Smp N 15 Pontianak Utara\\\",\\\"2019\\\"],[\\\"5\\\",\\\"Sma N 5 Pontianak Utara\\\",\\\"2022\\\"]]\"}","telp":"0895370009657","sinyal":"[\"150\",\"40\",\"1\",\"2\",\"1\",\"1\",\"1\",\"1\",\"2\",\"3\",\"1\",\"1\",\"5\",\"1\",\"1\",\"1\",\"-\",\"-\"]","data_ortu":"{\"ayah\":[\"JULIDESMAN\",\"3\",\"Jl. Khatulistiwa Gg.Purnajaya II No.168-A\"],\"ibu\":[\"RUSMAYA\",\"9\",\"Jl. Khatulistiwa Gg.Purnajaya II No. 168-A\"]}","imigrasi":"{\"passport\":\"-\"}","data_sdr":"[[\"GABRIELLA YUNITA\",\"23\",\"2\",\"Jl. Khatulistiwa Gg.Purnajaya II No. 168-A\"],[\"SANDRA RAHMAYANTI\",\"20\",\"2\",\"Jl. Khatulistiwa Gg.Purnajaya II No. 168-A\"],[\"AZZIRA YUNITA\",\"11\",\"2\",\"Jl. Khatulistiwa Gg.Purnajaya II No. 168-A\"]]"}';
$ciphertext = Crypto\sodium::encrypt($plaintext);
var_dump($ciphertext);
// bin version of data;
file_put_contents("data.bin",$ciphertext);

// The same nouce and key are required to decrypt
$ciphertext = Crypto\sodium::decrypt(file_get_contents("data.bin"));
var_dump($ciphertext);

// echo "KEY BIN TO BASE64";
// var_dump(base64_encode($ciphertext[0]));
// var_dump(base64_decode(base64_encode($ciphertext[0])));
// echo "KEY BIN TO HEX";
// var_dump(bin2hex($ciphertext[0]));
// var_dump(hex2bin(bin2hex($ciphertext[0])));
// $nch = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

// var_dump(bin2hex($nch));
// var_dump(uniqid());

var_dump(Crypto\sodium::encode());


// ANOTHE EXAMPLE

// >>> ENCRYPT FILE
// $password    = 'password';
// $inputFile   = 'data.bin';
// $encryptedFile = 'data.file.enc';
// $chunkSize   = 4096;

// $alg = SODIUM_PWHASH_ALG_DEFAULT;
// $opsLimit = SODIUM_PWHASH_OPSLIMIT_MODERATE;
// $memLimit = SODIUM_PWHASH_MEMLIMIT_MODERATE;
// $salt = random_bytes(SODIUM_PWHASH_SALTBYTES);

// $secretKey = sodium_pwhash(
//     SODIUM_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES,
//     $password,
//     $salt,
//     $opsLimit,
//     $memLimit,
//     $alg
// );
// var_dump($secretKey);
// $fdIn    = fopen($inputFile, 'rb');
// $fdOut   = fopen($encryptedFile, 'wb');

// fwrite($fdOut, pack('C', $alg));
// fwrite($fdOut, pack('P', $opsLimit));
// fwrite($fdOut, pack('P', $memLimit));
// fwrite($fdOut, $salt);

// [$stream, $header] = sodium_secretstream_xchacha20poly1305_init_push($secretKey);
// fwrite($fdOut, $header);
// $tag = SODIUM_SECRETSTREAM_XCHACHA20POLY1305_TAG_MESSAGE;
// do {
//     $chunk = fread($fdIn, $chunkSize);
//     if (feof($fdIn)) {
//         $tag = SODIUM_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL;
//     }
//     $encryptedChunk = sodium_secretstream_xchacha20poly1305_push($stream, $chunk, '', $tag);
//     fwrite($fdOut, $encryptedChunk);
// } while ($tag !== SODIUM_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL);
// fclose($fdOut);
// fclose($fdIn);
// ///>>> DECRYPT FILE
// var_dump(file_get_contents($encryptedFile));
// $decryptedFile = 'data.file.dec';
// $fdIn     = fopen($encryptedFile, 'rb');
// $fdOut    = fopen($decryptedFile, 'wb');
// $alg      = unpack('C', fread($fdIn, 1))[1];
// $opsLimit = unpack('P', fread($fdIn, 8))[1];
// $memLimit = unpack('P', fread($fdIn, 8))[1];
// $salt     = fread($fdIn, SODIUM_PWHASH_SALTBYTES);
// $header   = fread($fdIn, SODIUM_SECRETSTREAM_XCHACHA20POLY1305_HEADERBYTES);
// $secretKey = sodium_pwhash(
//     SODIUM_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES,
//     $password,
//     $salt,
//     $opsLimit,
//     $memLimit,
//     $alg
// );
// $stream = sodium_secretstream_xchacha20poly1305_init_pull($header, $secretKey);
// do {
//     $chunk   = fread($fdIn, $chunkSize + SODIUM_SECRETSTREAM_XCHACHA20POLY1305_ABYTES);
//     $res     = sodium_secretstream_xchacha20poly1305_pull($stream, $chunk);
//     if ($res === false) {
//         break;
//     }    
//     [$decrypted_chunk, $tag] = $res;
//     fwrite($fdOut, $decrypted_chunk);
// } while (!feof($fdIn) && $tag !== SODIUM_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL);
// $ok = feof($fdIn);
// fclose($fdOut);
// fclose($fdIn);
// if (!$ok) {
//     die('Invalid/corrupted input');
// }
// $ciphertext = Crypto\sodium::decrypt(file_get_contents("data.file.dec"));
// var_dump($ciphertext);