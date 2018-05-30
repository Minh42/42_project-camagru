(function() {
  var image_height = 0;
  var gallery_offset = 0;
  var image_count = document.getElementsByClassName('thumbnail').length; 
  var click_count = -1;
  var image_height = 0;
  var last_images_count = 0;

  document.addEventListener('click', function (event) {
    if (event.target.classList.contains('thumbnail')) {
      var container = document.querySelector(".gallery-container");
      var elems = container.querySelectorAll("a");
      for (var i = 0; i < elems.length; i++) {
        if (elems[i].classList == "active") {
          elems[i].classList.remove("active");
        }
      }
      event.target.parentElement.classList.add("active");
    }
  }, false);

  var thumbnails = document.getElementsByClassName("thumbnail");
  
  Array.prototype.forEach.call(thumbnails, function(elem) {
      image_height = elem.parentElement.offsetHeight;
  });


// Set the first image as active
var container = document.querySelector(".gallery-container");
var elems = container.querySelectorAll("a");
elems[0].classList.add("active");

})();

