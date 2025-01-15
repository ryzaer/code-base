<?php
// Streaming encript video tanpa temporari file
function streamVideo($filePath,$key=null,$mimeType="application/octet-stream") {
    
    // Check if file exists
    if (!file_exists($filePath)) {
        // Handle file not found error
        header("HTTP/1.1 404 Not Found");
        exit("File not found.");
    }

    $file = fopen($filePath, 'rb');

    // Menentukan nama file
    $fileName = basename($filePath);

    if($key){
        // Membaca nonce dari awal file
        $fileName = str_replace('.sodium', '.mp4', $fileName);
        $nonce = fread($file, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $fileSize = filesize($filePath) - SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;
        $chunkSize = 4096 + SODIUM_CRYPTO_SECRETBOX_MACBYTES; // Chunk terenkripsi dengan MAC
    }else{
        $fileSize = filesize($filePath);
        // 1MB chunks for better performance
        $chunkSize = 1024 * 1024;
        $checkMime = mime_content_type($filePath);
        if ($checkMime)
            $mimeType = $checkMime;
    }
    
    // Start streaming headers
    // header("Content-Type: $mimeType");
    header("Content-Disposition: inline; filename=\"$fileName\"");
    header("Content-Length: $fileSize");
    header('Accept-Ranges: bytes');

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
        fseek($file, $key ? SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + $start : $start);
    }

    while (!feof($file) && ($position = ftell($file)) <= $fileSize) {
            if ($range && $position >= $end)
                break;
            if($key){
                $chunk = fread($file, $chunkSize);
                $show = sodium_crypto_secretbox_open($chunk,$nonce,$key);
                if ($show)
                    print $show;
            }else{
                print fread($file, $chunkSize);
            }
            // Ensure each chunk is sent to the client
            ob_flush();
            flush(); 
    }
    // Stream the file in chunks
    // if($key){
        
    //     // Streaming data sesuai byte-range
    //     $currentPos = $start;
    //     while ($currentPos <= $end && !feof($file)) {
    //         $chunk = fread($file, $chunkSize);

    //         // Mendekripsi chunk
    //         $decryptedChunk = sodium_crypto_secretbox_open($chunk, $nonce, $key);
    //         if ($decryptedChunk === false)
    //             die("Gagal mendekripsi data.");

    //         // Mengirimkan data ke klien
    //         $remaining = $end - $currentPos + 1;
    //         if (strlen($decryptedChunk) > $remaining) {
    //             print substr($decryptedChunk, 0, $remaining);
    //         } else {
    //             print $decryptedChunk;
    //         }

    //         // Perbarui posisi
    //         $currentPos += strlen($decryptedChunk);

    //         // Flush buffer output
    //         ob_flush();
    //         flush();
    //     }
        
    // }else{
    //     while (!feof($file) && ($position = ftell($file)) <= $fileSize) {
    //             if ($range && $position >= $end)
    //                 break;
    //             print fread($file, $chunkSize);
    //             ob_flush();
    //         flush(); // Ensure each chunk is sent to the client
    //     }
    // }
        
    fclose($file);
    exit;
}
include_once "conf.php";
$videoFile = $path.'/output.mp4'; // Ganti dengan file video Anda
$videoFile = $path.'/video_encrypted.sodium'; // Ganti dengan file video Anda
if (file_exists($videoFile)) {
    $key = file_get_contents($path.'/encryption_key.key');
    streamVideo($videoFile,$key,"video/mp4");
    // streamVideo($videoFile);
} else {
    http_response_code(404);
    echo "Video tidak ditemukan.";
}
?>
