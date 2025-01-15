<?php
function encryptFile($inputFile, $outputFile, $key) {
    if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
        throw new Exception("Kunci harus sepanjang " . SODIUM_CRYPTO_SECRETBOX_KEYBYTES . " bytes.");
    }

    // Membuka file input dan output
    $inputHandle = fopen($inputFile, 'rb');
    if (!$inputHandle) {
        throw new Exception("Gagal membuka file input.");
    }

    $outputHandle = fopen($outputFile, 'wb');
    if (!$outputHandle) {
        fclose($inputHandle);
        throw new Exception("Gagal membuka file output.");
    }

    // Membuat nonce
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    fwrite($outputHandle, $nonce); // Simpan nonce di awal file output

    // Ukuran chunk
    $chunkSize = 4096;

    while (!feof($inputHandle)) {
        $chunk = fread($inputHandle, $chunkSize);

        if ($chunk === false) {
            fclose($inputHandle);
            fclose($outputHandle);
            throw new Exception("Gagal membaca file input.");
        }

        // Mengenkripsi chunk
        $encryptedChunk = sodium_crypto_secretbox($chunk, $nonce, $key);

        // Tulis chunk terenkripsi ke file output
        fwrite($outputHandle, $encryptedChunk);

        // Nonce tidak diubah di sini untuk menjaga sinkronisasi.
    }

    // Menutup file handle
    fclose($inputHandle);
    fclose($outputHandle);

    echo "File berhasil dienkripsi.\n";
}

// Contoh penggunaan
include_once "conf.php";
try {
    $inputFile = "$path/output.mp4";
    $outputFile = "$path/video_encrypted.sodium";
    $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES); // Kunci rahasia yang aman
    file_put_contents("$path/encryption_key.key", $key); // Simpan kunci ke file terpisah
    encryptFile($inputFile, $outputFile, $key);
} catch (Exception $e) {
    echo "Kesalahan: " . $e->getMessage() . "\n";
}
?>
