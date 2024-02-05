<?php
$id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? 'list';
$title = '- Packing Lists - '.$action ;

require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');?>

		<script src="/js/packing_list.js"></script>
<?php
$id = $_GET['id'] ?? '';
use Controllers\PackingListController;
$packingList = new PackingListController();

if ($action == 'add' || $action == 'edit' || $action == 'clone') { 
	$packingList->showPackingList($id); 
}
elseif ($action == 'view') {
	
 $packingList->returnCheckedOutItems($id); 

}
else {
$packingList->listPackingList(); 
}
	?>

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