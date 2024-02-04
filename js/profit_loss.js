$('#profitLossTable').DataTable({
	"pageLength": 30,
	//"searching": false,
	"order": [[0, "DESC"]],
	fixedHeader: true,
	responsive: true
});

function getVenues() {
	const previousItems = $("#venue_id").attr('value');
	console.log(previousItems);
	const itemSelector = $("#venue_id");

	const cachedData = localStorage.getItem('cachedVenues');

	const populateDropdown = (data) => {
		itemSelector.empty();
		data.forEach((item) => {
			const option = $("<option>").val(item.venue_id).text(item.name);
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
	};

	if (cachedData) {
		populateDropdown(JSON.parse(cachedData));
	} else {
		$.getJSON("/api/?action=returnVenues&autocomplete=1", function (data) {
			localStorage.setItem('cachedVenues', JSON.stringify(data));
			populateDropdown(data);
		}).fail(function (error) {
			console.error("Error:", error);
		});
	}
	preFillSelector("venue_id", previousItems);
}

function preFillSelector(selectorId, selectedIds) {
	const $selector = $("#" + selectorId);
	const idArray = selectedIds ? selectedIds.split(",") : [];
	$selector.val(null);
	$selector.next('.select2-container').find('.select2-selection__choice').remove();

	idArray.forEach(function (id) {
		const $option = $selector.find('option[value="' + id + '"]');
		$option.prop("selected", true);
		const title = $option.text();
		$selector.next('.select2-container').find('.select2-selection__rendered').append(
			$('<li class="select2-selection__choice" title="' + title + '" data-select2-id="' + id + '">' +
				'<span class="select2-selection__choice__remove" role="presentation">×</span>' +
				title +
				'</li>')
		);
	});
	$selector.trigger('change');
}

function getSelectedItems() {
	return $("#venue_id").val();
}	

function getPLValues() {
	$(".autocompletePL").each(function () {
		var inputName = $(this).attr('name');
		var currentInput = $('#' + $(this).attr('id'));

		$.ajax({
			url: 'api/index.php?action=returnProfitLossAutocompleteData&filter='+inputName, // Replace with your server endpoint
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

	



$("#profitLossSubmit").click(function (event) {

	const formId = '#profitLossForm';
	const form = $(formId)[0];
	const formData = new FormData(form);			
	const jsonData = {};
	formData.forEach((value, key) => {
		jsonData[key] = value;
	});
	$.ajax({
		type: "POST",
		url: "/api/index.php?action=upsertProfitLossData",
		contentType: false,
		processData: false,
		data: JSON.stringify(jsonData),
		success: function (response) {
		alert("item update success");
		},
		error: function () {
			alert("Error processing the form.");
		}
	});

});

$("#deleteItem").click(function (event) {
	var deleteId = $(this).attr('data-value');
	
	$.ajax({
		type: "GET",
		url: "/api/index.php?action=deleteItem&id="+deleteId,
		contentType: false,
		success: function (response) {
		window.location = "/index.php";
		},
		error: function () {
			alert("Error processing the form.");
		}
	});

});

$(document).on('click', '.checkInStatus', function (event) {
	var id = $(this).attr('data-item-id');
	var set = parseInt($(this).attr('data-item-status'), 10);
	var setValue = (set === 0) ? 1 : (set === 1) ? 0 : '';
	setCheckInStatus(id, setValue);
});

function setCheckInStatus(id, setValue) {
	$.ajax({
		type: "GET",
		url: "/api/index.php?action=checkIn&id=" + id + "&check_in=" + setValue,
		success: function (response) {
			console.log(response.message);
			if (setValue === 1) {
				$(".checkInStatus").html('<i class="fa-solid text-success fa-house-circle-check"></i>');
			}
			if (setValue === 0) {
				$(".checkInStatus").html('<i class="fa-solid text-warning fa-house-circle-xmark"></i>');
			}
		},
		error: function () {
			alert("Error processing the form.");
		}
	});
}


function setImageType(id, url, set, type) {
	$.ajax({
		type: "GET",
		url: "/api/index.php?action=setImageType&id=" + id + "&url=" + url + "&set=" + set + "&type=" + type,
		success: function (response) {
			$("#" + type + "_" + id).children().toggleClass('text-primary text-secondary');
		},
		error: function () {
			alert("Error processing the form.");
		}
	});
}

$(document).on('click', '.thumbnail, .serial', function (event) {
	var id = $(this).attr('data-item-id');
	var url = $(this).attr('data-url');
	var set = parseInt($(this).attr('data-set'), 10);
	var setValue = (set === 0) ? 1 : (set === 1) ? 0 : '';
	console.log('setValue', setValue);
	var type = ($(this).hasClass('thumbnail')) ? 'thumbnail' : 'serial';
	setImageType(id, url, setValue, type);
});

function handleFileUpload(inputSelector, fieldType) {
	var formData = new FormData();
	var fileInput = $(inputSelector)[0];
	var files = fileInput.files;

	for (var i = 0; i < files.length; i++) {
		formData.append('images[]', files[i]);
	}

	formData.append('imageType', fieldType);

	$.ajax({
		type: "POST",
		url: "/api/index.php?action=uploadPhoto",
		contentType: false,
		processData: false,
		data: formData,
		success: function (response) {
			var paths = response.images;
			console.log(response.imageType);
			$("#"+response.imageType).append(paths.join(', '));
			$("#"+response.imageType).val($("#"+response.imageType).text());
			$(inputSelector).after('<i class="fa-solid fa-upload"></i>');
		},
		error: function () {
			alert("Error processing the form.");
		}
	});
}

$("#photoUpload").change(function (event) {
	handleFileUpload("#photoUpload", "photo");
});

$("#documentUpload").change(function (event) {
	handleFileUpload("#documentUpload", "document");
});

