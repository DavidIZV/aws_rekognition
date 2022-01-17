(function() {
	'use strict';

	let jcp, rect;
	Jcrop.load('myimage').then(img => {
		jcp = Jcrop.attach(img, { multi: true });
		rect = Jcrop.Rect.sizeOf(jcp.el);
		jcp.focus();
	});

	let img = jQuery("#myimage");

	let imageName = img.attr('class').split(" ");

	fetch("https://informatica.ieszaidinvergeles.org:10056/pia/upload/logic/bucket-analizar.php?toanalyze=" + imageName[0], {
		method: 'GET',
	})
		.then(function(response) {
			return response.json();
		})
		.then(function(data) {
			console.log('Request succeeded with JSON response', data);
			$(".aws-data").text(JSON.stringify(data.faces, null, 2));

			data.faces.forEach(function(face) {
				var t = img[0].height * face.top;
				var l = img[0].width * face.left;
				var h = img[0].height * face.height;
				var w = img[0].width * face.width;
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

})();