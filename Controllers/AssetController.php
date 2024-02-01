<?php

namespace Controllers;

use \Models\AssetModel;

class AssetController {

public function updateItemCheckinStatus($db, $data) {

    //  include ($_SERVER['DOCUMENT_ROOT'].'/views/maintenance/maintenance_add.php');
   // include ($_SERVER['DOCUMENT_ROOT'].'/views/maintenance/maintenance_list_header.php');
   $assetModel = new \Models\AssetModel($db);
   $assetModel->changeCheckInStatus($data);
    
}

public function returnCheckedOutItems() {
    $db = new \Api\Database();

    //  include ($_SERVER['DOCUMENT_ROOT'].'/views/maintenance/maintenance_add.php');
   // include ($_SERVER['DOCUMENT_ROOT'].'/views/maintenance/maintenance_list_header.php');
   $assetModel = new \Models\AssetModel($db);
   $packedItems = $assetModel->getCheckedOutItems();
   include ($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_packed.php');

}

}
