<?php

namespace Controllers;

use \Models\VenueModel;

class VenueController {
	
public function returnVenues($db = '', $data = '', $autocomplete = '', $id='', $filter='', $value = '') {
	$db = new \Api\Database();
	if ($data == '') {
	$data = array('id'=> $id);
}
	//include ($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/table_header.php');

	$venueModel = new VenueModel($db, $data);
 	$venue = $venueModel->doReturnVenues();
	if ($autocomplete == 1){
		echo json_encode($venue);
	}
	else {
		foreach ($venue as $row){
		$id = $row['id'];
		$date = $row['date'];
		$name = $row['name'];
		$type = $row['type'] ?? '';
		$amount = $row['amount'] ?? '';
		$paid = $row['paid'] ?? '';
		$paidCheck = ($paid == 1) ? "-check" : "";
		$income = ($amount >= 0) ? "income" : "expense";
		$notes = $row['notes'] ?? '';
		$account = $row['account'] ?? '';
	
	include ($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/table_row.php');
	}
	include ($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/table_footer.php');
 }
}

public function editProfitLoss ($id = '') {
	if ($id != ''){
	$db = new \Api\Database();
	$data = array('id'=>$id);

		$profitLossModel = new ProfitLossEditModel($db, $data);
		 $profitLoss = $profitLossModel->doReturnProfitLoss();

		foreach ($profitLoss as $row){
			var_dump($row);
			$id = $row['id'];
			$date = $row['date'];
			$name = $row['name'];
			$type = $row['type'] ?? '';
			$amount = $row['amount'] ?? '';
			$paid = $row['paid'] ?? '';
			$paidCheck = ($paid == 1) ? "-check" : "";
			$income = ($amount >= 0) ? "income" : "expense";
			$notes = $row['notes'] ?? '';
			$tax_forms = $row['tax_forms'] ?? '';
			$tax_formsCheck = ($amount == 0) ? "" : "-check";
			$account = $row['account'] ?? '';
			$venue_id = $row['venue_id'] ?? '';
			$gig_notes = $row['note'] ?? '';
			$venue_payout = $row['venue_payout'] ?? '';
			$merch = $row['merch'] ?? '';
			$tips = $row['tips'] ?? '';
			$cost_to_play = $row['cost_to_play'] ?? '';
			$show_length = $row['show_length'] ?? '';


			
		include ($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/edit.php');
		}
	}
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
