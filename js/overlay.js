document.addEventListener("DOMContentLoaded", function(){
    // Handler when the DOM is fully loaded

    var image = document.getElementById('preview2');
    var thumbnail_1 = document.getElementById('thumbnail_1');
    var thumbnail_2 = document.getElementById('thumbnail_2');
    var thumbnail_3 = document.getElementById('thumbnail_3');
    var thumbnail_4 = document.getElementById('thumbnail_4');
    var thumbnail_5 = document.getElementById('thumbnail_5');
    var thumbnail_6 = document.getElementById('thumbnail_6');
    
    var addOverlayImage = function (event) {
        image.removeAttribute('src');
        var src = this.src;
        image.src = src;
    };
    
    // Add our event listeners
    thumbnail_1.addEventListener('click', addOverlayImage, false);
    thumbnail_2.addEventListener('click', addOverlayImage, false);
    thumbnail_3.addEventListener('click', addOverlayImage, false);
    thumbnail_4.addEventListener('click', addOverlayImage, false);
    thumbnail_5.addEventListener('click', addOverlayImage, false);
    thumbnail_6.addEventListener('click', addOverlayImage, false);
});
