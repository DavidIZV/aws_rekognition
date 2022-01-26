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

					var img1 = $('<img class="img-700-max">');
					var urlParts = $('#myimage')[0].src.split('/');
					var url = urlParts[urlParts.length - 1];
					img1.attr('src', "./../originales/" + url);
					var id = url + window.count;
					img1.attr('id', id);
					img1.appendTo('.newImage');

					Jcrop.load(id).then(img => {
						window.jcpCelebrities = Jcrop.attach(img, { multi: true });
						window.jcpCelebrities.setOptions({ shadeOpacity: 0.2 });
						window.jcpCelebrities.focus();
						Jcrop.Rect.sizeOf(window.jcpCelebrities.el);
					});
				}

				data.celebrities.forEach(function(celebrity) {

					setTimeout(function() {

						var t = imageDimensions.height * celebrity.face.top;
						var l = imageDimensions.width * celebrity.face.left;
						var h = imageDimensions.height * celebrity.face.height;
						var w = imageDimensions.width * celebrity.face.width;
						const rect = Jcrop.Rect.create(l, t, w, h);
						const options = {};
						jcpCelebrities.newWidget(rect, options).addClass('celebrity-people');

						var li = jQuery("<li class='w-100'></li>");
						var link = jQuery("<a href='https://" + celebrity.url + "'>" + celebrity.name + "</a>");
						li.append(link)
						ul.append(li);

					}, 1000);

					window.count = window.count + 1;
				});
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	var imageName = window.imgToWork.attr('class').split(" ")[0];

	jQuery(".btn-to-hide-celebrities").click(function() {
		searchCelebrities(window.imageDimensions, imageName);
	});

})();