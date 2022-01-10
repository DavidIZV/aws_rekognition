(function() {
	'use strict';

	// 	window.awsjcrop=Jcrop.attach('myimage');
	//   window.awsjcrop.setOptions({ multi: true, shadeColor: 'red', aspectRatio: 1 });

	var jcp;
	Jcrop.load('myimage').then(img => {
		jcp = Jcrop.attach(img, { multi: true });
		const rect = Jcrop.Rect.sizeOf(jcp.el);
		// jcp.newWidget(rect.scale(.7,.5).center(rect.w,rect.h));
		jcp.focus();
	});

	function setImage(tag) {
		document.getElementById('target').src =
			'https://d3o1694hluedf9.cloudfront.net/' + tag;
	}

	function rcoord() {
		const w = jcp.el.offsetWidth;
		const h = jcp.el.offsetHeight;
		return [Math.round(Math.random() * w), Math.round(Math.random() * h)];
	}

	function rrect() {
		return Jcrop.Rect.fromCoords(rcoord(), rcoord());
	}

	function anim() {
		if (!jcp.active) return false;
		const animtype = document.getElementById('animtype').value;
		jcp.active.animate(rrect(), null, animtype);
		jcp.focus();
	}

})();