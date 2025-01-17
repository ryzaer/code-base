<?php
require_once "../../autobase.php";
function decryptFile($encryptedFile, $outputFile, $key) {
    $json = json_decode(base64_decode($key), true);
    $key = isset($json['key']) ? hex2bin($json['key']) : null;
    if(!$key)
        throw new Exception("Key tidak valid.");

    // selisih ukuran file
    $bit = $json['size'];

    // Membuka file terenkripsi
    $inputHandle = fopen($encryptedFile, 'rb');
    if (!$inputHandle) 
        throw new Exception("Gagal membuka file terenkripsi.");
    
        // Membuka file output
    $outputHandle = fopen($outputFile, 'wb');
    if (!$outputHandle) {
        fclose($inputHandle);
        throw new Exception("Gagal membuka file output.");
    }

    // Membaca nonce dari awal file
    $nonce = fread($inputHandle, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    if($nonce !== hex2bin($json['nonce']))
        throw new Exception("Nonce tidak valid.");

    // Mendekripsi file secara chunk
    $chunkSize = 8192 + SODIUM_CRYPTO_SECRETBOX_MACBYTES; // Panjang chunk terenkripsi
    while (!feof($inputHandle)) {
        $encryptedChunk = fread($inputHandle, $chunkSize);
        if ($encryptedChunk === false || strlen($encryptedChunk) === 0) 
            break;        

        // Mendekripsi chunk
        $decryptedChunk = sodium_crypto_secretbox_open($encryptedChunk, $nonce, $key);
        if ($decryptedChunk === false) {
            fclose($inputHandle);
            fclose($outputHandle);
            throw new Exception("Gagal mendekripsi data. Pastikan kunci dan nonce sesuai.");
        }

        // Tulis chunk hasil dekripsi ke file output
        // fwrite($outputHandle, $decryptedChunk);
    }

    // Menutup file handle
    fclose($inputHandle);
    fclose($outputHandle);

    echo "File berhasil didekripsi.\n";
}

// Contoh penggunaan
include_once "conf.php";
try {
    $encryptedFile = "$path/video_encrypted.sodium"; // File terenkripsi
    $outputFile = "$path/video_decrypted.mp4";       // File hasil dekripsi
    $key = file_get_contents("$path/video_encrypted.json"); // Baca kunci dari file
    decryptFile($encryptedFile, $outputFile, $key);
} catch (Exception $e) {
    echo "Kesalahan: " . $e->getMessage();
}

