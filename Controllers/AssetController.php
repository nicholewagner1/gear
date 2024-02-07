<?php

namespace Controllers;

use Models\AssetModel;

class AssetController
{
    public function updateItemCheckinStatus($data)
    {
        $assetModel = new \Models\AssetModel();
        $assetModel->changeCheckInStatus($data);
    }

    public function returnCheckedOutItems()
    {
        $assetModel = new \Models\AssetModel();
        $packedItems = $assetModel->getCheckedOutItems();
        include($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_packed.php');
    }
}
