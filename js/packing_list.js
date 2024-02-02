$(document).ready(function () {
		
		getValues();		

function getItems() {
	$(".item").each(function () {
		const previousItems = $(this).attr('value');
		console.log('value', previousItems);
			const id = $(this).attr('id');
		console.log('item',id);
			// Check if data is already cached in localStorage
			const cachedData = localStorage.getItem('cachedItems');
			if (cachedData) {
				const data = JSON.parse(cachedData);
				populateDropdown(id, data);
			} else {
				// If not cached, make the Ajax call
				$.getJSON("/api/?action=list", function (data) {
					// Cache the data in localStorage
					localStorage.setItem('cachedItems', JSON.stringify(data));
		
					// Populate the dropdown
					populateDropdown(id, data);
				}).fail(function (error) {
					console.error("Error:", error);
				});
			}
		//	console.log('daata',data);
			preFillSelector(id, previousItems);
		});
		}
		function getNewRowItems(id) {

			var newRowid = $("#"+id);
			var id = $("#"+id).attr('id');
			
				const cachedData = localStorage.getItem('cachedItems');
				if (cachedData) {
					const data = JSON.parse(cachedData);
					populateDropdown(id, data);
				} else {
					// If not cached, make the Ajax call
					$.getJSON("/api/?action=list", function (data) {
						// Cache the data in localStorage
						localStorage.setItem('cachedItems', JSON.stringify(data));
			
						// Populate the dropdown
						populateDropdown(id, data);
					}).fail(function (error) {
						console.error("Error:", error);
					});
				}
			}
		
	$(".deleteList").click(function (event) {
		var deleteId = $(this).attr('data-value');
		var targetURL = "packing_list.php";
		$.ajax({
			type: "GET",
			url: "/api/index.php?action=deleteList&id="+deleteId,
			contentType: false,
			success: function (response) {
				// Handle the response from the server
			$('#list_'+deleteId).remove();
			
			  // Check if the current URL is different from the target
			  if (window.location.href !== targetURL) {
				// Redirect to the target URL
				window.location.href = targetURL;
			  }
			},
			error: function () {
				alert("Error processing the form.");
			}
		});
	
	});	
	
	function populateDropdown(id, data) {
		console.log('itemId',id);

		const itemSelector = $("#"+id);
		itemSelector.empty();
	
		data.forEach((item) => {
			const option = $("<option>").val(item.id).text(item.name);
			itemSelector.append(option);
		});
	
		itemSelector.select2({
			placeholder: "Select an option or start typing",
			maximumSelectionLength: 30,
			language: {
				maximumSelected: function (args) {
					return "Genre selection limited to 3";
				}
			}
		});
	}
	getItems();

		
		function preFillSelector(selectorId, selectedIds) {
		const $selector = $("#" + selectorId);
		const idArray = selectedIds ? selectedIds.split(",") : [];
		// Clear existing selections and remove existing choices from the rendered list
		$selector.val(null);
		$selector.next('.select2-container').find('.select2-selection__choice').remove();
		// Select the options with the specified IDs
		idArray.forEach(function(id) {
			const $option = $selector.find('option[value="' + id + '"]');
			$option.prop("selected", true);
			// Get the title from the selected option
			const title = $option.text();
			// Append a new choice to the rendered list
			$selector.next('.select2-container').find('.select2-selection__rendered').append(
				$('<li class="select2-selection__choice" title="' + title + '" data-select2-id="' + id + '">' +
					'<span class="select2-selection__choice__remove" role="presentation">×</span>' +
					title +
					'</li>')
			);
		});
		// Trigger change event to update Select2 UI
		$selector.trigger('change');
	}

		function getSelectedItems() {
			return $("#items").val();
		}

		// Function to add selected item images to the "itemImages" div
		function addSelectedImagesToDiv() {
			const selectedItems = getSelectedItems();
		$.getJSON("/api/?action=list&id="+selectedItems, function (data) {
		$("#itemImages").empty();

		data.forEach((item) => {
				const imageSrc = "/images/items/" + item.photo;
				const imageElement = $("<img>").attr("src", imageSrc).addClass("selected-item-image");
				$("#itemImages").append(imageElement);
			});
	});
		}
		
		
		// Event listener for the change event on the select2 dropdown
		$("#itemNew").on("click", function () {
			var lastRowId = $('#itemList tr:last').attr('id');
			if (!lastRowId) {
				lastRowId = "0";
			}
	var rowId = parseInt(lastRowId) + 1; // Ensure numeric addition
	$("#itemList tbody").append('<tr id="' + rowId + '"><td><input type="text" id="count_' + rowId + '"  class="form-control" value="" name="count_needed"></td><td><select name="item" id="item_' + rowId + '" class="js-multiple-select item form-control" multiple value=""></select></td><td><select id="subcategory_' + rowId + '" class="autocomplete size form-control" multiple value="" name="subcategory" ></select></td></tr>');
	
	getNewRowItems("item_"+rowId);
	getValues(1);
		});
		
		$("#packingList").submit(function (event) {
			event.preventDefault();
		
			const jsonData = {				
				id: $('#id').val(), // Assuming the input field has an id 'listName'
				name: $('#name').val(), // Assuming the input field has an id 'listName'
				items: []
			};
			// Loop through each row in the table
			$('#itemList tbody tr').each(function () {
				const row = $(this);
				const itemData = {
					id: row.attr('id'),
					count_needed: row.find('td input[name="count_needed"]').val(),
					item: row.find('td select[name="item"]').val(),
					subcategory: row.find('td select[name="subcategory"]').val()
				};
		
				jsonData.items.push(itemData);
			});
		
			// Send data using AJAX
			$.ajax({
				type: "POST",
				url: "/api/index.php?action=packingList", // replace with your actual API endpoint
				contentType: "application/json", // Set content type to JSON
				data: JSON.stringify(jsonData),
				success: function (data) {
					// Handle the response from the server
					// window.location = "/outfit.php";
					// alert(data.message);
				},
				error: function () {
					alert("Error creating outfit.");
				}
			});
		});

		});
