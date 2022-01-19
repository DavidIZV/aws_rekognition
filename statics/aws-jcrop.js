(function() {
	'use strict';

	function printAws(message) {
		$(".aws-data").html($(".aws-data").html() + "<br><br>" + JSON.stringify(message, null, 2));
	}

	function markBoxes(imageDimensions, imageName) {

		fetch("/pia/upload/logic/bucket-analizar.php?toanalyze=" + imageName, {
			method: 'GET',
		})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				console.log('Request succeeded with JSON response', data);
				printAws(data.faces);

				data.faces.forEach(function(face) {
					var t = imageDimensions.height * face.top;
					var l = imageDimensions.width * face.left;
					var h = imageDimensions.height * face.height;
					var w = imageDimensions.width * face.width;
					const rect = Jcrop.Rect.create(l, t, w, h);
					const options = {
						opacity: 0.7
					};
					jcp.newWidget(rect, options);
				});
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	function blurrImage(imageName) {

		var coordsAndSize = [];

		jcp.crops.forEach(function(value) {
			var position = {};
			position.width = value.pos.w;
			position.height = value.pos.h;
			position.top = value.pos.y;
			position.left = value.pos.x;
			coordsAndSize.push(position);
		});

		console.log('Image to blurr', imageName);

		fetch("/pia/upload/logic/local-blurrer.php", {
			body: JSON.stringify({ imageName: imageName, coords: coordsAndSize }),
			headers: {
				"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			method: 'POST',
		})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				console.log('Request succeeded with JSON response', data);
				printAws(data);
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	let jcp, rect;
	Jcrop.load('myimage').then(img => {
		jcp = Jcrop.attach(img, { multi: true });
		rect = Jcrop.Rect.sizeOf(jcp.el);
		jcp.focus();
	});

	var img = jQuery("#myimage");

	var imageName = img.attr('class').split(" ");

	var imageDimensions = {
		height: img[0].height,
		width: img[0].width
	};

	markBoxes(imageDimensions, imageName[0]);

	jQuery(".btn-to-blurr").click(function() {
		blurrImage(imageName[0]);
	});

})();