<?php

function convert_srt_to_vtt($srtFile){
    // Read the srt file content into an array of lines
    $fileHandle = fopen($srtFile, 'r');

    if ($fileHandle) {
        // Assume that every line has maximum 8192 length
        // If you don't care about line length then you can omit the 8192 param
        $lines = array();
        while (($line = fgets($fileHandle, 8192)) !== false) $lines[] = $line;

        if (!feof($fileHandle)) exit ("Error: unexpected fgets() fail\n");
        else ($fileHandle);
    }

    // Convert all timestamp lines
    // The first timestamp line is 1
    $length = count($lines);

    for ($index = 1; $index < $length; $index++) {
        // A line is a timestamp line if the second line above it is an empty line
        if ($index === 1 || trim($lines[$index - 2]) === '') {
            $lines[$index] = str_replace(',', '.', $lines[$index]);
        }
    }
    
    // Insert VTT header and concatenate all lines in the new vtt file
    return "WEBVTT\n\n".implode("",$lines);
}