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
			<th data-priority="">Brand</th>
			<th data-priority="">Model</th>
			<th>Cost</th>
			<th data-priority="3">Checked In</th>

		<th>Action</th>


		</tr>
	</thead>
	<tbody>
		
		<?php
        use Client\ApiClient;

$apiClient = new Client\ApiClient($apiBaseUrl, $apiCache);
$parameters = ['action' => 'list'];

if ($missing != '') {
    $parameters['missing'] = $missing;
	$cache = "listAll".$missing;

}

if ($status != '') {
    $parameters['status'] = $status;
	$cache = "listAll".$status;
}

if ($filter != '' && $value != '') {
    $parameters['filter'] = $filter;
    $parameters['value'] = $value;
	if ($filter == "checked_in") {
		$cache = null;
	}
	else {
	$cache = "listAll".$filter."-".$value;
}
}
else {
	$cache = "allItems";
}
echo $cache;
$items = $apiClient->get('', $parameters, $cache);

foreach ($items as $item):
    $id = $item['id'] ?? 'edit';
    $name = $item['name'] !== '' ? $item['name'] : 'edit';
    $brand = $item['brand'] !== '' ? $item['brand'] : 'edit';
    $category = $item['category'] !== '' ? $item['category'] : 'edit';
    $subcategory = $item['subcategory']  !== '' ? $item['subcategory'] : 'edit';
    $model = $item['model']  !== '' ? $item['model'] : 'edit';
    $cost = $item['purchase_price'] !== '' ? $item['purchase_price'] : 'edit';
    $checked_in = $item['checked_in']  == '1' ? 'checkedIn': 'checkedOut';
    $imageParameters = ['action' => 'images'];
    $imageParameters['id'] = $id;
    // print_r($imageParameters);
    $images = $apiClient->get('', $imageParameters);
    // 	var_dump($images);
    ?>
			<tr id="item_<?php echo $id; ?>" class="<?php echo $checked_in; ?>">
				<td id="<?php echo 'cell_photo_'.$id ;?>">
					<?php if($images) {
					    foreach ($images as $image) {
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
				<td id="<?php echo "cell_model_".$id ;?>">
					<span class="editable-select" data-field="model" data-itemid="<?php echo $id; ?>">
						<?php echo $model; ?>
					</span>
				</td>
				<td id="<?php echo "cell_cost_".$id ;?>">
					<span class="editable-select" data-field="cost" data-itemid="<?php echo $id; ?>">
						<?php echo $cost; ?>
					</span>
				</td>
				<td id="<?php echo "cell_checked_in_".$id ;?>">
					<span class="" data-field="checked_in" data-itemid="<?php echo $id; ?>">
						<?php echo $checked_in; ?>
					</span>
				</td>
				<td id="<?php echo "cell_action_".$id ;?>">
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