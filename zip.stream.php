<?php
include_once("autoload.php");
// still watch it if can run on php 7
// $nameBaseOfFile = "2643836";
// $zipFilePath = "F:/.ssh/102301/$nameBaseOfFile.zip"; // Path to the ZIP file
// $fileNameInsideZip = "$nameBaseOfFile-scane-01.mp4"; // Name of the file inside the ZIP
// $PasswordToOpenZip = md5($nameBaseOfFile); // If Passowrd is set


$nameBaseOfFile = "arini";
$zipFilePath = 'assets/zipunzip/safe/json_data.zip'; // Path to the ZIP file
$fileNameInsideZip = "$nameBaseOfFile.jpg"; // Name of the file inside the ZIP
$PasswordToOpenZip = 'S$gjhs'; // If Passowrd is set

// Check if the ZIP file exists
if (!file_exists($zipFilePath)) {
    header("HTTP/1.1 404 Not Found");
    exit("ZIP file does not exist.");
}

// Open the ZIP archive
$zip = new ZipArchive();
if ($zip->open($zipFilePath) !== TRUE) {
    header("HTTP/1.1 500 Internal Server Error");
    exit("Failed to open ZIP file.");
}

//Password Check
if($PasswordToOpenZip)
    $zip->setPassword($PasswordToOpenZip);

// Locate the file inside the ZIP
if (($index = $zip->locateName($fileNameInsideZip)) === false) {
    header("HTTP/1.1 404 Not Found");
    exit("File not found in ZIP.");
}

// Get the file size inside the ZIP
$stat = $zip->statIndex($index);
$fileSize = $stat['size'];


// Dynamically determine the MIME type
$fileExtension = pathinfo($stat['name'], PATHINFO_EXTENSION);
$mimeType = \__fn::ext2mime($fileExtension);

// Default to binary data if MIME type is not found
if (!$mimeType) {
    $mimeType = "application/octet-stream";
}

// Handle HTTP range requests
$start = 0;
$end = $fileSize - 1;
if (isset($_SERVER['HTTP_RANGE'])) {
    // Parse the range header
    if (preg_match('/bytes=(\d+)-(\d*)/', $_SERVER['HTTP_RANGE'], $matches)) {
        $start = intval($matches[1]);
        if (!empty($matches[2])) {
            $end = intval($matches[2]);
        }
    }
}

// Validate the range
if ($start > $end || $start >= $fileSize) {
    header("HTTP/1.1 416 Requested Range Not Satisfiable");
    header("Content-Range: bytes */$fileSize");
    exit();
}


// Set headers for partial content
header("HTTP/1.1 206 Partial Content");
header("Content-Type: $mimeType");
header("Content-Length: " . ($end - $start + 1));
header("Content-Range: bytes $start-$end/$fileSize");
header("Accept-Ranges: bytes");
header("Content-Disposition: inline; filename=\"{$stat['name']}\"");


// Stream the file content from the ZIP
$stream = $zip->getStream($stat['name']);
if (!$stream) {
    header("HTTP/1.1 500 Internal Server Error");
    exit("Failed to open file inside ZIP.");
}

// Seek to the start position
fseek($stream, $start);

// Stream the requested range
$bufferSize = 8192; // 8KB buffer
$bytesToRead = $end - $start + 1;
while ($bytesToRead > 0 && !feof($stream)) {
    $chunkSize = min($bufferSize, $bytesToRead);
    echo fread($stream, $chunkSize);
    flush();
    $bytesToRead -= $chunkSize;
}

// Close the stream and ZIP file
fclose($stream);
$zip->close();