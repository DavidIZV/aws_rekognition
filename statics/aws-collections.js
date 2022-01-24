(function() {
	'use strict';

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
					var li = jQuery("<li class='w-100'></li>");
					var removeBoton = jQuery("<a title='Remove' onclick='return false' href='#'>x</a>");
					removeBoton.click(function() {
						removeCollection(collection);
					});
					var link = jQuery("<p>" + collection + " - </p>");
					link.append(removeBoton);
					li.append(link)
					ul.append(li);
				});
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
					var li = jQuery("<li class='w-100'></li>");
					var removeBoton = jQuery("<a title='Remove' onclick='return false' href='#'>x</a>");
					removeBoton.click(function() {
						removeCollection(collection);
					});
					var link = jQuery("<p>" + collection + " - </p>");
					link.append(removeBoton);
					li.append(link)
					ul.append(li);
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
					var li = jQuery("<li class='w-100'></li>");
					var removeBoton = jQuery("<a title='Remove' onclick='return false' href='#'>x</a>");
					removeBoton.click(function() {
						removeCollection(collection);
					});
					var link = jQuery("<p>" + collection + " - </p>");
					link.append(removeBoton);
					li.append(link)
					ul.append(li);
				});
			})
			.catch(function(error) {
				console.log('Request failed', error);
			});
	}

	listCollections();

	jQuery(".btn-to-create-collection").click(function() {
		var collectionName = jQuery(".btn-to-write").val();
		createCollection(collectionName);
		jQuery(".btn-to-write").val("")
	});

})();