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
      width: 400px;
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
  </style>
</head>
<body>

<video id="myVideo">
  <source src="video.php" type="video/mp4">
  Your browser does not support the video tag.
</video>

<div id="controls">
  <button id="playButton">Play</button>
  <button id="stopButton">Stop</button>
  <button id="backwardButton">Backward</button>
  <button id="afterwardButton">Afterward</button>
  <button id="screenshotButton">Screenshot</button>
</div>

<div id="timeDisplay">Current Time: 0</div>

<div id="progressContainer">
  <div id="progressBar"></div>
</div>

<div id="popupScreenshot">
  <img id="popupImage" alt="Screenshot Popup">
</div>

<script>
  const video = document.getElementById('myVideo');
  const playButton = document.getElementById('playButton');
  const stopButton = document.getElementById('stopButton');
  const backwardButton = document.getElementById('backwardButton');
  const afterwardButton = document.getElementById('afterwardButton');
  const screenshotButton = document.getElementById('screenshotButton');
  const progressBar = document.getElementById('progressBar');
  const progressContainer = document.getElementById('progressContainer');
  const popupScreenshot = document.getElementById('popupScreenshot');
  const popupImage = document.getElementById('popupImage');
  const timeDisplay = document.getElementById('timeDisplay');

  let videoWasPlaying = false;
  let hoverTimeout;

  // Update progress bar as video plays
  video.addEventListener('timeupdate', () => {
    const percent = (video.currentTime / video.duration) * 100;
    progressBar.style.width = `${percent}%`;

    // Update the time display
    timeDisplay.innerText = `Current Time: ${video.currentTime.toFixed(3)}`;
  });

  // Play button event listener
  function playOrPlay(){
    if(videoWasPlaying == !video.paused){
      playButton.innerText = 'Pause';
      video.play();
    }else{
      playButton.innerText = 'Play';
      video.pause();
    } 
  }
  playButton.addEventListener('click', () => {
    playOrPlay()   
  });

  // Stop button event listener (Pause and reset video to start)
  stopButton.addEventListener('click', () => {
    video.pause();
    playButton.innerText = 'Play';
    video.currentTime = 0;
    progressBar.style.width = '0%';
  });

  // Backward button (rewind by 0.025 seconds)
  function rewindVideo() {
    video.currentTime = Math.max(0, video.currentTime - 0.025);  
    video.pause();
    playButton.innerText = 'Play';
  }
  function forwardVideo() {
    video.currentTime = Math.max(0, video.currentTime + 0.025);
    video.pause();  
    playButton.innerText = 'Play';
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

    videoWasPlaying = !video.paused;
    video.pause();
    playButton.innerText = 'Play';

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

  // Keyboard shortcut for backward (rewind) action
  document.addEventListener('keydown', (event) => {
    event.preventDefault();
    console.log(event.keyCode);
    if (event.key === 'ArrowLeft') {
      rewindVideo();
    }
    if (event.key === 'ArrowRight') {
      forwardVideo();
    }
    // space key
    if (event.keyCode === 32) {
      playOrPlay()  
    }
    if (event.keyCode === 83) {
      GetScreenShot()
    }
  });
</script>

</body>
</html>
