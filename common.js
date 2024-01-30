function getValues(inline = 0) {
	
	$(".autocomplete").each(function () {
		var inputName = $(this).attr('name');
		console.log (inputName);
		var currentInput = $('#' + $(this).attr('id'));
		var currentValues = $(this).attr('value');

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


		function getVibesValues() {
	$(".autocomplete").each(function () {
		var inputName = $(this).attr('name');
		var currentInput = $('#' + $(this).attr('id'));

		$.ajax({
			url: 'api/index.php?action=autocompleteVibe', // Replace with your server endpoint
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
