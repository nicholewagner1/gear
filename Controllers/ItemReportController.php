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
