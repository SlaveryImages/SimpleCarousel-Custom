$(document).ready(function() {
	var elem = document.querySelector('input[type="range"]');
	var images = document.getElementsByClassName("time-slider-image");

	var rangeValue = function(){
		var newValue = elem.value;
		var target = document.querySelector('.image-slider-value');
		var imageIndex;
		target.innerHTML = newValue;

		var minRange = elem.min;

		for (i = 0; i < images.length; i++) {
			images[i].style.display = "none";  
		}

		if (newValue === minRange) {
			imageIndex = 0;
		} else {
			imageIndex = newValue - minRange;
		}

		images[imageIndex].style.display = "block";
		imageMapHandler(images[imageIndex].nextElementSibling);
	}

	function imageMapHandler(imageMap) {
		var coords = [];
		var areas = imageMap.areas;
		var orgImageWidth = 2000;

		if (areas.length === 0) {
			return;
		}

		for (var i = 0; i < areas.length; i++) {
			coords[i] = areas[i].coords.split(',');
		}

		mapResize = function function_name () {
			var curImage = $('img[usemap]:visible')[0];
			var curImageWidth = curImage.width;

			var widthRatio = curImageWidth / orgImageWidth;
			for (var j = 0; j < areas.length; j++) {

				if (areas[j].getAttribute('value') != 0 && areas[j].getAttribute('value') == curImageWidth) {
					continue;
				}

				if (areas[j].getAttribute('value') != 0) {
					widthRatio = curImageWidth / areas.getAttribute('value');
				}

				for (var k = 0; k < coords[j].length; k++) {
					coords[j][k] = widthRatio * coords[j][k];
				}

				areas[j].coords = coords[j].join(',');
				areas[j].setAttribute('value', curImageWidth);
			}

			return true;
		};

		mapResize();
	}


	rangeValue();

	elem.addEventListener("input", rangeValue);
});
