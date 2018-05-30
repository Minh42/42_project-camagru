(function() {

	var offset = 20;
   
	var ajaxready = true;
	window.data = ajaxready;
	  	  
	window.addEventListener("scroll", function() {
		if (window.data == false) return;

		var wrap = document.getElementById('content');
		var contentHeight = wrap.offsetHeight;
		var yOffset = window.pageYOffset; 
		var y = yOffset + window.innerHeight;
	  	if(y >= contentHeight) {

			window.data = false;
			
			var navElements = document.querySelectorAll('#content');
			navElements.forEach(function(navElement) {
			  fadeIn(navElement, 5000);
			})

			var formData = new FormData();
			formData.append('offset', offset);
	
			var object = {};
			formData.forEach(function(value, key){
				object[key] = value;
			});
			var json = JSON.stringify(object);

			var httpRequest = new XMLHttpRequest();
			httpRequest.onreadystatechange = function(data) {
				if (httpRequest.status === 200 && httpRequest.readyState === 4) {
					var array = JSON.parse(httpRequest.responseText);
					var myHTML = '';
					for (var i = 0; i < array.length; i++) {
						myHTML += `
						<div class="gallery">
							<img class="picture" src="${array[i]['image_path']}" id="${array[i]['photo_id']}" width="612" height="612">
						</div>
						`
					}

					document.getElementById('content').innerHTML = wrap.innerHTML + myHTML;
					offset += 20;
					window.data = true;
				}
			};
			httpRequest.open('POST', '../app/load_gallery.php', true);
			httpRequest.setRequestHeader('Content-Type', 'application/json');
			httpRequest.send(json);
		}
	});

})();

function fadeIn(el, time) {
	el.style.opacity = 0;
  
	var last = +new Date();
	var tick = function() {
	  el.style.opacity = +el.style.opacity + (new Date() - last) / time;
	  last = +new Date();
  
	  if (+el.style.opacity < 1) {
		(window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
	  }
	};
	tick();
}