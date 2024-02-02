$(document).ready(function () {
	getMaintenanceValues();
	getItems();

	function getItems() {
		const previousItems = $("#items").attr('value');
		const itemSelector = $("#items");

		const cachedData = localStorage.getItem('cachedItems');

		const populateDropdown = (data) => {
			itemSelector.empty();
			data.forEach((item) => {
				const option = $("<option>").val(item.id).text(item.name).attr('data-value', item.category);
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
			$.getJSON("/api/?action=list", function (data) {
				localStorage.setItem('cachedItems', JSON.stringify(data));
				populateDropdown(data);
			}).fail(function (error) {
				console.error("Error:", error);
			});
		}
		preFillSelector("items", previousItems);
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
		return $("#items").val();
	}

	function addSelectedImagesToDiv() {
		const selectedItems = getSelectedItems();
		$.getJSON("/api/?action=list&id=" + selectedItems, function (data) {
			$("#itemImages").empty();
			data.forEach((item) => {
				const imageSrc = "/images/items/" + item.photo;
				const imageElement = $("<img>").attr("src", imageSrc).addClass("selected-item-image");
				$("#itemImages").append(imageElement);
			});
		});
	}

	$("#items").on("change", function () {
		addSelectedImagesToDiv();
	});

	$("#logMaintenaceForm").submit(function (event) {
		event.preventDefault();
		const form = $('#logMaintenaceForm')[0];
		const formData = new FormData(form);
		const selectedItems = Array.from(formData.getAll('items'));
		const jsonData = { ...Object.fromEntries(formData), items: selectedItems };

		$.ajax({
			type: "POST",
			url: "/api/index.php?action=addMaintenanceLog",
			contentType: "application/json",
			data: JSON.stringify(jsonData),
			success: function (data) {
				window.location = "/maintenance.php";
			},
			error: function () {
				alert("Error logging maintenance");
			}
		});
	});
});
