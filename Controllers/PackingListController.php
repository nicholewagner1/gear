<?php

namespace Controllers;

use \Models\PackingListModel;
use \Models\AssetModel;

class PackingListController {

public function listPackingList() {
    $db = new \Api\Database();
   $packingModel = new \Models\PackingListModel($db);
   $lists = json_decode($packingModel->returnPackingLists(), true);
   include ($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_view.php');
   
}

public function showPackingList($id = '') {

    $db = new \Api\Database();
    $data = array('id'=>$id);
   $packingModel = new \Models\PackingListModel($db, $data);
   $lists = json_decode($packingModel->returnPackingLists(), true);
   include ($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_edit_header.php');  

   if ($id != ''){
   include ($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_edit_row.php');  
}
   include ($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_edit_footer.php');  

}

public function returnCheckedOutItems($id = '') {
    $db = new \Api\Database();
    $data = array('id'=> $id);

   $assetModel = new \Models\AssetModel($db);
   $checkedOutItems = $assetModel->getCheckedOutItems();
   
   
   $packingModel = new \Models\PackingListModel($db, $data);
   $items = $packingModel->checkPackingLists();
   $packedItems = json_decode($items, true);
   //print_r($packedItems[0]['packed']);
   
   include ($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_packed.php');

}
public function updatePackingList($db, $data) {

   $packingModel = new \Models\PackingListModel($db, $data);
   $packingModel->updatePackingListItems();

   }
   public function deleteList($db, $data) {
   
   $packingModel = new \Models\PackingListModel($db, $data);
   $packingModel->deletePackingList();
   
   }
   
}
