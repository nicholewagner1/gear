<?php
$id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? 'list';
$title = '- Outfit - '.$action ;

require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$id = $_GET['id'] ?? '';
use Client\ApiClient;

$apiClient = new Client\ApiClient($apiBaseUrl, $apiCache);

if ($id != '' && $action == 'edit' || $action == 'list') {
    $parameters = ['action' => 'returnPackingLists'];
    $parameters['id'] = $id;
}
if ($id != '' && $action == 'view') {
    $parameters = ['action' => 'checkPackingLists'];
    $parameters['id'] = $id;
}
$lists = $apiClient->get('', $parameters);
?>

<?php if ($action == 'add' || $action == 'edit') { ?>
	<div class="row">
		<div class="col">
<h2>Create/Edit Packing List</h2>

<form id="packingList">
	<input type="text" id="id" name="id" hidden value="<?php echo $lists[$id]['id']; ?>"><br>

	<label for="name">Name:</label>
	<input type="text" id="name" class="form-control" name="name" required value="<?php echo $lists[$id]['name'] ; ?>">
	<div class="row">
		 <div class="col">
			 
			 
		<table id="itemList">
			<thead><tr>
				<td>Count Needed</td>
				<td>Item</td>
				<td>Subcategory</td></tr></thead>
	<tbody>
	<?php
    $items = $lists[$id]['items'];
    $i = '0';
    foreach ($items as $listItem) {
        $i++;
        ?>
	<tr id="<?php echo $i; ?>">
		<td><input type="text" class="form-control"  name="count_needed" value="<?php echo $listItem['count_needed']; ?>"> </td>
	<td><select name="item" id="item_<?php echo $i; ?>" class="js-multiple-select item form-control" multiple value="<?php echo $listItem['item'] ?? ''; ?>"></select></td>
	<td>
		<select id="subcategory_<?php echo $i; ?>" class="autocomplete size form-control" multiple value="<?php echo $listItem['subcategory'] ?? ''; ?>" name="subcategory" ></select>
</td>
</tr>
<?php
    } ?>
	</tbody>
		 </table>
		 </div>
	</div>
	<div class="row">
		<div class="col">
		 <div class="btn btn-secondary" id="itemNew"><i class="fa-regular fa-square-plus"></i> New Item</div>
		</div></div>
	<div class="row">
		<div class="col">
	<input class="btn btn-primary" type="submit" value="Update List">
		</div></div>
</form>
		</div></div>
<!-- Include script for processing form with jQuery -->
<script>
	$(document).ready(function () {
		
		getValues();		

function getItems() {
	$(".item").each(function () {
		const previousItems = $(this).attr('value');
		console.log('value', previousItems);
			const id = $(this).attr('id');
		console.log('item',id);
			// Check if data is already cached in localStorage
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
		//	console.log('daata',data);
			preFillSelector(id, previousItems);
		});
		}
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
		
	function populateDropdown(id, data) {
		console.log('itemId',id);

		const itemSelector = $("#"+id);
		itemSelector.empty();
	
		data.forEach((item) => {
			const option = $("<option>").val(item.id).text(item.name);
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
	getItems();

		
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
		$("#itemNew").on("click", function () {
			var lastRowId = $('#itemList tr:last').attr('id');
			if (!lastRowId) {
				lastRowId = "0";
			}
	var rowId = parseInt(lastRowId) + 1; // Ensure numeric addition
	$("#itemList tbody").append('<tr id="' + rowId + '"><td><input type="text" id="count_' + rowId + '"  class="form-control" value="" name="count_needed"></td><td><select name="item" id="item_' + rowId + '" class="js-multiple-select item form-control" multiple value=""></select></td><td><select id="subcategory_' + rowId + '" class="autocomplete size form-control" multiple value="" name="subcategory" ></select></td></tr>');
	
	getNewRowItems("item_"+rowId);
	getValues(1);
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
					// window.location = "/outfit.php";
					// alert(data.message);
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
if ($action == 'view') {
    ?>
	<div class="row">
	<div class="col">
<h3>Packed <?php echo $lists[0]['name']; ?></h3>
<table>
<?php
foreach ($lists[0]['packed'] as $packed) {
    $name = $packed['subcategory'] != '' ? $packed['subcategory'] : $packed['item_name'];
    $count = $packed['count_category'] != '0' ? $packed['count_category'] : $packed['packed'];
    $countNeeded = $packed['count_needed'] != '0' ? $packed['count_needed'] : $packed['count_needed'];

    echo "<tr><td>".$name."</td><td>".$count."/".$countNeeded."</td></tr>";
}
                foreach ($lists[0]['missing'] as $missing) {
                    $name = $missing['subcategory'] != '' ? $missing['subcategory'] : $missing['item_name'];
                    $count = $missing['count_category'] != '0' ? $missing['count_category'] : $missing['packed'];
                    $countNeeded = $missing['count_needed'] != '0' ? $missing['count_needed'] : $missing['count_needed'];

                    echo "<tr class='missing'><td>".$name."</td><td>".$count."/".$countNeeded."</td></tr>";

                }
?>
</table>
	</div>
<div class="col">
  <h3>All Packed Items</h3>
  <?php
    $parametersList = ['action' => 'getCheckedOutItems'];
    $listsAll = $apiClient->get('', $parametersList);
    echo "<table>";
    foreach ($listsAll as $itemsPacked) {
        echo "<tr><td>".$itemsPacked['name']."</tr></td>";
    }
    echo "</table>";
}
?>
</div></div>
<?php
if ($action == '' or $action == 'list') {
    ?> <h2>Packing Lists</h2>
	
	<table id="itemTable">
		<thead>
			<tr>
				<th>Name</th>
	
				<th>Action</th>

			</tr>
		</thead>
		<tbody>
			
<?php foreach ($lists as $list):
    ?>
				<tr>
					<td class="center"><?php echo $list['name'];
    ?>
					</td><td><a href="/packing_list.php?action=edit&id=<?php echo $list['id']; ?>"><i class="fa-solid fa-pencil"></i></a></td>
				
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