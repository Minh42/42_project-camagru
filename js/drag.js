var img_ele = null,
x_cursor = 0,
y_cursor = 0,
x_img_ele = 0,
y_img_ele = 0;

function zoomOut(zoomFactor) {
  img_ele = document.getElementById('preview2');
  pre_width = img_ele.clientWidth;
  pre_height = img_ele.clientHeight;
  img_ele.style.width = (pre_width * zoomFactor) + 'px';
  img_ele.style.height = (pre_height * zoomFactor) + 'px';
  img_ele = null;
}

function zoomIn(zoomFactor) {
  img_ele = document.getElementById('preview2');
  pre_width = img_ele.clientWidth;
  pre_height = img_ele.clientHeight;
  if (pre_width >= 506 && pre_height >= 380) { 
    img_ele.style.width = 506 + 'px';
    img_ele.style.height = 380 + 'px';
  }
  else {
    img_ele.style.width = (pre_width * zoomFactor) + 'px';
    img_ele.style.height = (pre_height * zoomFactor) + 'px';
  }
  img_ele = null;
}

document.getElementById('zoomout').addEventListener('click', function() {
  zoomOut(0.8);
});
document.getElementById('zoomin').addEventListener('click', function() {
  zoomIn(1.2);
});

function start_drag(e) {
  var event = e || window.event;
  img_ele = this;
  x_img_ele = event.clientX - document.getElementById('preview2').offsetLeft;
  y_img_ele = event.clientY - document.getElementById('preview2').offsetTop;
}

function stop_drag() {
  img_ele = null;
}

function while_drag(e) {
  var event = e || window.event;
  var x_cursor = event.clientX;
  var y_cursor = event.clientY;
  if (img_ele !== null) {
    img_ele.style.left = (x_cursor - x_img_ele) + 'px';
    img_ele.style.top = (event.clientY - y_img_ele) + 'px';
  }
}

document.getElementById('preview2').addEventListener('mousedown', start_drag);
document.getElementById('image_container').addEventListener('mousemove', while_drag);
document.getElementById('image_container').addEventListener('mouseup', stop_drag);