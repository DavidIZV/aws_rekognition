(function() {
	'use strict';

	var jcp, rect;
	Jcrop.load('myimage').then(img => {
		jcp = Jcrop.attach(img, { multi: true });
		rect = Jcrop.Rect.sizeOf(jcp.el);
		jcp.focus();
	});

	var img = jQuery("#myimage");

	var imageName = img.attr('class').split(" ");

	$.getJSON("/pia/upload/logic/bucket-analizar.php?toanalyze=" + imageName[0], function(result) {

		$(".aws-data").text(JSON.stringify(result.faces, null, 2));

		result.faces.forEach(function(face) {
			var t = img[0].height * face.top;
			var l = img[0].width * face.left;
			var h = img[0].height * face.height;
			var w = img[0].width * face.width;
			const rect = Jcrop.Rect.create(l, t, w, h);
			const options = {};
			jcp.newWidget(rect, options);
		});

	});

})();