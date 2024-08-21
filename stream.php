<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
      <style>
         body { margin: 0; }
         #overlay {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background-color: #ccc;        
            z-index:999;
         }
         #video{
            width:100%;
            height:100%;
            box-sizing: border-box;
         }
         #video::cue {
            font-size: 75%;
            background:none;
            text-shadow: 1px 1px 2px black, 0 0 0.1em black, 0 0 0.01em black;
         }
      </style>
   </head>
   <body>
      <div id="overlay">
         <video id="video" controls preload="metadata">
            <source src="video.php" type="video/mp4" size="720"/>
            <source src="video.php" type="video/mp4" size="480"/>
            <source src="video.php" type="video/mp4" size="320"/>
            <track label="Indonesia" kind="subtitles" srclang="en" src="video.sub.php" default/>
            <!--<track label="Deutsch" kind="subtitles" srclang="de" src="video.sub.php">
            <track label="Español" kind="subtitles" srclang="es" src="video.sub.php">-->
         </video>
      </div>
   </body>
</html>