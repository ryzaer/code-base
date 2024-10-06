<?php
require_once 'autobase.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>My Video</title>
    <!-- For IE8 (for Video.js versions prior to v7)
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    -->
    
  </head>
  <body>
   <h1>My Video</h1>
   <style>
        @import "https://vjs.zencdn.net/7.7.6/video-js.css";
        .video-js .vjs-time-control {
            display: block;
        }
        .video-js .vjs-remaining-time {
            display: none;
        }
   </style>
   <video-js id="my_video_1" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="640" height="268">
    <source src="assets/videos/hls.example/playall.m3u8?_<?=time()?>" type="application/x-mpegURL">
   </video-js>
   <b><i id="current_time"></i> / <i id="duration_time"></i></b>
   <!--This is for Video.js by itself -->
   <script src="https://unpkg.com/video.js@7.18.1/dist/video.js"></script>
   <!--This is for HLS compatibility with all major browsers
   <script src = "https://unpkg.com/browse/@videojs/http-streaming@1.13.3/dist/videojs-http-streaming.min.js"></script>-->
   <script>
    var convertPlayerTimes = function(whereYouAt){
      var hrs = Math.floor(whereYouAt / 3600); 
      var min = Math.floor(whereYouAt % 3600 / 60);   
      var sec = Math.floor(whereYouAt % 60);
      var mis = whereYouAt.toString().split(".");
      var times = [
            (hrs<10?"0"+hrs:hrs),
            (min<10?"0"+min:min),
            (sec<10?"0"+sec:sec)
          ];
      return times.join(":") + ( mis[1] ? "." + mis[1].substr(0,3) : "" );
    };
    var player = videojs('my_video_1');  
        player.on('timeupdate', function() {     
          document.getElementById("current_time").innerHTML = convertPlayerTimes(this.currentTime());
          document.getElementById("duration_time").innerHTML = convertPlayerTimes(this.duration());            
        });
   </script>
  </body>
</html>