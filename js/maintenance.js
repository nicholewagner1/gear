$(document).ready(function () {
	getItems();

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
