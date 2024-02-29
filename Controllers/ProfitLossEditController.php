<?php

namespace Controllers;

use Models\ProfitLossEditModel;

class ProfitLossEditController
{
    public function returnProfitLoss($data = '', $filter='', $value = '', $date_start = '', $date_end = '')
    {
        $data = array('filter'=> $filter, 'value'=> $value, 'date_start'=> $date_start, 'date_end'=>$date_end);

        $helper = new \Controllers\HelpersController();
        $columnNames = ['Date', 'Name', 'Category', 'Subcategory', 'Amount', 'Paid', 'Tax Forms', 'Action'];
        $helper->generateTableHeaders('profitLossTable', $columnNames);

        $profitLossModel = new ProfitLossEditModel($data);
        $profitLoss = $profitLossModel->doReturnProfitLoss();
        foreach ($profitLoss as $row) {
            $id = $row['id'];
            $date = $row['date'];
            $is_future = ($date > date('Y-m-d') ? "future" : "");
            $name = $row['name'];
            $category = $row['category'] ?? '';
            $subcategory = $row['subcategory'] ?? '-';
            $amount = $row['amount'] ?? '';
            $paid = $row['paid'] ?? '';
            $paidCheck = ($paid == 1) ? "-check text-success" : "-xmark text-warning";
            $tax_forms = $row['tax_forms'] ?? '';
            $tax_forms_check = ($tax_forms == 1) ? "-check text-success" : "-xmark text-warning";
            $income = ($amount >= 0) ? "income" : "expense";
            $notes = $row['notes'] ?? '';
            $gig_notes = $row['gig_notes'] ?? '';

            $account = $row['account'] ?? '';

            include($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/table_row.php');
        }

        $helper->generateTableFooters();
    }

    public function editProfitLoss($id = '')
    {
        if ($id != '') {
            $data = array('id'=>$id);
            $profitLossModel = new ProfitLossEditModel($data);
            $profitLoss = $profitLossModel->doReturnProfitLoss();
            foreach ($profitLoss as $row) {
                $id = $row['id'];
                $date = $row['date'];
                $name = $row['name'];
                $category = $row['category'] ?? '';
                $subcategory = $row['subcategory'] ?? '';
                $amount = $row['amount'] ?? '';
                $paid = $row['paid'] ?? '';
                $paidCheck = ($paid == 1) ? "-check text-success" : "-xmark text-warning";
                $tax_forms = $row['tax_forms'] ?? '';
                $tax_forms_check = ($tax_forms == 1) ? "-check text-success" : "-xmark text-warning";
                $income = ($amount >= 0) ? "income" : "expense";
                $notes = $row['notes'] ?? '';
                $account = $row['account'] ?? '';
                $venue_id = $row['venue_id'] ?? '';
                $gig_id = $row['gig_id'] ?? '';
                $gig_notes = $row['gig_notes'] ?? '';
                $venue_payout = $row['venue_payout'] ?? '';
                $merch = $row['merch'] ?? '';
                $tips = $row['tips'] ?? '';
                $cost_to_play = $row['cost_to_play'] ?? '';
                $show_length = $row['show_length'] ?? '';

                include($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/edit.php');
            }
        } else {
            $date = date('Y-m-d');
            include($_SERVER['DOCUMENT_ROOT'].'/views/profit_loss/edit.php');
        }
    }


    public function returnProfitLossAutocompleteData($data)
    {
        $autocomplete = new \Models\ProfitLossEditModel($data);
        $autocomplete->doProfitLossAutocompleteData();
    }

    public function upsertProfitLossData($data)
    {
        $edit = new \Models\ProfitLossEditModel($data);
        $editedPL = $edit->doUpsertProfitLossData();
    }
}
