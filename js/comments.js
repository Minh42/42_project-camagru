(function() {
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('picture')) {
		document.getElementById('modal2').classList.add("is-active");
		document.getElementById('photo_comment').value = "";

		var element = event.target;
		var idElement = element.getAttribute('id');
		window.data = idElement;
		
		var formData = new FormData();
		formData.append('photo_id', idElement);

		var object = {};
		formData.forEach(function(value, key){
			object[key] = value;
		});
		var json = JSON.stringify(object);

		var httpRequest = new XMLHttpRequest();
		
		httpRequest.onreadystatechange = function(data) {
			if (httpRequest.readyState === XMLHttpRequest.DONE) {
				if (httpRequest.status === 200) {

					var array = JSON.parse(httpRequest.responseText);

					var username = array.username;
					var caption = array.caption;
					var comments = array.comments;
					var nb_likes = array.likes;

					var myHTML = '';
					var myHTML2 = `${nb_likes} 😍`;

					for (var i = 0; i < comments.length; i++) {
						if (comments[i]['profile_pic_url']) {
							myHTML += `
							<article class="media">
							<figure class="media-left">
							<p class="image is-48x48">
								<img style='border-radius:50%' src="${comments[i]['profile_pic_url']}">
							</p>
							</figure>
							<div class="media-content">
							<div class="content">
								<p>
								<strong>${comments[i]['username']}</strong>
								<br>
									${comments[i]['comment']}
								<br>
								<small><a>Published on</a> ${comments[i]['date_updated']}</small>
								</p>
							</div>
							</div>
							</article>`
						}
						else {
							myHTML += `
							<article class="media">
							<figure class="media-left">
							  <p class="image is-48x48">
								<img style='border-radius:50%' src="https://bulma.io/images/placeholders/96x96.png">
							  </p>
							</figure>
							<div class="media-content">
							  <div class="content">
								<p>
								  <strong>${comments[i]['username']}</strong>
								  <br>
									  ${comments[i]['comment']}
								  <br>
								  <small><a>Published on</a> ${comments[i]['date_updated']}</small>
								</p>
							  </div>
							</div>
						  	</article>`
						}
					}
					document.getElementById("myHTMLWrapper").innerHTML = myHTML;
					document.getElementById('likes').innerHTML = myHTML2;
					document.getElementById('username_modal').innerHTML = username;
					document.getElementById('caption_modal').innerHTML = caption;
					document.getElementById('this_picture').src = element.src;

				} else {
					console.log('Error: ' + httpRequest.status);
				}
			}
		};
		
		httpRequest.open('POST', '../app/display_comment.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/json');
		httpRequest.send(json);
		}

}, false);
})();


function postComment() {
	
			var idElement = window.data;		
			var comment = document.getElementById('photo_comment').value;

			var formData = new FormData();
			formData.append('photo_id', idElement);
			formData.append('comment', comment);
	
			var object = {};
			formData.forEach(function(value, key){
				object[key] = value;
			});
			var json = JSON.stringify(object);
			
			var httpRequest = new XMLHttpRequest();

			httpRequest.onreadystatechange = function(data) {
				if (httpRequest.status === 200 && httpRequest.readyState === 4) {
					if(JSON.parse(httpRequest.responseText) === "success") {
						document.getElementById('modal2').classList.remove("is-active");
					}
				}
			};
		
			httpRequest.open('POST', '../app/post_comment.php', true);
			httpRequest.setRequestHeader('Content-Type', 'application/json');
			httpRequest.send(json);
}

function saveLikes() {
			var idElement = window.data;

			var formData = new FormData();
			formData.append('photo_id', idElement);
	
			var object = {};
			formData.forEach(function(value, key){
				object[key] = value;
			});
			var json = JSON.stringify(object);
			
			var httpRequest = new XMLHttpRequest();

			httpRequest.onreadystatechange = function(data) {
				if (httpRequest.status === 200 && httpRequest.readyState === 4) {
						var nb_likes = JSON.parse(httpRequest.responseText);
						var myHTML2 = `${nb_likes} 😍`;
						document.getElementById('likes').innerHTML = myHTML2;
				}
			};
		
			httpRequest.open('POST', '../app/save_likes.php', true);
			httpRequest.setRequestHeader('Content-Type', 'application/json');
			httpRequest.send(json);

}

function deletePicture() {
				var idElement = window.data;
	
				var formData = new FormData();
				formData.append('photo_id', idElement);
		
				var object = {};
				formData.forEach(function(value, key){
					object[key] = value;
				});
				var json = JSON.stringify(object);

				var httpRequest = new XMLHttpRequest();
				httpRequest.onreadystatechange = function(data) {
					if (httpRequest.status === 200 && httpRequest.readyState === 4) {
						if(JSON.parse(httpRequest.responseText) === "success") {
							document.getElementById('modal2').classList.remove("is-active");
							location.reload();
						}
					}
				};
			
				httpRequest.open('POST', '../app/delete_pictures.php', true);
				httpRequest.setRequestHeader('Content-Type', 'application/json');
				httpRequest.send(json);
}

function closeModal() {
	document.getElementById('modal2').classList.remove("is-active");
}


  









  

