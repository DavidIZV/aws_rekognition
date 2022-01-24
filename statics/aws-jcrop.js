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
					if (face.lowAge < 18) {
						var t = imageDimensions.height * face.top;
						var l = imageDimensions.width * face.left;
						var h = imageDimensions.height * face.height;
						var w = imageDimensions.width * face.width;
						const rect = Jcrop.Rect.create(l, t, w, h);
						const options = {
							shadeOpacity: 0.3
						};
						jcp.newWidget(rect, options);
					}
				});
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	function blurrImage(imageDimensions, imageName) {

		var coordsAndSize = [];

		jcp.crops.forEach(function(value) {
			var position = {};
			position.width = value.pos.w / imageDimensions.width;
			position.height = value.pos.h / imageDimensions.height;
			position.top = value.pos.y / imageDimensions.height;
			position.left = value.pos.x / imageDimensions.width;
			coordsAndSize.push(position);
		});

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

				if (data.ok) {
					var img = $('<img class="img-700-max">');
					img.attr('src', data.new_href);
					img.appendTo('.newImage');
				}
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	function deleteJcropActive() {

		jcp.active.el.remove();
		jcp.removeWidget(jcp.active);
	}

	let jcp;
	Jcrop.load('myimage').then(img => {
		jcp = Jcrop.attach(img, { multi: true });
		jcp.setOptions({ shadeOpacity: 0.2 });
		jcp.focus();
		Jcrop.Rect.sizeOf(jcp.el);
	});

	var img = jQuery("#myimage");

	var imageName = img.attr('class').split(" ");

	var imageDimensions = {
		height: img[0].height,
		width: img[0].width
	};

	markBoxes(imageDimensions, imageName[0]);

	jQuery(".btn-to-blurr").click(function() {
		blurrImage(imageDimensions, imageName[0]);
	});

	jQuery(".btn-to-delete").click(function() {
		deleteJcropActive();
	});

})();