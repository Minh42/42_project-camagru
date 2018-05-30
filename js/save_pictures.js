function saveFile() {
	
	function getBase64Image(img) {
		var canvas = document.createElement("canvas");
		canvas.width = img.naturalWidth;
		canvas.height = img.naturalHeight;
		var ctx = canvas.getContext("2d");
		ctx.drawImage(img, 0, 0);
		var dataURL = canvas.toDataURL("image/png");
		return dataURL;
	}
	
	var data = getBase64Image(document.getElementById("final"));
	var caption = document.getElementById('caption').value;

	var formData = new FormData();
	formData.append('image', data);
	formData.append('caption', caption);

	var object = {};
	formData.forEach(function(value, key){
	  object[key] = value;
	});
	var json = JSON.stringify(object);
  
	var httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function(data) {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200) {
				if (JSON.parse(httpRequest.responseText) === "image saved in database")	{
					document.getElementById('modal').classList.remove("is-active");
					location.reload();
				}
				else
					console.log(httpRequest.responseText);
			} else {
				console.log('Error: ' + httpRequest.status);
			}
		}
	};

	httpRequest.open('POST', '../app/save_pictures.php', true);
	httpRequest.send(json);
  }