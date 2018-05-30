(function() {
  // The width and height of the captured photo. We will set the
  // width to the value defined here, but the height will be
  // calculated based on the aspect ratio of the input stream.

  var width = 320;    // We will scale the photo width to this
  var height = 0;     // This will be computed based on the input stream

  // |streaming| indicates whether or not we're currently streaming
  // video from the camera. Obviously, we start at false.

  var streaming = false;

  // The various HTML elements we need to configure or control. These
  // will be set by the startup() function.

  var video = null;
  var canvas = null;
  var photo = null;
  var startbutton = null;

  function startup() {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    photo = document.getElementById('photo');
    startbutton = document.getElementById('startbutton');

	 navigator.getMedia = (navigator.mediaDevices.getUserMedia ||
                    	navigator.webkitGetUserMedia ||
                        navigator.mozGetUserMedia ||
                        navigator.msGetUserMedia);

	var constraints = { audio: true, video: true }; 
						
	navigator.mediaDevices.getUserMedia(constraints)
	.then(function(mediaStream) {
	  var video = document.querySelector('video');
	  video.srcObject = mediaStream;
	  video.onloadedmetadata = function(e) {
		video.play();
	  };
	})
	.catch(function(err) { console.log(err.name + ": " + err.message); }); // always check for errors at the end.

    video.addEventListener('canplay', function(ev){
      if (!streaming) {
        height = video.videoHeight / (video.videoWidth/width);
      
        // Firefox currently has a bug where the height can't be read from
        // the video, so we will make assumptions if this happens.
      
        if (isNaN(height)) {
          height = width / (4/3);
        }
      
        video.setAttribute('width', width);
        video.setAttribute('height', height);
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);
        streaming = true;
      }
    }, false);

    startbutton.addEventListener('click', function(ev){
      takepicture();
      ev.preventDefault();
    }, false);
    
  }
  
  // Capture a photo by fetching the current contents of the video
  // and drawing it into a canvas, then converting that to a PNG
  // format data URL. By drawing it on an offscreen canvas and then
  // drawing that to the screen, we can change its size and/or apply
  // other changes before drawing it.

  function takepicture() {
    var context = canvas.getContext('2d');
    if (width && height) {
      canvas.width = width;
      canvas.height = height;

      var el_video = document.getElementById('video');
      var filters = el_video.style.WebkitFilter;
  
      context.filter = filters;
      context.drawImage(video, 0, 0, width, height);
    
      // get elements
      var data = canvas.toDataURL('image/png', true);
      var upload = getBase64Image(document.getElementById("preview"));
      var filter = document.getElementById('preview2').src;

      container = document.getElementById('preview');
      img = document.getElementById('preview2');
      canvas_width = container.clientWidth;
      canvas_height = container.clientHeight;
      img_width = img.getBoundingClientRect().width,
      img_height = img.getBoundingClientRect().height;
      x_img_ele = img.style.left;
      y_img_ele = img.style.top;

      // put it in a formData object and convert it into json format
      var formData = new FormData();
      formData.append('photo', data);
      formData.append('upload', upload);
      formData.append('filter', filter);
      formData.append('canvas_width', canvas_width);
      formData.append('canvas_height', canvas_height);
      formData.append('img_width', img_width);
      formData.append('img_height', img_height);
      formData.append('x', x_img_ele);
      formData.append('y', y_img_ele);

      var object = {};
      formData.forEach(function(value, key){
        object[key] = value;
      });
      var json = JSON.stringify(object);

      // handle ajax request
      var httpRequest = new XMLHttpRequest();

      httpRequest.onreadystatechange = function(data) {
				if (httpRequest.readyState === XMLHttpRequest.DONE) {
					if (httpRequest.status === 200) {
            var url = JSON.parse(httpRequest.responseText);
            readURL(url);
					} else {
						console.log('Error: ' + httpRequest.status);
					}
				}
			};

      httpRequest.open('POST', '../app/merge_pictures.php', true);
      httpRequest.setRequestHeader('Access-Control-Allow-Headers', '*');
      httpRequest.send(json);
    } 

    function readURL(src) { 
        var image = document.getElementById('final');
        image.removeAttribute('src'); 
        image.src = src;
    }

    function getBase64Image(img) {
      var canvas = document.createElement("canvas");
      canvas.width = img.naturalWidth;
      canvas.height = img.naturalHeight;
      var ctx = canvas.getContext("2d");
      var el_video = document.getElementById('video');
      var filters = el_video.style.WebkitFilter;
  
      ctx.filter = filters;
      ctx.drawImage(img, 0, 0);

      var dataURL = canvas.toDataURL("image/png");
      return dataURL;
  }

  }

  // Set up our event listener to run the startup process
  // once loading is complete.
  window.addEventListener('load', startup, false);

})();

function stopStreamedVideo(videoElem) {
  let stream = videoElem.srcObject;
  stream.getTracks()[0].enabled = false;  
  }

function playStreamedVideo(videoElem) {
  let stream = videoElem.srcObject;
  stream.getTracks()[0].enabled = true;  
}

function toggle() {
  var image = document.getElementById('preview');
  image.removeAttribute('src');
  image.style.visibility = 'hidden';
  document.getElementById("fileToUpload").value = "";

  var videoElem = document.getElementById('video');
  let stream = videoElem.srcObject;
  let track = stream.getTracks()[0].enabled;

  if (track == false)
    playStreamedVideo(videoElem) 
  else
    stopStreamedVideo(videoElem)  
}

document.getElementById('startbutton').addEventListener('click', function () {
  document.getElementById('modal').classList.add("is-active");
});

document.getElementById('delete').addEventListener('click', function () {
  document.getElementById('modal').classList.remove("is-active");
});


window.onload = function() {
  document.addEventListener('click', function (event) {
    if (event.target.classList.contains('thumbnail')){
      document.getElementById('show').style.display = 'block';
      document.getElementById('zoomout').style.display = 'block';
      document.getElementById('zoomin').style.display = 'block';
    }
  }, false);
};


