<?php
namespace parse; 
class SceneCutter
{
    private $ffmpeg = 'ffmpeg';
    private $input;
    private $outputDir;
    private $sceneThreshold = 0.4;
    private $minDuration = 2.0; // detik

    public function __construct($input, $outputDir)
    {
        $this->input = $input;
        $this->outputDir = rtrim($outputDir, '/');

        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0777, true);
        }
    }

    public function setSceneThreshold($threshold)
    {
        $this->sceneThreshold = $threshold;
    }

    public function setMinDuration($seconds)
    {
        $this->minDuration = $seconds;
    }

    public function detectScenes()
    {
        $cmd = "{$this->ffmpeg} -i " . escapeshellarg($this->input)
             . " -filter:v \"select='gt(scene,{$this->sceneThreshold})',showinfo\" -f null - 2>&1";

        exec($cmd, $output);

        $timestamps = [];
        foreach ($output as $line) {
            if (preg_match('/pts_time:([0-9\.]+)/', $line, $match)) {
                $timestamps[] = (float)$match[1];
            }
        }

        array_unshift($timestamps, 0.0);
        return $timestamps;
    }

    public function cutByScenesToTS()
    {
        $scenes = $this->detectScenes();
        $count = count($scenes);
        $segments = [];

        for ($i = 0; $i < $count - 1; $i++) {
            $start = $scenes[$i];
            $duration = $scenes[$i + 1] - $start;

            if ($duration < $this->minDuration) {
                continue;
            }

            $filename = sprintf("scene-%03d.ts", $i + 1);
            $outputPath = "{$this->outputDir}/$filename";

            $cmd = "{$this->ffmpeg} -ss {$start} -i " . escapeshellarg($this->input)
                 . " -t {$duration} -c:v libx264 -preset fast -crf 23 -c:a aac -f mpegts "
                 . escapeshellarg($outputPath) . " -y";

            exec($cmd);
            $segments[] = [
                'file' => $filename,
                'duration' => round($duration, 3)
            ];
        }

        return $segments;
    }

    public function generateM3U8($segments)
    {
        $targetDuration = 0;
        foreach ($segments as $seg) {
            $targetDuration = max($targetDuration, ceil($seg['duration']));
        }

        $m3u8 = "#EXTM3U\n";
        $m3u8 .= "#EXT-X-VERSION:3\n";
        $m3u8 .= "#EXT-X-TARGETDURATION:$targetDuration\n";
        $m3u8 .= "#EXT-X-MEDIA-SEQUENCE:0\n";

        foreach ($segments as $seg) {
            $m3u8 .= "#EXTINF:{$seg['duration']},\n";
            $m3u8 .= "{$seg['file']}\n";
        }

        $m3u8 .= "#EXT-X-ENDLIST\n";

        file_put_contents("{$this->outputDir}/playlist.m3u8", $m3u8);
    }

    public function process()
    {
        $segments = $this->cutByScenesToTS();
        $this->generateM3U8($segments);
    }
}