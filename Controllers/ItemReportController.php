<?php

namespace Controllers;

use \Models\ItemReportModel;

class ItemReportController {
	
public function reportItemValue($db = '', $data='', $status ='' , $filter='', $value ='', $sort = 'count') {
	$db = new \Api\Database();
	if (!$data) {$data = array('filter'=> $filter, 'value'=> $value, 'status'=>$status, 'sort'=> $sort); }
	$itemModel = new ItemReportModel($db, $data);
	$items = $itemModel->returnValues();
	return $items;
}

public function autocomplete($db, $data) {

   $itemModel = new \Models\ItemInfoModel($db, $data);
   $itemModel->returnAutocompleteData();

   }
   
   public function list($db) {
	  $itemModel = new \Models\ItemInfoModel($db);
	 $items = $itemModel->returnItems();
      echo json_encode($items);
	  }

}
