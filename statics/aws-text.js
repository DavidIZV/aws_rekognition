(function() {
	'use strict';

	function searchTexts(imageName) {

		fetch("/pia/upload/logic/bucket-text.php?toanalyze=" + imageName, {
			method: 'GET',
		})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				console.log('Request succeeded with JSON response', data);
				window.printAws(data.texts);

				var ul = jQuery(".tags ul");
				if (data.texts.length > 0) {
					ul.find("li").remove();
				}

				data.texts.forEach(function(text) {
					var li = jQuery("<li class='w-100'></li>");
					var textDom = $("<p>" + text.DetectedText + "</p>")
					li.append(textDom)
					ul.append(li);
				});
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	var imageName = window.imgToWork.attr('class').split(" ");

	searchTexts(imageName[0]);

})();