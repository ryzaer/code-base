<?php
$file_path = urlencode('C:\htdocs\jin.mp4');
if(isset($_GET['fname']) && $_GET['fname']){
  foreach ([
      "K:/.attachments/11250111/fc2",
      "F:/.attachments/11250111/fc2",
  ] as $dir) {
      if(file_exists("$dir/{$_GET['fname']}"))
          $file_path = urlencode("$dir/{$_GET['fname']}");
  }
}
$filename = "video.php?fname=$file_path";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Custom Video Controls with Keyboard Shortcuts</title>
  <style>
    /* Style the video */
    video {
      display: block;
      height: 40vh;
      /* margin-left:40vw; */
      margin-top: 10px;
      margin-bottom: 10px;
    }

    /* Control buttons */
    #controls{
      /* display: flex; */
      width: 100%;
      text-align: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    button {
      padding: 5px 10px;
      font-size: 16px;
      cursor: pointer;
    }

    /* Progress bar container */
    #progressContainer {
      width: 100%;
      background-color: #ddd;
      height: 10px;
      position: relative;
      margin-bottom: 10px;
    }

    /* Progress bar */
    #progressBar {
      height: 100%;
      width: 0;
      background-color: #4caf50;
      position: absolute;
      top: 0;
      left: 0;
    }

    /* Popup Screenshot */
    #popupScreenshot {
      position: absolute;
      display: none;
      background-color: white;
      border: 1px solid black;
      padding: 5px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }

    #popupImage {
      max-width: 100px;
      max-height: 56px;
    }
    body {
      margin:0
    }
    #timeRange {
      margin-top:15px;
      margin-bottom:15px;
      text-align:left;
      width:100%;
      padding-left: 37%;
    }
    @media screen and (max-width: 1200px) {
      #timeRange {
        padding-left: 30%;
      }
    }
    @media screen and (max-width: 900px) {
      #timeRange {
        padding-left: 20%;
      }
    }
    @media screen and (max-width: 600px) {
      #timeRange {
        padding-left: 5%;
      }
    }
  </style>
</head>
<body>

<center>
  <video id="myVideo" data-dir="<?=$file_path?>">
    <source src="<?=$filename?>" type="video/mp4">
    Your browser does not support the video tag.
  </video>
</center>
<div style="width:100%;text-align:right;position:absolute">
  <div id="timeDisplay2" style="padding-right:5px">00:00.000 / 00:00.000</div>
</div>
<div id="timeDisplay" style="padding-left:5px">Time : 0 Seconds</div>
<div id="progressContainer">
  <div id="progressBar"></div>
</div>
<div id="popupScreenshot">
  <img id="popupImage" alt="Screenshot Popup">
</div>

<div id="controls">
  <button id="playButton" title="Stop (press space)">&#9658;</button>
  <button id="stopButton" title="Stop (press s)">&#9724;</button>
  <button id="backwardButton"><</button>
  <button id="afterwardButton">></button>
  <button id="screenshotButton" title="Capture (press i)">C</button>
  <button id="fullscreenButton" title="Fullscreen (press z)">Z</button>
  <button id="timeCuts" title="Time Cut (reverse time from end to early)">TC</button>
  <button id="timeGet" title="Time Get">TG</button>
  <button id="timeReset" title="Time Reset">TR</button>
</div>
<div id="Prompts" style="width:100%;text-align:center">
  From <input type="text"> To <input type="text">
</div>

<div id="timeRange">
 <code id="timeRangeCut"></code>
</div>

<div id="vidScreenshot" style="padding:5px;line-height:10px"></div>

<script>
  const video = document.getElementById('myVideo'),
        playButton = document.getElementById('playButton'),
        stopButton = document.getElementById('stopButton'),
        backwardButton = document.getElementById('backwardButton'),
        afterwardButton = document.getElementById('afterwardButton'),
        screenshotButton = document.getElementById('screenshotButton'),
        fullscreenButton = document.getElementById('fullscreenButton'),
        progressBar = document.getElementById('progressBar'),
        progressContainer = document.getElementById('progressContainer'),
        popupScreenshot = document.getElementById('popupScreenshot'),
        popupImage = document.getElementById('popupImage'),
        timeDisplay = document.getElementById('timeDisplay'),
        timeDisplay2 = document.getElementById('timeDisplay2'),
        timeReset = document.getElementById('timeReset'),
        timeCuts = document.getElementById('timeCuts'),
        timeGet = document.getElementById('timeGet'),
        timeRange = document.getElementById('timeRangeCut'),
        vidScreenshot = document.getElementById('vidScreenshot'),
        val1 = document.getElementById("Prompts").getElementsByTagName('input')[0],
        val2 = document.getElementById("Prompts").getElementsByTagName('input')[1];

  let videoWasPlaying = false,
      hoverTimeout;
  // ADD Time Cuts here 
  timeCuts.addEventListener('click', () => {          
    const splitTime = timeDisplay2.innerText.split('/');
    if(!val2.value){
      val2.value = splitTime[0].trim()
    }else{
      if(!val1.value)
        val1.value = splitTime[0].trim()
    }
  });
  timeReset.addEventListener('click', () => {   
    if(val1.value || val2.value){
       val1.value="",val2.value=""
    }else{
      if(timeRange.innerText)
        if (confirm('Are you sure you want to reset this array time cuts?'))
          // reset!
          timeRange.innerText = ""
    }
    vidScreenshot.innerText=""
  });
  timeGet.addEventListener('click', () => {
    if(val1.value && val2.value){
      var existRange= timeRange.innerText ? "\n"+timeRange.innerText : "",
          addScenes = !existRange ? ',true,"$fmove-scane-%s.mp4"' : '';
            
      timeRange.innerText = `["${val1.value}","${val2.value}"${addScenes}],${existRange}`,
      val1.value="",val2.value=""
    }
  });
  // Update progress bar as video plays
  video.addEventListener('timeupdate', () => {
    const percent = (video.currentTime / video.duration) * 100;
    const fixTime = video.currentTime.toFixed(3);
    const lstTime = getLastMicroTime(fixTime);  
    const dstTime = getLastMicroTime(video.duration.toFixed(3));  
    const curTime = secondsToHms(video.currentTime);
    const durTime = secondsToHms(video.duration);  

    progressBar.style.width = `${percent}%`;
    // Update the time display
    timeDisplay.innerText = `Time : ${fixTime} Seconds`;
    timeDisplay2.innerText = `${curTime}.${lstTime} / ${durTime}.${dstTime}`;
  });
  video.addEventListener('click', () => {
    playOrPlay()   
  });
  // Format time in MM:SS format
  function getLastMicroTime(fixTime){
    var strCurTime = fixTime.toString();
    return strCurTime.substr(strCurTime.length-3);
  }
  function secondsToHms(d) {
    d = Number(d);
    var h = Math.floor(d / 3600);
    var m = Math.floor((d % 3600) / 60);
    var s = Math.floor((d % 3600) % 60);
    var hDisplay = (h > 9 ? h : `0${h}`) + ":";
    var mDisplay = (m > 9 ? m : `0${m}`) + ":";
    var sDisplay = s > 9 ? s : `0${s}`;

    return (hDisplay == "00:" ? "" : hDisplay) + mDisplay + sDisplay;
  }
  // Play button event listener
  function execPlay(){
    playButton.innerText = '❚❚';
    video.play();
  }
  function execPause(){
    playButton.innerText = "►";
    video.pause();
  }
  function execStop(){
    execPause()
    video.currentTime = 0;
    progressBar.style.width = '0%';
  }
  function playOrPlay(){
    if(videoWasPlaying == !video.paused){
      execPlay()
    }else{
      execPause()
    } 
  }
  playButton.addEventListener('click', () => {
    playOrPlay()   
  });
  

  // Stop button event listener (Pause and reset video to start)
  stopButton.addEventListener('click', () => {
    execStop()
  });

  // Backward button (rewind by 0.025 seconds)
  function rewindVideo() {
    video.currentTime = Math.max(0, video.currentTime - 0.025);  
    execPause()
  }
  function forwardVideo() {
    video.currentTime = Math.max(0, video.currentTime + 0.025);
    execPause()
  }

  // Attach the backward functionality to the button
  backwardButton.addEventListener('click', rewindVideo);
  afterwardButton.addEventListener('click', forwardVideo);

  // Update progress bar as video plays
  video.addEventListener('timeupdate', () => {
    const percent = (video.currentTime / video.duration) * 100;
    progressBar.style.width = `${percent}%`;
  });

  // Allow the user to click on the progress bar to seek video
  progressContainer.addEventListener('click', (e) => {
    const containerWidth = progressContainer.offsetWidth;
    const clickPosition = e.offsetX;
    const newTime = (clickPosition / containerWidth) * video.duration;
    video.currentTime = newTime;
  });

  // Popup Screenshot when hovering on progress bar without playing video
  progressContainer.addEventListener('click', (e) => {
  // progressContainer.addEventListener('mousemove', (e) => {
    const containerWidth = progressContainer.offsetWidth;
    const mousePosition = e.offsetX;
    const hoverTime = (mousePosition / containerWidth) * video.duration;

    // videoWasPlaying = !video.paused;
    execPause()

    clearTimeout(hoverTimeout);
    hoverTimeout = setTimeout(() => {
      video.currentTime = hoverTime;

      setTimeout(() => {
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);

        popupImage.src = canvas.toDataURL('image/webp');
        popupScreenshot.style.left = `${e.pageX + 10}px`;
        popupScreenshot.style.top = `${e.pageY - 70}px`;
        popupScreenshot.style.display = 'block';
      }, 100);
    }, 50);
  });

  progressContainer.addEventListener('mouseleave', () => {
    popupScreenshot.style.display = 'none';
    // if (videoWasPlaying) {
    //   video.play();
    // }
  });

  // Screenshot button event listener
function GetScreenShot() {
  const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);

    const dataURL = canvas.toDataURL('image/webp');
    const img = document.createElement('img');
    img.style = 'width:250px;padding:0;margin:5px';
    img.src = dataURL;
    vidScreenshot.appendChild(img);
}
  screenshotButton.addEventListener('click', GetScreenShot);

  // Fullscreen button event listener
  fullscreenButton.addEventListener('click', () => {
    if (!document.fullscreenElement) {
      video.requestFullscreen(); // Enter fullscreen
    } else {
      document.exitFullscreen(); // Exit fullscreen
    }
  });

  // Fullscreen change event listener to toggle button label
  document.addEventListener('fullscreenchange', () => {
    if (document.fullscreenElement) {
      fullscreenButton.textContent = 'Exit Fullscreen';
    } else {
      fullscreenButton.textContent = 'Z';
    }
  });

  // Keyboard shortcut for backward (rewind) action
  document.addEventListener('keydown', (event) => {
    console.log(event.keyCode);
    if (event.key === 'ArrowLeft') {
      event.preventDefault();
      rewindVideo();
    }
    if (event.key === 'ArrowRight') {
      event.preventDefault();
      forwardVideo();
    }
    // space key
    if (event.keyCode === 32) {
      event.preventDefault();
      playOrPlay()  
    }
    if (event.keyCode === 73) {
      event.preventDefault();
      GetScreenShot()
    }
    if (event.keyCode === 83) {
      event.preventDefault();
      execStop()
    }
  });
</script>

</body>
</html>
