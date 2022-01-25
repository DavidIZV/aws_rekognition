(function() {
	'use strict';

	function searchCelebrities(imageDimensions, imageName) {

		fetch("/pia/upload/logic/bucket-search-celebrity.php?toanalyze=" + imageName, {
			method: 'GET',
		})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				console.log('Request succeeded with JSON response', data);
				printAws(data.celebrities);

				var ul = jQuery(".celebrities ul");
				if (data.celebrities.length > 0) {
					ul.find("li").remove();
				}

				data.celebrities.forEach(function(celebrity) {
					var t = imageDimensions.height * celebrity.face.top;
					var l = imageDimensions.width * celebrity.face.left;
					var h = imageDimensions.height * celebrity.face.height;
					var w = imageDimensions.width * celebrity.face.width;
					const rect = Jcrop.Rect.create(l, t, w, h);
					const options = {
						shadeOpacity: 0.00001
					};
					jcpCelebrities.newWidget(rect, options).addClass('celebrity-people');

					var li = jQuery("<li class='w-100'></li>");
					var link = jQuery("<a href='https://" + celebrity.url + "'>" + celebrity.name + "</a>");
					li.append(link)
					ul.append(li);
				});
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	function hideCelebrities() {

		jcpCelebrities.crops.forEach(function(value) {
			if (value.hasClass('none')) {
				value.removeClass('none');
			} else {
				value.addClass('none');
			}
		});
	}

	let jcpCelebrities;
	Jcrop.load('myimage').then(img => {
		jcpCelebrities = Jcrop.attach(img, { multi: true });
		jcpCelebrities.setOptions({ shadeOpacity: 0.00001 });
		jcpCelebrities.focus();
		Jcrop.Rect.sizeOf(jcpCelebrities.el);
	});

	var imageName = window.imgToWork.attr('class').split(" ")[0];

	searchCelebrities(window.imageDimensions, imageName);

	jQuery(".btn-to-hide-celebrities").click(function() {
		hideCelebrities();
	});

})();