<?php
function streamVideo($filePath) {
    if (!file_exists($filePath)) {
        http_response_code(404);
        echo "File not found!";
        exit;
    }

    $fileSize = filesize($filePath);
    $start = 0;
    $end = $fileSize - 1;

    if (isset($_SERVER['HTTP_RANGE'])) {
        // Mendapatkan range byte dari header
        $range = $_SERVER['HTTP_RANGE'];
        list(, $range) = explode('=', $range, 2);
        list($start, $end) = explode('-', $range);
        $start = intval($start);
        $end = $end ? intval($end) : $fileSize - 1;

        if ($start > $end || $end >= $fileSize) {
            http_response_code(416); // Range Not Satisfiable
            header("Content-Range: bytes */$fileSize");
            exit;
        }

        header('HTTP/1.1 206 Partial Content');
    } else {
        header('HTTP/1.1 200 OK');
    }

    $length = $end - $start + 1;

    // Header HTTP untuk streaming
    header('Content-Type: video/mp4');
    header("Content-Range: bytes $start-$end/$fileSize");
    header('Accept-Ranges: bytes');
    header('Content-Length: ' . $length);

    // Kirim data secara bertahap
    $fp = fopen($filePath, 'rb');
    fseek($fp, $start);

    while (!feof($fp) && $length > 0) {
        $chunkSize = min(8192, $length);
        echo fread($fp, $chunkSize);
        $length -= $chunkSize;
        ob_flush();
        flush();
    }

    fclose($fp);
}
include_once "conf.php";
$filePath = $path.'/output.mp4'; // Path ke file video
streamVideo($filePath);
?>
