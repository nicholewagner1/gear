<?php
$action = $_GET['action'] ?? '';
$title = '- Item - '.$action ;
require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$id = $_GET['id'] ?? '';
use Client\ApiClient;
$apiClient = new Client\ApiClient($apiBaseUrl, $apiCache);

$parameters = ['action' => 'list', 'id'=> $id];
$imageParameters = ['action' => 'images', 'id'=> $id];
$maintenanceParameters = ['action' => 'maintenanceLog', 'id'=> $id];
$images = $apiClient->get('', $imageParameters);
// Make the API request and get the JSON response
if ($id !='' ) { $items = $apiClient->get('', $parameters);
$maintenance = $apiClient->get('', $maintenanceParameters); 
}
if ($action == 'add' || $action == 'edit') { ?>

		<h2>Add/Edit Item</h2>

		<form id="addEditItemForm" method="post">
			<div class="form-group">
			<div class="row">
			<div class="col">
			<input type="text" hidden id="id" class="" name="id" value="<?php echo $items[0]['id'] ?? ''; ?>">

			<label for="date">Date Acquired:</label>
			<input type="date" class="form-control" name="date_acquired" value="<?php echo $items[0]['date_acquired'] ?? date('Y-m-d'); ?>" >
			</div>
</div>
</div>
			<div class="form-group">
<div class="row mt-3">
	<div class="col">
	
		
<div class="row mb-4">
				<?php 
				$imageURLs = '';
echo '<div class="col">';
				if ($id != '' && $images) {
					foreach ($images as $image){
						if ($image['type'] == 'photo') {
							echo "<div class='card w-25 float-left'> <div class='card-body'>";

					echo "<a href='/images/items/".$image['url']."' data-toggle='lightbox'> <img src='/images/items/".$image['url']."' width=250></a>";
					echo "  <div class='card-footer'><div class='row'>";
					if ($image['thumbnail'] == '1') {
						echo "<div class='col thumbnail' id='thumb_".$image['item_id']."' data-url='".$image['url']."' data-set='".$image['thumbnail']."'  data-item-id='".$image['item_id']."'><i class='fa-solid fa-thumbtack text-primary'></i></div>";
					}
					elseif ($image['serial'] == '1') {
						echo "<div class='col serial' id='serial_".$image['item_id']."' data-url='".$image['url']."' data-set='".$image['serial']."' data-item-id='".$image['item_id']."'><i class='fa-solid fa-key text-primary'></i></div>";
					}
					else {	
						echo "<div class='col thumbnail' id='thumb_".$image['item_id']."' data-url='".$image['url']."' data-item-id='".$image['item_id']."' data-set=".$image['thumbnail']." ><i class='fa-solid fa-thumbtack text-secondary'></i></div>";
						echo "<div class='serial col' id='serial_".$image['item_id']."' data-url='".$image['url']."' data-set='".$image['serial']."'  data-item-id='".$image['item_id']."'><i class='fa-solid fa-key text-secondary'></i></div>";
					}
					$imageURLs .= $image['url']. ',';
					
echo "</div></div></div></div>"; //end card
}
						}
					}
					echo '<input type="text" id="photo" hidden class="custom-file-input" name="photo" value="'.$imageURLs.'">';
					echo '</div>';
				?>
	<label for="photoUpload">Photos:</label>
				
				<input type="file" id ="photoUpload" class="form-control-file" name="photoUpload" multiple="multiple" accept="image/*">
			</div>
</div></div>
<div class="row">
				<div class="col">
				<label for="status">Status:</label>
				<select id="status" class="js-multiple-select autocomplete status form-control" multiple value="<?php echo $items[0]['status'] ?? ''; ?>" name="status" ></select>
					</div>
					<div class="col">

				<label for="year">Year:</label>
 <input type="text" id="year" class="form-control" name="year" value="<?php echo $items[0]['year'] ?? ''; ?>"></div>
				<div class="col">

				<label for="serial_number">Serial Number:</label>
 <input type="text" id="serial_number" class="form-control" name="serial_number" value="<?php echo $items[0]['serial_number'] ?? ''; ?>"></div>
				<div class="col">

				<label for="asset_tag">Asset Tag:</label>
 <input type="text" id="asset_tag" class="form-control" name="asset_tag" value="<?php echo $items[0]['asset_tag'] ?? ''; ?>"></div>
				

</div>
</div>
			
			<div class="form-group">

			<label for="name">Item Name:</label>
			<input class="form-control" type="text" value="<?php echo $items[0]['name'] ?? ''; ?>" name="name" required>			
			</div>
			<div class="form-group">
			<div class="row">
				<div class="col">
					<label for="brand">Brand:</label>
								<select id="brand" class="autocomplete size form-control" multiple value="<?php echo $items[0]['brand'] ?? ''; ?>" name="brand" ></select>
				</div>
				<div class="col">
			<label for="category">Category:</label>
			<select id="category" class="js-multiple-select autocomplete size form-control" multiple value="<?php echo $items[0]['category'] ?? ''; ?>" name="category" ></select><br>
				</div>
				<div class="col">
			<label for="subcategory">Subcategory:</label>
			<select id="subcategory" class="autocomplete size form-control" multiple value="<?php echo $items[0]['subcategory'] ?? ''; ?>" name="subcategory" ></select><br>
			</div>
			</div></div>
			<div class="form-group">
<div class="row">
	<div class="col-sm-3">
			<label for="purchase_location">Purchase Location</label>
			<select id="purchase_location" class="autocomplete size form-control"  multiple value="<?php echo $items[0]['purchase_location'] ?? ''; ?>" name="purchase_location" ></select>
	</div>
	<div class="col-sm-3">

			<label for="purchase_price">Purchase Price:</label>
			<input type="text"id="purchase_price" class=" size form-control"  value="<?php echo $items[0]['purchase_price'] ?? ''; ?>" name="purchase_price" ></input>
		
		
		</div>

	<div class="col-sm-3">
			<label for="replacement_value">Replacement Value</label>
			<input type="text" id="replacement_value" class="replacement_value form-control"  value="<?php echo $items[0]['replacement_value'] ?? ''; ?>" name="replacement_value" ></input></div>
			<div class="col-sm-3">
				<label for="documentUpload">Documents:</label>

				<input type="file" id ="documentUpload" class="form-control-file" name="documentUpload" multiple="multiple" accept="image/*,application/pdf">
				
				<?php 
				if ($id != '' && $images) {
					echo "<div class='row'>";
					$documentURLs = '';
					foreach ($images as $document) {
						if ($document['type'] == 'document'){
					echo "<div class='col'>";

					echo "<a href='/images/items/".$document['url']."' class='tiny'>".$document['url']."</a>";
					} 
					echo "</div>";

					$documentURLs .= $document['url']. ',';
				
				}
				
				echo '</div>';
			}?>
			<input type="text" id="document" hidden class="custom-file-input" name="document" value="<?php echo $documentURLs ?? ''; ?>">
			</div>
			
		
			<div class="row">
			<div class="col">
			<label for="notes">Notes</label>
			<textarea id="notes" class="notes form-control" value="" name="notes" ><?php echo $items[0]['notes'] ?? ''; ?></textarea></div>
		
			</div>
			<div class="form-group">
				
			<div class="row">
				<div class="col">
					<button type="button" value="Add Item" id="addEditItemFormButton" class="btn btn-primary mt-3">Submit</button></div></div></div>
					</form>

				<?php 
				if ($id != '' && $maintenance) { ?>
					<div id="accordion" class="mt-3">
					  <div class="card">
						<div class="card-header" id="headingOne">
						  <h5 class="mb-0">
							<button class="btn btn-link" data-toggle="collapse" data-target="#maintenanceLogCard" aria-expanded="true" aria-controls="maintenanceLogCard">
							Maintenance Log</button>
						  </h5>
						</div>
					
						<div id="maintenanceLogCard" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
						  <div class="card-body">
					<table id='maintenanceLog'>	<thead>
					<tr><th>Date</th><th>Service</th><th>Notes</th><th>Cost</th></tr></thead>
				<?php foreach ($maintenance as $service) {
					echo "<tr><td>".$service['date']."</td><td>".$service['service']."</td><td>".$service['notes']."</td><td>".$service['cost']."</td></tr>";
			} ?>
					</table>
				</div>
					</div>
				  </div>
					</div>
		<?php	}
					?>

			
		
<script>
	$(document).ready(function () {
	
	getValues();
		$('#maintenanceLog').DataTable({
			"pageLength": 10,  		
			"searching": false,
			"order": [[1, "desc"]],  
			fixedHeader: true,
			responsive: true,
		});
		
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
					// Handle the response from the server
				//	window.location = "/index.php";
				alert("item update success");
				},
				error: function () {
					alert("Error processing the form.");
				}
			});
			localStorage.removeItem('cachedItems');

		});

$(".thumbnail").click(function (event) {
		var id = $(this).attr('data-item-id');
		var url = $(this).attr('data-url');
		var set = parseInt($(this).attr('data-set'), 10); // Convert to integer
		var setValue = (set === 0) ? 1 : (set === 1) ? 0 : '';
		console.log('setValue', setValue);
			$.ajax({
				type: "GET",
				url: "/api/index.php?action=setImageType&id="+id+"&url="+url+"&set="+setValue+"&type=thumbnail",
				success: function (response) {
					// Handle the response from the server
					console.log (response.message);
					$("#thumb_"+id).children().toggleClass('text-primary');
					$("#thumb_"+id).children().toggleClass('text-secondary');
				},
				error: function () {
					alert("Error processing the form.");
				}
			});		
		});
	$(".serial").click(function (event) {
	var id = $(this).attr('data-item-id');
	var url = $(this).attr('data-url');
	var set = parseInt($(this).attr('data-set'), 10); // Convert to integer
	var setValue = (set === 0) ? 1 : (set === 1) ? 0 : '';
	console.log('setValue', setValue);
console.log($(this));
		$.ajax({
			type: "GET",
			url: "/api/index.php?action=setImageType&id="+id+"&url="+url+"&set="+setValue+"&type=serial",
			success: function (response) {
				// Handle the response from the server
				console.log (response.message);
			$("#serial_"+id).children().toggleClass('text-primary');
			$("#serial_"+id).children().toggleClass('text-secondary');

			},
			error: function () {
				alert("Error processing the form.");
			}
		});		
	});

	$("#documentUpload").change(function (event) {
		// Create a FormData object
		var formData = new FormData();
	
		// Append files to FormData
		var fileInput = $("#documentUpload")[0];
		var files = fileInput.files;
	
		if (files.length === 1) {
			// If only one file is selected, append it without using an array
			formData.append('images[]', files[0]);
		} else {
			// If multiple files are selected, append them as an array
			for (var i = 0; i < files.length; i++) {
				formData.append('images[]', files[i]);
			}
		}
		formData.append('imageType', 'document');
	
		$.ajax({
			type: "POST",
			url: "/api/index.php?action=uploadPhoto",
			contentType: false,
			processData: false,
			data: formData,
			success: function (response) {
				// Handle the response from the server
				// Assuming the server responds with an array of photo paths
				// Adjust this part based on your server response
				var documentPaths = response.images;
				console.log(response.imageType);
				// Set the value of the photo input field
				$("#document").val(documentPaths.join(', '));
				$('#documentUpload').after('<i class="fa-solid fa-upload"></i>');
			},
			error: function () {
				alert("Error processing the form.");
			}
		});
	});

	$("#photoUpload").change(function (event) {
		// Create a FormData object
		var formData = new FormData();
	
		// Append files to FormData
		var fileInput = $("#photoUpload")[0];
		var files = fileInput.files;
	
		if (files.length === 1) {
			// If only one file is selected, append it without using an array
			formData.append('images[]', files[0]);
		} else {
			// If multiple files are selected, append them as an array
			for (var i = 0; i < files.length; i++) {
				formData.append('images[]', files[i]);
			}
		}
		formData.append('imageType', 'photo');

		$.ajax({
			type: "POST",
			url: "/api/index.php?action=uploadPhoto",
			contentType: false,
			processData: false,
			data: formData,
			success: function (response) {
				// Handle the response from the server
				// Assuming the server responds with an array of photo paths
				// Adjust this part based on your server response
				var photoPaths = response.images;
				console.log(response.imageType);
				// Set the value of the photo input field
				$("#photo").val(photoPaths.join(', '));
				$('#photoUpload').after('<i class="fa-solid fa-upload"></i>');
			},
			error: function () {
				alert("Error processing the form.");
			}
		});
	});

});
</script>

	<?php
}
if ($_GET['action'] =='view') {

}

include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
