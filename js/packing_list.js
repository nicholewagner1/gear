$(document).ready(function () {
		
		$('#packingListTable').DataTable({
			"pageLength": 30,
			//"searching": false,
			"order": [[0, "DESC"]],
			fixedHeader: true,
			responsive: true
		});
		
		getValues('item');		


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
		
	
	
	getItems();

			
		// Event listener for the change event on the select2 dropdown
		$("#itemNew").on("click", function () {
			var lastRowId = $('#itemList tr:last').attr('id');
			if (!lastRowId) {
				lastRowId = "0";
			}
	var rowId = parseInt(lastRowId) + 1; // Ensure numeric addition
	$("#itemList tbody").append('<tr id="' + rowId + '"><td><input type="text" id="count_' + rowId + '"  class="form-control" value="" name="count_needed"></td><td><select name="item" id="item_' + rowId + '" class="js-multiple-select item form-control" multiple value=""></select></td><td><select id="subcategory_' + rowId + '" class="autocomplete size form-control" data-table="item" multiple value="" name="subcategory" ></select></td></tr>');
	
	getNewRowItems("item_"+rowId);
	getValues('packing_list', 1);
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
					 window.location = "/packing_list.php";
					// alert(data.message);
				},
				error: function () {
					alert("Error creating outfit.");
				}
			});
		});

		});
