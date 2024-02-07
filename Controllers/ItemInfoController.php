<?php

namespace Controllers;

use Models\ItemInfoModel;

class ItemInfoController
{
    public function displayItemsList($view, $missing = '', $status ='', $filter='', $value ='', $sort = '')
    {
        include($_SERVER['DOCUMENT_ROOT'].'/views/item_list/item_'.$view.'_header.php');
        $data = array('filter'=> $filter, 'value'=> $value, 'missing'=> $missing, 'status'=>$status, 'sort'=>$sort);
        $itemModel = new ItemInfoModel($data);
        $items = $itemModel->returnItems();
        //var_dump($items);
        foreach ($items as $item) {
            $id = $item['id'] ?? 'edit';
            $name = $item['name'] !== '' ? $item['name'] : '';
            $brand = $item['brand'] !== '' ? $item['brand'] : '-';
            $category = $item['category'] !== '' ? $item['category'] : '-';
            $subcategory = $item['subcategory']  !== '' ? $item['subcategory'] : '';
            $model = $item['model']  !== '' ? $item['model'] : '-';
            $cost = $item['purchase_price'] !== '' ? $item['purchase_price'] : '-';
            $checked_in = $item['checked_in']  !== '' ? $item['checked_in'] : '';
            $insured = $item['insured']  !== '' ? $item['insured'] : '' ;
            $insuredCheck = $item['insured']  == '1' ? "-check text-success" : "-xmark text-warning";
            $imageURL = $item['url'] !='' ? '/images/items/'.$item['url'] : 'https://placehold.co/800?text=No+Image&font=roboto';

            include($_SERVER['DOCUMENT_ROOT'].'/views/item_list/item_'.$view.'_row.php');
        }

        include($_SERVER['DOCUMENT_ROOT'].'/views/item_list/item_'.$view.'_footer.php');
    }

    public function editItem($id = '')
    {
        if ($id != '') {
            $data = array('id'=>$id);
            $itemModel = new ItemInfoModel($data);
            $maintenanceModel = new \Models\ItemMaintenanceModel($data);
            $items = $itemModel->returnItems();
            //var_dump($items);
            foreach ($items as $item) {
                $id = $item['id'] ?? '';
                $date_acquired = $item['date_acquired'] !== '' ? $item['date_acquired'] : date('Y-m-d');
                $name = $item['name'] !== '' ? $item['name'] : '';
                $brand = $item['brand'] !== '' ? $item['brand'] : '';
                $category = $item['category'] !== '' ? $item['category'] : '';
                $subcategory = $item['subcategory']  !== '' ? $item['subcategory'] : '';
                $model = $item['model']  !== '' ? $item['model'] : '';
                $cost = $item['purchase_price'] !== '' ? $item['purchase_price'] : '';
                $checked_in = $item['checked_in']  == '1' ? 'checkedIn' : 'checkedOut';
                $images = $itemModel->returnItemImages($id);
                $maintenance = $maintenanceModel->returnAllMaintenance();

                include($_SERVER['DOCUMENT_ROOT'].'/views/item_edit/item_edit_main.php');
                if ($maintenance && $id !='') {
                    include($_SERVER['DOCUMENT_ROOT'].'/views/item_edit/item_edit_maintenance_log.php');
                }
            }
        } else {
            $date_acquired = date('Y-m-d');
            include($_SERVER['DOCUMENT_ROOT'].'/views/item_edit/item_edit_main.php');
        }

        //	include ($_SERVER['DOCUMENT_ROOT'].'/views/item_edit/item_edit_footer.php');
    }

    public function autocomplete($data)
    {
        $itemModel = new \Models\ItemInfoModel($data);
        $itemModel->returnAutocompleteData();
    }

    public function list()
    {
        $itemModel = new \Models\ItemInfoModel();
        $items = $itemModel->returnItems();
        echo json_encode($items);
    }
}
