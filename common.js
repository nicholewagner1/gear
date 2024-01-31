function getValues(inline = 0) {
	
	$(".autocomplete").each(function () {
		var inputName = $(this).attr('name');
		console.log (inputName);
		var currentInput = $('#' + $(this).attr('id'));
		var currentValues = $(this).attr('value');
		console.log (currentInput);
		console.log (currentValues);

		$.ajax({
			url: 'api/index.php?action=autocomplete', // Replace with your server endpoint
			method: 'GET',
			data: { name: inputName },
			success: function (data) {


				data.forEach((suggestion) => {
					const option = $("<option>").val(suggestion.value).text(suggestion.value);
					currentInput.append(option);
				});
				
				// Initialize Select2 after appending options
				currentInput.select2({
					placeholder: "Select an option or start typing",
					tags: true, 
					maximumSelectionLength:1,
				});

				// Pre-select the value that matches the original value of the select
				if (currentValues !== '' && currentValues !== 'edit' && inline == 0) { currentInput.val(currentValues).trigger('change'); }
			},
			error: function (error) {
				console.error("Error:", error);
			}
		});

	});
}



		function getMaintenanceValues() {
	$(".autocompleteMaintenance").each(function () {
		var inputName = $(this).attr('name');
		var currentInput = $('#' + $(this).attr('id'));

		$.ajax({
			url: 'api/index.php?action=getMaintenanceValues', // Replace with your server endpoint
			method: 'GET',
			data: { name: inputName },
			success: function (data) {
				// Clear existing options
				currentInput.empty();

				// Add a default option for user input
				const defaultOption = $("<option>").val('').text('Type to search or add');
				currentInput.append(defaultOption);

				data.forEach((suggestion) => {
					const option = $("<option>").val(suggestion.value).text(suggestion.value);
					currentInput.append(option);
				});

				// Initialize Select2 after appending options
				currentInput.select2({
					placeholder: "Select an option or start typing",
					tags: true, // Allow the user to add new options
				});

				// Pre-select the value that matches the original value of the select
				currentInput.val(currentInput.attr('value')).trigger('change');
			},
			error: function (error) {
				console.error("Error:", error);
			}
		});
	});
}

	$(document).ready(function () {

$( '#pageBody' ).on( 'click', '.editable-text', function () {
	// Get the current field value
	var dataId = $(this).data('itemid');
	var dataField = $(this).data('field');
	var fieldValue = $(this).text().trim();
	// Create an input field for editing
	var inputField = $('<input>')
		.attr('type', 'text')
		.val(fieldValue);

	inputField.attr({
		'data-itemid': dataId,
		'data-field': dataField
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
		filter: field,
		value: newItemValue
		};

		// AJAX request to update the item
		$.ajax({
			type: 'POST',
			url: '/api/index.php?action=updateItem', // Adjust the endpoint
			data: JSON.stringify(jsonData),
			success: function (response) {
				// Update the UI with the new value
				inputField.replaceWith('<span class="editable-text" data-field="' + field + '" data-itemid="' + itemId + '">' + newItemValue + '</span>');
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
	var dataField = $(this).data('field');
	var currentValues = $(this).attr('value');
	// Create an input field for editing
	var inputField = $('<select multiple>').val(fieldValue);
	$(this).parent().addClass('active');
	inputField.attr({
		'data-itemid': dataId,
		'data-field': dataField,
		'name': dataField,
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
		filter: dataField,
		value: newItemValue
		};
if (newItemValue !== '' && newItemValue !== 'edit') {
		// AJAX request to update the item
		$.ajax({
			type: 'POST',
			url: '/api/index.php?action=updateItem', // Adjust the endpoint
			data: JSON.stringify(jsonData),
			success: function (response) {
				// Update the UI with the new value
				//$('#'+field).select2('remove');
				//$('td#'+field).empty();
				$('#cell_'+field).html('<span class="editable-select" data-field="' + dataField + '" data-itemid="' + itemId + '">' + newItemValue + '</span>');
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

});