<?php

namespace Controllers;

use \Models\ProfitLossEditModel;

class ProfitLossEditController {
	
public function returnProfitLoss($db = '', $data = '', $filter='', $value = '', $date_start = '', $date_end = '') {
	$db = new \Api\Database();
	$data = array('filter'=> $filter, 'value'=> $value, 'date_start'=> $date_start, 'date_end'=>$date_end);
	
	include ($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/table_header.php');

	$profitLossModel = new ProfitLossEditModel($db, $data);
 	$profitLoss = $profitLossModel->doReturnProfitLoss();
	foreach ($profitLoss as $row){
		$id = $row['id'];
		$date = $row['date'];
		$is_future = ($date > date('Y-m-d') ? "future" : "");
		$name = $row['name'];
		$type = $row['type'] ?? '';
		$amount = $row['amount'] ?? '';
		$paid = $row['paid'] ?? '';
		$paidCheck = ($paid == 1) ? "-check text-success" : "-xmark text-warning";
		$income = ($amount >= 0) ? "income" : "expense";
		$notes = $row['notes'] ?? '';
		$account = $row['account'] ?? '';
	
	include ($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/table_row.php');
	}
	include ($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/table_footer.php');

}

public function editProfitLoss ($id = '') {
	if ($id != ''){
		$db = new \Api\Database();
	 	$data = array('id'=>$id);
		$profitLossModel = new ProfitLossEditModel($db, $data);
		$profitLoss = $profitLossModel->doReturnProfitLoss();
		foreach ($profitLoss as $row){
			$id = $row['id'];
			$date = $row['date'];
			$name = $row['name'];
			$type = $row['type'] ?? '';
			$amount = $row['amount'] ?? '';
			$paid = $row['paid'] ?? '0';
			$paidCheck = ($paid == 1) ? "-check" : "";
			$income = ($amount >= 0) ? "income" : "expense";
			$notes = $row['notes'] ?? '';
			$tax_forms = $row['tax_forms'] ?? '0';
			$tax_formsCheck = ($amount == 0) ? "" : "-check";
			$account = $row['account'] ?? '';
			$venue_id = $row['venue_id'] ?? '';
			$gig_id = $row['gig_id'] ?? '';
			$gig_notes = $row['note'] ?? '';
			$venue_payout = $row['venue_payout'] ?? '';
			$merch = $row['merch'] ?? '';
			$tips = $row['tips'] ?? '';
			$cost_to_play = $row['cost_to_play'] ?? '';
			$show_length = $row['show_length'] ?? '';
			
		include ($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/edit.php');
		}
	}
	else {
		$date = date('Y-m-d');
		include ($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/edit.php');
	}
}
	

public function returnProfitLossAutocompleteData($db, $data) {

   $autocomplete = new \Models\ProfitLossEditModel($db, $data);
   $autocomplete->doProfitLossAutocompleteData();

   }
   
   public function upsertProfitLossData($db, $data) {
   $edit = new \Models\ProfitLossEditModel($db, $data);
	 $editedPL = $edit->doUpsertProfitLossData();
	  }

}
