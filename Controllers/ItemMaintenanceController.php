<?php

namespace Controllers;

use Models\ItemMaintenanceModel;

class ItemMaintenanceController
{
    public function editMaintentance()
    {
        $date = date('Y-m-d');
        include($_SERVER['DOCUMENT_ROOT'].'/views/maintenance/maintenance_add.php');
        $helper = new \Controllers\HelpersController();
        $columnNames = ['Date', 'Service', 'Item', 'Notes', 'Cost'];
        $helper->generateTableHeaders('maintenanceTable', $columnNames);

        $maintenanceModel = new \Models\ItemMaintenanceModel();
        $maintenance = $maintenanceModel->returnAllMaintenance();

        foreach ($maintenance as $service) {
            $id = $service['id'] ?? '';
            $dateCompleted = $service['date'];
            $serviceDone = $service['service'];
            $item = $service['item'];
            $notes = $service['notes'];
            $cost = $service['cost'];
            include($_SERVER['DOCUMENT_ROOT'].'/views/maintenance/maintenance_list_row.php');
        }
        $helper->generateTableFooters();
    }
    public function upsertMaintenance($data)
    {
        $maintenanceModel = new \Models\ItemMaintenanceModel($data);
        $maintenanceModel->doUpsertMaintenance($data);
    }
}
