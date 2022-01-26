(function() {
	'use strict';

	function searchFaces(imageDimensions, imageName) {

		window.startLoading();
		fetch("/pia/upload/logic/bucket-analizar.php?toanalyze=" + imageName, {
			method: 'GET',
		})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				window.printAws(data);

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
				window.endLoading();
			})
			.catch(function(error) {
				window.printAws(error);
				window.endLoading();
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

		window.startLoading();
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
				window.printAws(data);

				if (data.ok) {
					var img = $('<img class="img-700-max">');
					img.attr('src', data.new_href);
					img.appendTo('.newImage');
				}
				window.endLoading();
			})
			.catch(function(error) {
				window.printAws(error);
				window.endLoading();
			});
	}

	function deleteJcropActive() {

		jcp.crops.forEach(function(value) {
			value.options.shadeColor = 'red';
		});

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

	var imageName = window.imgToWork.attr('class').split(" ");

	searchFaces(window.imageDimensions, imageName[0]);

	jQuery(".btn-to-blurr").click(function() {
		blurrImage(imageDimensions, imageName[0]);
	});

	jQuery(".btn-to-delete").click(function() {
		deleteJcropActive();
	});

})();