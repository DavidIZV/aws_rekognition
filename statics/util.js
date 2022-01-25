(function() {
	'use strict';

	window.printAws = function(message) {
		$(".aws-data").html($(".aws-data").html() + "<br><br>" + JSON.stringify(message, null, 2));
	}

	window.imgToWork = jQuery("#myimage");

	window.imageDimensions = {
		height: window.imgToWork[0].height,
		width: window.imgToWork[0].width
	};

	jQuery(".btn-to-blurr-saved").click(function() {
		window.location.pathname = "/pia/upload/view/local-blurred.php";
	});

})();