var FILTER_VALS = {};
var el = document.getElementById('preview');
var el_video = document.getElementById('video');
function set(filter, value) {
 FILTER_VALS[filter] = typeof value == 'number' ? Math.round(value * 10) / 10 : value;
 if (value == 0 || (typeof value == 'string' && value.indexOf('0') == 0)) 
{
    delete FILTER_VALS[filter];
}
 render();
}

function render() {
 var vals = [];
 Object.keys(FILTER_VALS).sort().forEach(function(key, i) {
    vals.push(key + '(' + FILTER_VALS[key] + ')');
});
 var val = vals.join(' ');
 el.style.webkitFilter = val;
el_video.style.webkitFilter = val;
}
