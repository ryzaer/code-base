<?php
function decryptFile($encryptedFile, $outputFile, $key) {
    if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
        throw new Exception("Kunci harus sepanjang " . SODIUM_CRYPTO_SECRETBOX_KEYBYTES . " bytes.");
    }

    // Membuka file terenkripsi
    $inputHandle = fopen($encryptedFile, 'rb');
    if (!$inputHandle) {
        throw new Exception("Gagal membuka file terenkripsi.");
    }

    // Membuka file output
    $outputHandle = fopen($outputFile, 'wb');
    if (!$outputHandle) {
        fclose($inputHandle);
        throw new Exception("Gagal membuka file output.");
    }

    // Membaca nonce dari awal file
    $nonce = fread($inputHandle, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    if (strlen($nonce) !== SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
        fclose($inputHandle);
        fclose($outputHandle);
        throw new Exception("Nonce tidak valid. Panjangnya harus " . SODIUM_CRYPTO_SECRETBOX_NONCEBYTES . " bytes.");
    }

    // Mendekripsi file secara chunk
    $chunkSize = 8192 + SODIUM_CRYPTO_SECRETBOX_MACBYTES; // Panjang chunk terenkripsi
    while (!feof($inputHandle)) {
        $encryptedChunk = fread($inputHandle, $chunkSize);
        if ($encryptedChunk === false || strlen($encryptedChunk) === 0) {
            break;
        }

        // Mendekripsi chunk
        $decryptedChunk = sodium_crypto_secretbox_open($encryptedChunk, $nonce, $key);
        if ($decryptedChunk === false) {
            fclose($inputHandle);
            fclose($outputHandle);
            throw new Exception("Gagal mendekripsi data. Pastikan kunci dan nonce sesuai.");
        }

        // Tulis chunk hasil dekripsi ke file output
        fwrite($outputHandle, $decryptedChunk);
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
    $key = file_get_contents("$path/video_encrypted.key"); // Baca kunci dari file
    decryptFile($encryptedFile, $outputFile, $key);
} catch (Exception $e) {
    echo "Kesalahan: " . $e->getMessage();
}
