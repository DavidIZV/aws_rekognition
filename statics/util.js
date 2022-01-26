(function() {
	'use strict';

	window.printAws = function(message) {
		$(".aws-data").html(JSON.stringify(message, null, 2) + "<br><br>" + $(".aws-data").html());
	}

	window.imgToWork = jQuery("#myimage");

	window.imageDimensions = {
		height: window.imgToWork[0].height,
		width: window.imgToWork[0].width
	};

	jQuery(".btn-to-blurr-saved").click(function() {
		window.location.pathname = "/pia/upload/view/local-blurred.php";
	});

	window.startLoading = function(message) {
		$(".load").removeClass('none');
	}

	window.endLoading = function(message) {
		$(".load").addClass('none');
	}

})();