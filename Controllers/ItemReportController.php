<?php

namespace Controllers;

use Models\ItemReportModel;

class ItemReportController
{
    public function reportItemValue($data='', $status ='', $filter='', $value ='', $sort = 'count')
    {
        if (!$data) {
            $data = array('filter'=> $filter, 'value'=> $value, 'status'=>$status, 'sort'=> $sort);
        }
        $reportModel = new ItemReportModel($data);
        $items = $reportModel->returnValues();
        return $items;
    }
    public function gigVenueDetails($data = '')
    {
        $reportModel = new ItemReportModel($data);
        $report = $reportModel->doReturnGigDetails();
        $helper = new \Controllers\HelpersController();
        $columnNames = ['Venue', 'Payout Average', 'Tips Average', 'Times Played'];
        $helper->generateTableHeaders('gigsTable', $columnNames);

        foreach ($report as $info) {
            $name = $info['name'];
            $venue_average = $info['venue_average'];
            $tips_average = $info['tips_average'];
            $played = $info['played'];
            include($_SERVER['DOCUMENT_ROOT'].'/views/reports/gigVenuereport.php');
        }
        $helper->generateTableFooters();
    }
    public function insuranceReport()
    {
        $data = '';
        $reportModel = new ItemReportModel($data);
        $report = $reportModel->doInsuranceReport();
        $helper = new \Controllers\HelpersController();
        $columnNames = ['Name', 'Brand', 'Model', 'Serial #', 'Replacement Value'];
        $helper->generateTableHeaders('insuranceTable', $columnNames);

        foreach ($report as $info) {
            $id = $info['id'];
            $name = $info['name'];
            $brand = $info['brand'];
            $model = $info['model'];
            $serial_number = $info['serial_number'];
            $replacement_value = $info['replacement_value'];
            include($_SERVER['DOCUMENT_ROOT'].'/views/reports/insuranceReport.php');
        }
        $helper->generateTableFooters();
    }
    public function profitLossCategory($data)
    {
        $reportModel = new ItemReportModel($data);
        $report = $reportModel->doReturnProfitLossCategory();
        echo json_encode($report);
    }

    public function profitLoss($data)
    {
        $reportModel = new ItemReportModel($data);
        $items = $reportModel->doReturnProfitLoss();
        echo json_encode($items);
    }
    public function outstandingPayments($data)
    {
        $reportModel = new ItemReportModel($data);
        $items = $reportModel->doReturnOutstandingPayments();
        echo json_encode($items);
    }
}
