(function() {
	
		var upload = document.getElementById('fileToUpload');
		upload.addEventListener('change', function() {
	
			var file = document.getElementById('fileToUpload').files[0];
			
			var formData = new FormData();
			formData.append('file', file);
		
			var httpRequest = new XMLHttpRequest();

			httpRequest.onreadystatechange = function(data) {
				if (httpRequest.readyState === XMLHttpRequest.DONE) {
					if (httpRequest.status === 200) {
						readURL(httpRequest.responseText);
					} else {
						console.log('Error: ' + httpRequest.status);
					}
				}
			};
	
			httpRequest.open('POST', '../app/upload_pictures.php', true);
			httpRequest.send(formData);
		});
	})();
	
	function readURL(src) {  
		
				image = document.getElementById('preview');
				image.removeAttribute('src'); 
				image.src = src;
				image.style.visibility = 'visible';
		}