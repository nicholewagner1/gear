<?php
$title = 'Home';
require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$missing = $_GET['missing'] ?? '' ;
$filter = $_GET['filter'] ?? '' ;
$value = $_GET['value'] ?? '' ;
$status = $_GET['status'] ?? '' ;

?>

<h2>Items <?php echo $value; ?></h2>

<div class="" id="collapseExample">
  <div class="card card-body">
<form id="filterSelect">
		  <div class="row">
  
		  <div class="col-4">
	  <label for="brand">Brand:</label>
					  <select id="brand" class="autocomplete brand form-control" multiple value="<?php echo $items[0]['brand'] ?? ''; ?>" name="brand" ></select>
		  </div>
		  <div class="col-4">
	  <label for="category">category:</label>
	  <select id="category" class="autocomplete category form-control" multiple value="<?php echo $items[0]['category'] ?? ''; ?>" name="category" ></select>
		  </div>
		  <div class="col-4">
			<label for="subcategory">subcategory:</label>
			<select id="subcategory" class="autocomplete subcategory form-control" multiple value="<?php echo $items[0]['subcategory'] ?? ''; ?>" name="subcategory" ></select>
				</div>
		  </div>
  </form>  </div>
</div>
<table id="itemTable">
	<thead>
		<tr>
			<th data-priority="1">Photos</th>
			<th data-priority="2">Name</th>
			<th>Category</th>
			<th>Sub category</th>
			<th data-priority="">Model</th>
			<th data-priority="3">Checked In</th>
		<th>Action</th>


		</tr>
	</thead>
	<tbody>
		
		<?php
		use Client\ApiClient;
		$apiClient = new Client\ApiClient($apiBaseUrl);
		$parameters = ['action' => 'list'];
		
		if ($missing != '') {
			$parameters['missing'] = $missing;
		}
		
		if ($status != '') {
			$parameters['status'] = $status;
		}
		
		if ($filter != '' && $value != '') {
			$parameters['filter'] = $filter;
			$parameters['value'] = $value;
		}
		
		$items = $apiClient->get('', $parameters);
		
		 foreach ($items as $item): 
			 $id = $item['id'] ?? 'edit';
			 $name = $item['name'] !== '' ? $item['name'] : 'edit';
			 $brand = $item['brand'] !== '' ? $item['brand'] : 'edit';
			 $category = $item['category'] !== '' ? $item['category'] : 'edit';
			 $subcategory = $item['subcategory']  !== '' ? $item['subcategory'] : 'edit';
			 $color=$item['model']  !== '' ? $item['model'] : 'edit';
			 $checked_in=$item['checked_in']  !== '' ? $item['checked_in'] : 'edit';
			 $imageParameters = ['action' => 'images'];
			 $imageParameters['id'] = $id;
			// print_r($imageParameters);
				 $images = $apiClient->get('', $imageParameters);
			// 	var_dump($images);
			 ?>
			<tr id="item_<?php echo $id; ?>">
				<td id="<?php echo 'cell_photo_'.$id ;?>">
					<?php if($images){
						foreach ($images as $image){
							if ($image['thumbnail'] == 1) {
						 $imageURL = $image['url']; 
					//	 echo $imageURL;
						 ?>
						
						 <span class="editable-image" data-field="photo" data-itemid="<?php echo $id; ?>"><?php echo '<img src="/images/items/'.$imageURL.'" width=100 height=100>'; ?></span>
					<?php 
				}
				 } //end image loop
			 }
					 ?>
</td>
				<td id="<?php echo 'cell_name_'.$id ;?>">
					<span class="editable-text" data-field="name" data-itemid="<?php echo $id; ?>">
						<?php echo $name; ?>
					</span>
					
						</td>				
				
				<td id="<?php echo "cell_category_".$id ;?>">
					<span class="editable-select" data-field="category" data-itemid="<?php echo $id; ?>">
						<?php echo $category; ?>
					</span>
				</td>
				<td id="<?php echo "cell_subcategory_".$id ;?>">
					<span class="editable-select" data-field="subcategory" data-itemid="<?php echo $id; ?>">
						<?php echo $subcategory; ?>
					</span>
				</td>
				<td id="<?php echo 'cell_brand_'.$id ;?>">
					<span class="editable-select" data-field="brand" data-itemid="<?php echo $id; ?>">
						<?php echo $brand;?>
					</span>
				</td>
				<td id="<?php echo "cell_color_".$id ;?>">
					<span class="editable-select" data-field="model" data-itemid="<?php echo $id; ?>">
						<?php echo $model; ?>
					</span>
				</td>
				<td id="<?php echo "cell_status_".$id ;?>">
					<span class="" data-field="checked_in" data-itemid="<?php echo $id; ?>">
						<?php echo $checked_in; ?>
					</span>
					 <a href='item.php?action=edit&id=<?php echo $id ?>' class="" id=<?php echo $id ?>><i class='fa-regular fa-pencil'></i></a>
			</td>
			
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<!-- Initialize DataTable -->
<script>
	$(document).ready(function () {
		getValues();
			$("#filterSelect .autocomplete").change(function (event) {
				var filter = $(this).attr('id');
				var value = encodeURIComponent($(this).val());
				console.log (filter);
				console.log (value);
				window.location = "/?filter="+filter+"&value="+value;

				});
		$(".delete").click(function (event) {
		var action = $(this).hasClass('hard') ? 'hardDelete' : 'delete';
		var status = $(this).hasClass('hard') ? '' : 'Considering for Donation';

		const id = $(this).attr('id');
			$.ajax({
				type: "GET",
				url: "/api/index.php?action="+action+"&status="+status+"&id=" +id,
				success: function (response) {
					// Handle the response from the server
					$('#item_'+id).remove();
				},
				error: function () {
					alert("Error processing the form.");
				}
			});
		});

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
		var inputField = $('<select multiple>')
			.val(fieldValue);
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
	
	
   $('#itemTable').DataTable({
		"pageLength": 30,  		
		"searching": true,
		"order": [[2, "desc"]],  
		fixedHeader: true,
		responsive: true,
		



	});
	});
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
</body>
</html>