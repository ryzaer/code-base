<?php
require_once "../../autobase.php";
function streamFileSodium($encryptedFile,$key,$mimeType="video/mp4") {
    // Check if file exists
    if (!file_exists($encryptedFile)) {
        // Handle file not found error
        header("HTTP/1.1 404 Not Found");
        exit("File not found.");
    }
    $json = json_decode(base64_decode($key), true);
    $key = isset($json['key']) ? hex2bin($json['key']) : null;
    if(!$key)
        throw new Exception("Key tidak valid.");

    
    // Menentukan nama file
    $fileName = str_replace('.sodium', '.mp4', basename($encryptedFile));

    // selisih ukuran file
    $diffSize = $json['size'] - (SODIUM_CRYPTO_SECRETBOX_MACBYTES + SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

    // menentukan ukuran file
    $fileSize = filesize($encryptedFile) - $diffSize;

    // Mendekripsi file secara chunk
    $chunkSize = 8192 + SODIUM_CRYPTO_SECRETBOX_MACBYTES; // Panjang chunk terenkripsi
    
    // Start streaming headers
    // header("Content-Type: $mimeType");
    header("Content-Disposition: inline; filename=\"$fileName\"");
    header("Content-Length: $fileSize");
    header('Accept-Ranges: bytes');

    // Membuka file terenkripsi
    $inputHandle = fopen($encryptedFile, 'rb');
    if (!$inputHandle) 
        throw new Exception("Gagal membuka file terenkripsi.");

    // Membaca nonce dari awal file
    $nonce = fread($inputHandle, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    if($nonce !== hex2bin($json['nonce']))
        throw new Exception("Nonce tidak valid.");

    // Optional: Handle partial content requests (i.e., range requests)
    $range = null;
    $start = 0;
    if (isset($_SERVER['HTTP_RANGE'])) {
        $range = str_replace('bytes=', '', $_SERVER['HTTP_RANGE']);
        $range = explode('-', $range);
    }

    // If a range was requested, handle the partial content logic
    if ($range) {
        $start = intval($range[0]);
        $end = isset($range[1]) && $range[1] ? intval($range[1]) : $fileSize - 1;
        $length = $end - $start + 1;
        header("HTTP/1.1 206 Partial Content");
        header("Content-Range: bytes $start-$end/$fileSize");
        header("Content-Length: $length");
        // Memposisikan pointer file ke offset yang sesuai
        fseek($file, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + $start );
    }

    $position = $start;
    while (!feof($inputHandle) && ($position = ftell($inputHandle)) <= $fileSize) {
        $encryptedChunk = fread($inputHandle, $chunkSize);
        if ($encryptedChunk === false || strlen($encryptedChunk) === 0) 
            break;       
        
        if ($range && $position >= $end)
            break;

        // Mendekripsi chunk
        $decryptedChunk = sodium_crypto_secretbox_open($encryptedChunk, $nonce, $key);
        if ($decryptedChunk === false) {
            fclose($inputHandle);
            throw new Exception("Gagal mendekripsi data. Pastikan kunci dan nonce sesuai.");
        }else{
            print $decryptedChunk;
        }
    }

    // Menutup file handle
    fclose($inputHandle);
}

// Contoh penggunaan
include_once "conf.php";
try {
    $encryptedFile = "$path/video_encrypted.sodium"; // File terenkripsi
    $key = file_get_contents("$path/video_encrypted.json"); // Baca kunci dari file
    streamFileSodium($encryptedFile, $key);
} catch (Exception $e) {
    echo "Kesalahan: " . $e->getMessage();
}

