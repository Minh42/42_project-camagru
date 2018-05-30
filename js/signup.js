(function() {
		var form = document.getElementById('form');
		var modal = document.getElementById('modal');

		form.addEventListener("submit", function (event) {
			event.preventDefault();
			sendData();
		});
	
		function sendData() {

			// Bind the FormData object and the form element
			var formData = new FormData(form);
			var object = {};
			formData.forEach(function(value, key){
				object[key] = value;
			});
			var json = JSON.stringify(object);
	
			var httpRequest = new XMLHttpRequest();
			httpRequest.onreadystatechange = function(data) {
				if (httpRequest.readyState === XMLHttpRequest.DONE) {
					if (httpRequest.status === 200) {
						if (JSON.parse(httpRequest.responseText) === "Registration successful") {
							document.getElementById('error_email').innerHTML = "";
							document.getElementById('error_firstname').innerHTML = "";
							document.getElementById('error_lastname').innerHTML = "";
							document.getElementById('error_username1').innerHTML = "";
							document.getElementById('error_username2').innerHTML = "";
							document.getElementById('error_password1').innerHTML = "";
							document.getElementById('error_password2').innerHTML = "";
							document.getElementById('error_password3').innerHTML = "";
							document.getElementById('modal').classList.add("is-active");
						}
						else {
							var messages = JSON.parse(httpRequest.responseText);
		
							document.getElementById('error_email').innerHTML = "";
							document.getElementById('error_firstname').innerHTML = "";
							document.getElementById('error_lastname').innerHTML = "";
							document.getElementById('error_username1').innerHTML = "";
							document.getElementById('error_username2').innerHTML = "";
							document.getElementById('error_password1').innerHTML = "";
							document.getElementById('error_password2').innerHTML = "";
							document.getElementById('error_password3').innerHTML = "";
		
							if(messages.email) {
								document.getElementById('error_email').innerHTML = messages.email;
							}
							if(messages.firstname) {
								document.getElementById('error_firstname').innerHTML = messages.firstname;
							}
							if(messages.lastname) {
								document.getElementById('error_lastname').innerHTML = messages.lastname;
							}
							if(messages.username1) {
								document.getElementById('error_username1').innerHTML = messages.username1;
							}
							if(messages.username2) {
								document.getElementById('error_username2').innerHTML = messages.username2;
							}
							if(messages.password1) {
								document.getElementById('error_password1').innerHTML = messages.password1;
							}
							if(messages.password2) {
								document.getElementById('error_password2').innerHTML = messages.password2;
							}
							if(messages.password3) {
								document.getElementById('error_password3').innerHTML = messages.password3;
							}
						}
					}
					else {
						console.log('Error: ' + httpRequest.status);
					}
				}
			};

			// Set up our request
			httpRequest.open("POST", "app/signup.php");
			httpRequest.setRequestHeader('Content-Type', 'application/json');
			httpRequest.send(json);
		}
})();

document.getElementById('delete').addEventListener('click', function () {
  document.getElementById('modal').classList.remove("is-active");
});


document.addEventListener('click', function (event) {
    if (event.target.classList.contains('picture') ) {
		document.getElementById('modal2').classList.add("is-active");
    }
}, false);

document.getElementById('delete2').addEventListener('click', function () {
	document.getElementById('modal2').classList.remove("is-active");
});
  


