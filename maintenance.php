<?php
$id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? 'view';
$title = '- Outfit - '.$action ;

require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$id = $_GET['id'] ?? '';
use Controllers\ItemMaintenanceController;

$maintenanceController = new ItemMaintenanceController();
?>
		<?php $maintenanceController->editMaintentance(); ?>

		<script src="/js/item_edit.js"></script>
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