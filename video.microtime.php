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
      width: 100vh;
      margin-bottom: 10px;
    }

    /* Control buttons */
    #controls {
      display: flex;
      align-items: center;
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
  </style>
</head>
<body>

<video id="myVideo">
  <source src="video.php" type="video/mp4">
  Your browser does not support the video tag.
</video>

<div id="controls">
  <button id="playButton">&#9658;</button>
  <button id="stopButton">&#9724;</button>
  <button id="backwardButton"><</button>
  <button id="afterwardButton">></button>
  <button id="screenshotButton" title="Capture">C</button>
  <button id="fullscreenButton" title="Fullscreen">Z</button>
</div>
<div style="width:100%;text-align:right;position:absolute">
  <div id="timeDisplay2">00:00.000 / 00:00.000</div>
</div>
<div id="timeDisplay">Time Seconds: 0</div>

<div id="progressContainer">
  <div id="progressBar"></div>
</div>

<div id="popupScreenshot">
  <img id="popupImage" alt="Screenshot Popup">
</div>

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
        timeDisplay2 = document.getElementById('timeDisplay2');

  let videoWasPlaying = false,
      hoverTimeout;

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
    timeDisplay.innerText = `Time Seconds: ${fixTime}`;
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
    img.style = 'width:400px;padding:0;margin: 0 10px 5px 0';
    img.src = dataURL;
    document.body.appendChild(img);
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
      fullscreenButton.textContent = 'Fullscreen';
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
    if (event.keyCode === 67) {
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
