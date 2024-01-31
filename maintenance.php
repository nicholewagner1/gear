<?php
$id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? 'view';
$title = '- Outfit - '.$action ;

require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$id = $_GET['id'] ?? '';
use Client\ApiClient;

$apiClient = new Client\ApiClient($apiBaseUrl, $apiCache);


    $parameters = ['action' => 'maintenanceLog'];
  
$items = $apiClient->get('', $parameters);

?>
<div class="row">
	<div class="col">
<h2>Log Maintenance</h2>

<form id="logMaintenaceForm">
	<input type="text" name="id" hidden value=""><br>

	<label for="date">Date:</label>
	<input type="date" name="date" required value=""><br>
	<label for="date">Service:</label>
	<select id="service" class="js-multiple-select service autocompleteMaintenance form-control" type="text" name="service" value=""></select><br>

	<div class="row">
		 <div class="col">
			 <label for="item">Item</label>
			<select id="items" class="js-multiple-select autocomplete item form-control" multiple="multiple" name="name" value="">
			</select>
	<div id="itemImages"></div>


		</div>
		<div class="row">
			<div class="col">
			<label for="notes">Notes</label>	<input type="text" id="notes" class="form-control notes" name="notes">
			
			<label for="cost">Cost</label>	<input type="text" id="cost" class="form-control cost" name="cost">
			</div>
	  </div>

	<input class="btn btn-primary "  type="submit" value="Log Maintenance">
</form>
	</div></div>
<!-- Include script for processing form with jQuery -->
<script>
	$(document).ready(function () {
		

		getMaintenanceValues();
function getItems() {
		const previousItems = $("#items").attr('value');
		
			const itemSelector = $("#items");
		
			// Check if data is already cached in localStorage
			const cachedData = localStorage.getItem('cachedItems');
			if (cachedData) {
				const data = JSON.parse(cachedData);
				populateDropdown(data);
			} else {
				// If not cached, make the Ajax call
				$.getJSON("/api/?action=list", function (data) {
					// Cache the data in localStorage
					localStorage.setItem('cachedItems', JSON.stringify(data));
		
					// Populate the dropdown
					populateDropdown(data);
				}).fail(function (error) {
					console.error("Error:", error);
				});
			}
			preFillSelector("items", previousItems);
		
		}
	function populateDropdown(data) {
		const itemSelector = $("#items");
		itemSelector.empty();
	
		data.forEach((item) => {
			const option = $("<option>").val(item.id).text(item.name).attr('data-value',item.category);
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

		function getSelectedItems() {
			return $("#items").val();
		}
	
getItems();
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
		$("#items").on("change", function () {
			// Call the function to add selected item images to the div
			addSelectedImagesToDiv();
		});


		$("#logMaintenaceForm").submit(function (event) {
			event.preventDefault();
			const form = $('#logMaintenaceForm')[0]; // Correct the selector
		
			const formData = new FormData(form);
			// Serialize form data
			const selectedItems = Array.from(formData.getAll('items'));
			const jsonData = {};
			formData.forEach((value, key) => {
				jsonData[key] = value;
			});
			jsonData['items'] = selectedItems;
		
			// Send data using AJAX
			$.ajax({
				type: "POST",
				url: "/api/index.php?action=addMaintenanceLog", // replace with your actual API endpoint
				contentType: "application/json", // Set content type to JSON
				data: JSON.stringify(jsonData),
				success: function (data) {
					// Handle the response from the server
					window.location = "/maintenance.php";

					//alert(data.message);
				},
				error: function () {
					alert("Error logging maintenance");
				}
			});
		});
		});

</script>
<div class="row mt-5">
	<div class="col">
 <h2>Maintenance Log</h2>
	
	<table id="itemTable">
		<thead>
			<tr>
				<th>Date</th>
				<th>Service</th>
				<th>Item</th>
				<th>Notes</th>
				<th>Cost</th>

			</tr>
		</thead>
		<tbody>
			
<?php

             foreach ($items as $item):

                 ?>
				<tr>
					<td class="center"><?php $date = strtotime($item['date']);
                 echo date('Y-m-d <br> D', $date);
                 ?></td>
				

					<td class="center"><?php echo $item['service']; ?></td>
 				   <td>
										<?php echo $item['item']; ?>
					</td>
					 <td>
										<?php echo $item['notes']; ?>
					</td>
					 <td>
										<?php echo $item['cost']; ?>
					</td>
			<?php endforeach; ?>
		</tbody>
	</table>
	</div></div>
	<!-- Initialize DataTable -->
	<script>
		$(document).ready(function () {
   	 	$('#itemTable').DataTable({
			"order": [[0, "desc"]],  // Set the default sorting to the first column in ascending order
		});		
	});
	</script>

	
<?php
include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
?>