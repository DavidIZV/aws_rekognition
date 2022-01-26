(function() {
	'use strict';

	function createCollectionBotton(collection, ul) {

		var li = jQuery("<li class='w-100'></li>");
		var removeBoton = jQuery("<button type='button' class='btn btn-mini btn-to-create-collection'>Remove collection</button>");
		removeBoton.click(function() {
			removeCollection(collection);
		});
		var indexBoton = jQuery("<button type='button' class='btn btn-mini btn-to-index-in-collection'>Index in collection</button>");
		indexBoton.click(function() {
			indexCollection(collection);
		});
		var accurancityBoton = jQuery("<input type='text' class='btn btn-mini btn-to-write' onclick='return false;' value='99.0' />");
		var searchBoton = jQuery("<button type='button' class='btn btn-mini btn-to-search-in-collection'>Search in collection</button>");
		searchBoton.click(function() {
			searchCollection(collection, accurancityBoton.val());
		});
		var link = jQuery("<p class='w-100'>" + collection + "</p>");
		li.append(link)
		li.append(removeBoton);
		li.append(indexBoton);
		li.append(searchBoton);
		li.append(accurancityBoton);
		ul.append(li);
	}

	function listCollections() {

		fetch("/pia/upload/logic/bucket-list-collections.php", {
			method: 'GET',
		})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				console.log('Request succeeded with JSON response', data);
				printAws(data.collections);

				var ul = jQuery(".collections ul");
				if (data.collections.length > 0) {
					ul.find("li").remove();
				}

				data.collections.forEach(function(collection) {
					createCollectionBotton(collection, ul);
				});
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	function indexCollection(collectionName) {

		var imageName = window.imgToWork.attr('class').split(" ")[0];

		fetch("/pia/upload/logic/bucket-index.php?toanalyze=" + imageName + "&collection-to=" + collectionName, {
			method: 'GET',
		})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				console.log('Request succeeded with JSON response', data);
				printAws(data.collections);
				printAws("Contenido indexado con exito");
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	function searchCollection(collectionName, accurancity) {

		var imageName = window.imgToWork.attr('class').split(" ")[0];

		fetch("/pia/upload/logic/bucket-search.php?toanalyze=" + imageName + "&collection-to=" + collectionName + "&accurancity=" + accurancity, {
			method: 'GET',
		})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				console.log('Request succeeded with JSON response', data);
				printAws(data.matches);

				if (data.ok == true && data.urls.length > 0) {

					data.urls.forEach(function(url) {
						var img1 = $('<img class="img-700-max">');
						img1.attr('src', "./../originales/" + url);
						var id = url + window.count;
						img1.attr('id', id);
						img1.appendTo('.newImage');

						Jcrop.load(id).then(img => {
							window.jcpCollections = Jcrop.attach(img, { multi: true });
							window.jcpCollections.setOptions({ shadeOpacity: 0.2 });
							window.jcpCollections.focus();
							Jcrop.Rect.sizeOf(window.jcpCollections.el);
						});

						data.matches.forEach(function(match) {

							if (id.includes(match.Face.ExternalImageId)) {

								setTimeout(function() {

									var img2 = $(document.getElementById(id));

									var t = img2[0].height * match.Face.BoundingBox.Top;
									var l = img2[0].width * match.Face.BoundingBox.Left;
									var h = img2[0].height * match.Face.BoundingBox.Height;
									var w = img2[0].width * match.Face.BoundingBox.Width;

									const rect = Jcrop.Rect.create(l, t, w, h);
									const options = {};
									window.jcpCollections.newWidget(rect, options).addClass('collection-people');

								}, 1000);

								window.count = window.count + 1;
							}
						});
					});
				} else {
					printAws("No hay coincidencias");
				}
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	function removeCollection(collectionName) {

		fetch("/pia/upload/logic/bucket-delete-collection.php?collection-to=" + collectionName, {
			method: 'GET',
		})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				console.log('Request succeeded with JSON response', data);
				printAws(data.collections);

				var ul = jQuery(".collections ul");
				ul.find("li").remove();

				data.collections.forEach(function(collection) {
					createCollectionBotton(collection, ul);
				});
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	function createCollection(collectionName) {

		fetch("/pia/upload/logic/bucket-create-collection.php?collection-to=" + collectionName, {
			method: 'GET',
		})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				console.log('Request succeeded with JSON response', data);
				printAws(data.collections);

				var ul = jQuery(".collections ul");
				ul.find("li").remove();

				data.collections.forEach(function(collection) {
					createCollectionBotton(collection, ul);
				});
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	window.count = 0;

	listCollections();

	jQuery(".btn-to-create-collection").click(function() {
		var collectionName = jQuery(".btn-to-write").val();
		createCollection(collectionName);
		jQuery(".btn-to-write").val("")
	});

})();