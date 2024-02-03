$("#addEditItemFormButton").click(function (event) {
	event.preventDefault();

	const formId = '#addEditItemForm';
	const form = $(formId)[0];
	const formData = new FormData(form);			
	const jsonData = {};
	formData.forEach((value, key) => {
		jsonData[key] = value;
	});
	$.ajax({
		type: "POST",
		url: "/api/index.php?action=addEditItem",
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
	localStorage.removeItem('cachedItems');

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
			$("#photo").append(paths.join(', '));
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

