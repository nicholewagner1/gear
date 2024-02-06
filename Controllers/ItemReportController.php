<?php

namespace Controllers;

use \Models\ItemReportModel;

class ItemReportController {
	
public function reportItemValue($db = '', $data='', $status ='' , $filter='', $value ='', $sort = 'count') {
	$db = new \Api\Database();
	if (!$data) {$data = array('filter'=> $filter, 'value'=> $value, 'status'=>$status, 'sort'=> $sort); }
	$reportModel = new ItemReportModel($db, $data);
	$items = $reportModel->returnValues();
	return $items;
}
public function gigVenueDetails($db = '', $data = ''){
	$db = new \Api\Database();

	$reportModel = new ItemReportModel($db, $data);
	  $report = $reportModel->doReturnGigDetails();
	  $helper = new \Controllers\HelpersController();
	  $columnNames = ['Venue', 'Payout Average', 'Tips Average', 'Times Played'];
	  $helper->generateTableHeaders('gigsTable',$columnNames);
	  
	  foreach ($report as $info){
		  $name = $info['name'];
		  $venue_average = $info['venue_average'];
		  $tips_average = $info['tips_average'];
		  $played = $info['played'];
		  include ($_SERVER['DOCUMENT_ROOT'].'/views/reports/gigVenuereport.php');  

	  }
	  $helper->generateTableFooters();

}
public function insuranceReport() {
	$db = new \Api\Database();
	$data = '';
	$reportModel = new ItemReportModel($db, $data);
    $report = $reportModel->doInsuranceReport();
    $helper = new \Controllers\HelpersController();
	$columnNames = ['Name', 'Brand', 'Model', 'Serial #', 'Replacement Value'];
	$helper->generateTableHeaders('insuranceTable',$columnNames);
	
	foreach ($report as $info){
		$id = $info['id'];
		$name = $info['name'];
		$brand = $info['brand'];
		$model = $info['model'];
		$serial_number = $info['serial_number'];
		$replacement_value = $info['replacement_value'];
		include ($_SERVER['DOCUMENT_ROOT'].'/views/reports/insuranceReport.php');  
  
	}
	$helper->generateTableFooters();

  }
public function profitLossCategory($db, $data) {

	$reportModel = new ItemReportModel($db, $data);
  $report = $reportModel->doReturnProfitLossCategory();
   echo json_encode($report);
   }
   
   public function profitLoss($db, $data) {
	$reportModel = new ItemReportModel($db, $data);
	 $items = $reportModel->doReturnProfitLoss();
      echo json_encode($items);
	  }

}
