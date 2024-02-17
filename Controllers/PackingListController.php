<?php

namespace Controllers;

use Models\PackingListModel;
use Models\AssetModel;

class PackingListController
{
    public function listPackingList()
    {
        $packingModel = new \Models\PackingListModel();
        $lists = json_decode($packingModel->returnPackingLists(), true);
        include($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_view.php');
    }

    public function showPackingList($id = '')
    {
        $data = array('id'=>$id);
        $packingModel = new \Models\PackingListModel($data);
        $lists = json_decode($packingModel->returnPackingLists(), true);
        include($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_edit_header.php');

        if ($id != '') {
            include($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_edit_row.php');
        }

        include($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_edit_footer.php');
    }

    public function returnCheckedOutItems($id = '')
    {
        $data = array('id'=> $id);

        $assetModel = new \Models\AssetModel();
        $checkedOutItems = $assetModel->getCheckedOutItems();

        $packingModel = new \Models\PackingListModel($data);
        $items = $packingModel->checkPackingLists();
        $packedItems = json_decode($items, true);

        include($_SERVER['DOCUMENT_ROOT'].'/views/packing/packing_list_packed.php');
    }
    public function updatePackingList($data)
    {
        $packingModel = new \Models\PackingListModel($data);
        $packingModel->updatePackingListItems();
    }

    public function deleteList($data)
    {
        $packingModel = new \Models\PackingListModel($data);
        $packingModel->deletePackingList();
    }
}
