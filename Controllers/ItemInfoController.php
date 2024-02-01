<?php

namespace Controllers;

use \Models\ItemInfoModel;

class ItemInfoController {
	
public function displayItemsList($view, $missing = '', $status ='' , $filter='', $value ='', $sort = '') {
	include ($_SERVER['DOCUMENT_ROOT'].'/views/item_list/item_'.$view.'_header.php');
	$db = new \Api\Database();
	$data = array('filter'=> $filter, 'value'=> $value, 'missing'=> $missing, 'status'=>$status, 'sort'=>$sort);
	$itemModel = new ItemInfoModel($db, $data);
	$items = $itemModel->returnItems();
	//var_dump($items);
	foreach ($items as $item) {
		$id = $item['id'] ?? 'edit';
		$name = $item['name'] !== '' ? $item['name'] : '';
		$brand = $item['brand'] !== '' ? $item['brand'] : '';
		$category = $item['category'] !== '' ? $item['category'] : '';
		$subcategory = $item['subcategory']  !== '' ? $item['subcategory'] : '';
		$model = $item['model']  !== '' ? $item['model'] : 'edit';
		$cost = $item['purchase_price'] !== '' ? $item['purchase_price'] : '';
		$checked_in = $item['checked_in']  == '1' ? 'checkedIn': 'checkedOut';
		$imageURL = $item['url'] !='' ? '/images/items/'.$item['url'] : 'https://placehold.co/800?text=No+Image&font=roboto'; 
		
		include ($_SERVER['DOCUMENT_ROOT'].'/views/item_list/item_'.$view.'_row.php');
	}
	
	include ($_SERVER['DOCUMENT_ROOT'].'/views/item_list/item_'.$view.'_footer.php');
}

public function editItem ($id = '') {
	if ($id != ''){
	$db = new \Api\Database();
	$data = array('id'=>$id);
	$itemModel = new ItemInfoModel($db, $data);
	$maintenanceModel = new \Models\ItemMaintenanceModel($db, $data);
	$items = $itemModel->returnItems();
	//var_dump($items);
	foreach ($items as $item) {
		$id = $item['id'] ?? '';
		$date_acquired = $item['date_acquired'] !== '' ? $item['date_acquired'] : date('Y-m-d'); 
		$name = $item['name'] !== '' ? $item['name'] : '';
		$brand = $item['brand'] !== '' ? $item['brand'] : '';
		$category = $item['category'] !== '' ? $item['category'] : '';
		$subcategory = $item['subcategory']  !== '' ? $item['subcategory'] : '';
		$model = $item['model']  !== '' ? $item['model'] : '';
		$cost = $item['purchase_price'] !== '' ? $item['purchase_price'] : '';
		$checked_in = $item['checked_in']  == '1' ? 'checkedIn': 'checkedOut';
		$images = $itemModel->returnItemImages($id);
		$maintenance = $maintenanceModel->returnAllMaintenance();

		include ($_SERVER['DOCUMENT_ROOT'].'/views/item_edit/item_edit_header.php');

		//include ($_SERVER['DOCUMENT_ROOT'].'/views/item_edit/item_edit_row.php');
	}
}
else {	
	$date_acquired = date('Y-m-d');
	include ($_SERVER['DOCUMENT_ROOT'].'/views/item_edit/item_edit_header.php');
	}
	
//	include ($_SERVER['DOCUMENT_ROOT'].'/views/item_edit/item_edit_footer.php');
}

public function autocomplete($db, $data) {

   $itemModel = new \Models\ItemInfoModel($db, $data);
   $itemModel->returnAutocompleteData();

   }

}
