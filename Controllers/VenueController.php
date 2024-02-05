<?php

namespace Controllers;

use \Models\VenueModel;

class VenueController {
	
public function returnVenues($db = '', $data = '', $autocomplete = '0', $id='', $filter='', $value = '') {
	$db = new \Api\Database();
	if ($data == '') {
	$data = array('id'=> $id);
	}
	$venueModel = new VenueModel($db, $data);
 	$venue = $venueModel->doReturnVenues();
	if ($autocomplete == 1){
		echo json_encode($venue);
	}
	else {
		include ($_SERVER['DOCUMENT_ROOT'].'/views/venue/table_header.php');
		foreach ($venue as $row){
		$id = $row['venue_id'];
		$name = $row['name'];
		$type = $row['venue_type'] ?? 'edit';
		$booking_contact = $row['booking_contact'] ?? 'edit';
		$city = $row['city'] ?? 'edit';
		$state = $row['state'] ?? 'edit';
		$status= $row['status'] ?? 'edit';
		$roundtrip_mileage = $row['roundtrip_mileage'] ?? '0';
	
	include ($_SERVER['DOCUMENT_ROOT'].'/views/venue/table_row.php');
	}
	include ($_SERVER['DOCUMENT_ROOT'].'/views/venue/table_footer.php');
 }
}

public function editVenue ($id = '') {
	if ($id != ''){
	$db = new \Api\Database();
	$data = array('id'=>$id);

$venueModel = new VenueModel($db, $data);
	 $venue = $venueModel->doReturnVenues();


		foreach ($venue as $row){
			$id = $row['venue_id'];
			$name = $row['name'];
			$venue_type = $row['venue_type'] ?? '';
		$type = $row['type'] ?? '';
		$booking_contact = $row['booking_contact'] ?? '';
		$city = $row['city'] ?? '';
		$state = $row['state'] ?? '';
		$country = $row['country'] ?? '';

		$roundtrip_mileage = $row['roundtrip_mileage'] ?? '';


			
		include ($_SERVER['DOCUMENT_ROOT'].'/views/venue/edit.php');
		}
	}
	else 
	include ($_SERVER['DOCUMENT_ROOT'].'/views/venue/edit.php');

}
	
public function upsertVenueData($db, $data) {

   $venueModel = new \Models\VenueModel($db, $data);
   $venueModel->doUpsertVenueData();

   }
   
   
   public function list($db) {
	  $itemModel = new \Models\ItemInfoModel($db);
	 $items = $itemModel->returnItems();
      echo json_encode($items);
	  }

}
