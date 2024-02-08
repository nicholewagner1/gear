<?php

namespace Controllers;

use Models\CSVImportModel;

class CSVImportController
{
    public function importCSVProfitLoss($data)
    {
        $csvModel = new \Models\CSVImportModel();
        $csvModel->doUploadCSVProfitLoss($data);
    }
}
