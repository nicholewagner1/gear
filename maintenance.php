<?php	
$id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? 'view';
$title = '- Outfit - '.$action ;

require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$id = $_GET['id'] ?? '';
use Client\ApiClient;
$apiClient = new Client\ApiClient($apiBaseUrl);

if ($id != '' && $action == 'edit' || $action == 'view') {
	$parameters = ['action' => 'maintenanceLog'];
	$parameters['id'] = $id;
	}
//	var_dump($parameters);
	//echo $id;
$items = $apiClient->get('', $parameters);
//var_dump($items);
?>

<?php if ($action == 'add' || $action == 'edit') { ?>
<h2>Log Maintenance</h2>

<form id="logMaintenaceForm">
	<input type="text" name="id" hidden value="<?php echo $items[0]['id']; ?>"><br>

	<label for="date">Date:</label>
	<input type="date" name="date" required value="<?php echo $items[0]['date'] ; ?>"><br>
	<label for="date">Service:</label>
	<select id="service" class="js-multiple-select service autocomplete form-control" type="text" name="service" value="<?php echo $items[0]['service'] ; ?>"></select><br>

	<div class="row">
		 <div class="col">
			 <label for="item">Item</label>
			<select id="item" class="js-multiple-select item form-control" multiple="multiple" name="item" value="<?php echo $items[0]['item']; ?>">
			</select>
	<div id="itemImages"></div>


		</div>
		<div class="row">
			<div class="col">
			<label for="notes">Notes</label>	<input type="text" id="notes" class="form-control notes" name="notes">
			
			<label for="cost">Cost</label>	<input type="text" id="cost" class="form-control cost" name="cost">
			</div>
	  </div>

	<input type="submit" value="Log Maintenance">
</form>

<!-- Include script for processing form with jQuery -->
<script>
	$(document).ready(function () {
		

		getVibesValues();
		

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
		
		$("#createOutfitForm").submit(function (event) {
			event.preventDefault();
			const form = $('#createOutfitForm')[0]; // Correct the selector
		
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
					window.location = "/outfit.php";

					//alert(data.message);
				},
				error: function () {
					alert("Error creating outfit.");
				}
			});
		});
		});

</script>
<?php 
}
else { 
	?> <h2>Outfits</h2>
	
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
					 ?>
				 <br> <a href="/maintenance.php?action=edit&id=<?php echo $item['id']; ?>"><i class="fa-solid fa-pencil"></i></a></td>
				

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
	
	<!-- Initialize DataTable -->
	<script>
		$(document).ready(function () {
   	 	$('#itemTable').DataTable({
			"order": [[0, "desc"]],  // Set the default sorting to the first column in ascending order
		});		
	});
	</script>

	
<?php }
include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
 ?>