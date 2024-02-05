<?php

namespace Controllers;

use \Models\ItemMaintenanceModel;

class ItemMaintenanceController {


public function editMaintentance () {
	$date = date('Y-m-d'); 

	include ($_SERVER['DOCUMENT_ROOT'].'/views/maintenance/maintenance_add.php');
	include ($_SERVER['DOCUMENT_ROOT'].'/views/maintenance/maintenance_list_header.php');

	$db = new \Api\Database();
	$maintenanceModel = new \Models\ItemMaintenanceModel($db);
	$maintenance = $maintenanceModel->returnAllMaintenance();

	foreach ($maintenance as $service) {
		$id = $service['id'] ?? '';
		$dateCompleted = $service['date']; 
		$serviceDone = $service['service'];
		$item = $service['item'];
		$notes = $service['notes'];
		$cost = $service['cost'];
		include ($_SERVER['DOCUMENT_ROOT'].'/views/maintenance/maintenance_list_row.php');
	}
		include ($_SERVER['DOCUMENT_ROOT'].'/views/maintenance/maintenance_list_footer.php');

}
 public function upsertMaintenance($db, $data) {

  $maintenanceModel = new \Models\ItemMaintenanceModel($db, $data);
  $maintenanceModel->doUpsertMaintenance($db, $data);

  }

}
