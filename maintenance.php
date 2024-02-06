<?php
$id = $_GET['id'] ?? '';
$title = '- Maintenance'; 
require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$id = $_GET['id'] ?? '';
use Controllers\ItemMaintenanceController;

$maintenanceController = new ItemMaintenanceController();
?>
		<?php $maintenanceController->editMaintentance(); ?>

		<script src="/js/maintenance.js"></script>


	
<?php
include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
?>