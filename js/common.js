function getValues(inline = 0) {
	
	$(".autocomplete").each(function () {
		
		var inputName = $(this).attr('name');
		var dataTable = $(this).attr('data-table');
		var returnValue = $(this).attr('data-return');
		var currentInput = $('#' + $(this).attr('id'));
		var currentValues = $(this).attr('value');
		$.ajax({
			url: 'api/index.php?action=autocomplete&table='+dataTable, // Replace with your server endpoint
			method: 'GET',
			data: { filter: inputName },
			success: function (data) {

		
			if (returnValue === 'id') {
				data.forEach((suggestion) => {
					const option = $("<option>").val(suggestion.value).text(suggestion.name);
					currentInput.append(option);
				});
			}
			else {
				data.forEach((suggestion) => {
					const option = $("<option>").val(suggestion.value).text(suggestion.value);
					currentInput.append(option);
				});
			}
				// Initialize Select2 after appending options
				currentInput.select2({
					placeholder: "Select an option or start typing",
					tags: true, 
					maximumSelectionLength:1,
				});

				// Pre-select the value that matches the original value of the select
				if (currentValues !== '' && currentValues !== 'edit' && currentValues != '' && inline == 0) { currentInput.val(currentValues).trigger('change'); }
			},
			error: function (error) {
				console.error("Error:", error);
			}
		});

	});
}

function getItems() {
$(".autocompleteItem").each(function () {
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
	
	$(document).ready(function () {

$( '#pageBody' ).on( 'click', '.editable-text', function () {
	// Get the current field value
	var fieldValue = $(this).text().trim();
	var dataId = $(this).data('itemid');
	var dataTable = $(this).data('table');
	var dataField = $(this).data('field');
	var dataIdField = $(this).data('id-field');
	var currentValues = $(this).attr('value');
	// Create an input field for editing
	var inputField = $('<input>')
		.attr('type', 'text')
		.val(fieldValue);

	inputField.attr({
	'data-itemid': dataId,
	'data-field': dataField,
	'data-table': dataTable,
	'name': dataField,
	'data-id-field': dataIdField,
	'id': dataField +'_'+dataId,
		});
	// Replace the span with the input
	$(this).replaceWith(inputField);

	// Focus on the input field
	inputField.focus();

	// Handle saving the new data
	inputField.blur(function () {
		var newItemValue = inputField.val().trim();
		var itemId = $(this).data('itemid');
		var field = $(this).data('field');

	var jsonData = {
	id: itemId,
	table: dataTable,
	id_field: dataIdField,
	filter: dataField,
	value: newItemValue
	};

		// AJAX request to update the item
		$.ajax({
			type: 'POST',
			url: '/api/index.php?action=updateField', // Adjust the endpoint
			data: JSON.stringify(jsonData),
			success: function (response) {
				// Update the UI with the new value
				inputField.replaceWith('<span class="editable-text" data-table="'+dataTable+'" data-id-field="'+dataIdField+'" data-field="' + field + '" data-itemid="' + itemId + '">' + newItemValue + '</span>');
			},
			error: function () {
				console.error('Error updating item.');
			}
		});
	});
});

$( '#pageBody' ).on( 'click', '.editable-select', function () {
	var fieldValue = $(this).text().trim();
	var dataId = $(this).data('itemid');
	var dataTable = $(this).data('table');
	var dataField = $(this).data('field');
	var dataIdField = $(this).data('id-field');
	var currentValues = $(this).attr('value');
	// Create an input field for editing
	var inputField = $('<select multiple>').val(fieldValue);
	$(this).parent().addClass('active');
	inputField.attr({
		'data-itemid': dataId,
		'data-field': dataField,
		'data-table': dataTable,
		'name': dataField,
		'data-id-field': dataIdField,
		'id': dataField +'_'+dataId,
		'class': 'autocomplete form-control js-multiple-select',
		'value':fieldValue,
		});
		console.log('fieldValue',fieldValue);
	$(this).replaceWith(inputField);
	
	inputField.focus();
	getValues(1);
	inputField.change(function () {
		var field = dataField +'_'+dataId;
		var newItemValue = $('#'+dataField +'_'+dataId).val().join(', ');

		var itemId = dataId;


	var jsonData = {
		id: itemId,
		table: dataTable,
		id_field: dataIdField,
		filter: dataField,
		value: newItemValue
		};
if (newItemValue !== '' && newItemValue !== 'edit') {
		// AJAX request to update the item
		$.ajax({
			type: 'POST',
			url: '/api/index.php?action=updateField', // Adjust the endpoint
			data: JSON.stringify(jsonData),
			success: function (response) {
				// Update the UI with the new value
				//$('#'+field).select2('remove');
				//$('td#'+field).empty();
				$('#cell_'+field).html('<span class="editable-select" data-table="'+dataTable+'" data-field="' + dataField + '" data-id-field="'+dataIdField+'" data-itemid="' + itemId + '">' + newItemValue + '</span>');
				$('#cell_'+field).parent().removeAttr('data-select2-id');
				$('#cell_'+field).parent().removeClass('active');
				newItemValue = '';

			},
			error: function () {
				console.error('Error updating item.');
			}
		});
	}
	});
});

$( '#pageBody' ).on( 'click', '.editable-image', function () {
	// Get the current field value
	var dataId = $(this).data('itemid');
	var dataField = $(this).data('field');
	// Create an input field for editing
	var inputField = $('<input>')
		.attr('type', 'file');

	inputField.attr({
		'data-itemid': dataId,
		'data-field': dataField,
		'class': 'form-control-file',
		'accept':'image/*'
		});
	// Replace the span with the input
	$(this).replaceWith(inputField);

	// Focus on the input field
	inputField.focus();

	// Handle saving the new data
	inputField.change(function () {
		var newItemValue = inputField.val().trim();
		var itemId = $(this).data('itemid');
		var field = $(this).data('field');

	var formData = new FormData();
	
		// Append the file to FormData
		var fileInput = $(inputField)[0];
		var file = fileInput.files[0];
		formData.append('photo', file);
	
		$.ajax({
			type: "POST",
			url: "/api/index.php?action=uploadPhoto",
			contentType: false,
			processData: false,
			data: formData,
			success: function (response) {
		
				$('#cell_'+field+'_'+itemId).html('<span class="editable-image" data-field="' + dataField + '" data-itemid="' + itemId + '"><img src="/images/items/'+ response.photo +'" width=100 height=100></span>');
				console.log ('photo changed'+response.photo);

				var jsonData = {
				id: itemId,
				filter: dataField,
				value: response.photo
				};
				$.ajax({
					type: 'POST',
					url: '/api/index.php?action=updateItem', // Adjust the endpoint
					data: JSON.stringify(jsonData),
					success: function (response) {
										
					},
					error: function () {
						console.error('Error updating item.');
					}
				});
			},
			error: function () {
				alert("Error processing the form.");
			}
		});
	});
});


$(".toggleUpdate").click(function (event) {
	var clickedElement = $(this);

	var table = clickedElement.data('table');
	var filter = clickedElement.data('filter');
	var iDfield = clickedElement.data('id-field');
	var updateId = clickedElement.data('id');
	var set = parseInt(clickedElement.data('toggle-value'), 10);

	var setValue = (set === 0) ? 1 : (set === 1) ? 0 : '';

	$.ajax({
		type: "GET",
		url: "/api/index.php?action=updateField&value=" + setValue + "&id_field=" + iDfield + "&id=" + updateId + "&filter=" + filter + "&table=" + table,
		contentType: false,
		success: function (response) {
			console.log(response.message);

			// Toggle the icon and update the data attribute
			if (setValue === 1) {
				clickedElement.html('<i class="fa-solid text-success fa-circle-check"></i>');
				clickedElement.data('toggle-value', '1');
			} else {
				clickedElement.html('<i class="fa-solid text-warning fa-circle-xmark"></i>');
				clickedElement.data('toggle-value', '0');
			}
		},
		error: function () {
			alert("Error processing the form.");
		}
	});
});

$(".delete").click(function (event) {
	// Store the reference to the clicked element
	var clickedElement = $(this);

	// Trigger a confirmation popup
	var confirmed = window.confirm("Are you sure you want to delete?");

	// Check if the user confirmed
	if (!confirmed) {
		return;
	}
	var deleteTable = clickedElement.attr('data-table');
	var deleteIDField = clickedElement.attr('data-field');
	var deleteId = clickedElement.attr('data-value');
	var target = clickedElement.attr('data-remove');

	$.ajax({
		type: "GET",
		url: "/api/index.php?action=delete&" + deleteIDField + "=" + deleteId + "&filter=" + deleteIDField + "&table=" + deleteTable,
		contentType: false,
		success: function (response) {
			console.log('deleted');
			if (window.location.search.includes("action=edit")) {
					// Reload the window without the "action=edit" query string
					var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
					window.location.replace(newUrl);
				} else {
					$(target).remove(); // 
				}
			},
		error: function () {
			alert("Error processing the form.");
		}
	});


});

getValues();
});