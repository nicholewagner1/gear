<?php
$action = $_GET['action'] ?? '';
$title = '- Item - '.$action ;
require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$id = $_GET['id'] ?? '';
use Controllers\ItemInfoController;
$itemController = new ItemInfoController();

 $itemController->editItem($id); ?>

		<script src="/js/item_edit.js"></script>

<script>
	$(document).ready(function () {
	getValues('item');
		$('#maintenanceLog').DataTable({
			"pageLength": 10,  		
			"searching": false,
			"order": [[1, "desc"]],  
			fixedHeader: true,
			responsive: true,
		});
	
});
</script>

	<?php


include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
