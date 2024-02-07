<?php

namespace Controllers;

use Models\ItemEditModel;

class ItemEditController
{
    public function addEditItem($data)
    {
        $itemModel = new \Models\ItemEditModel($data);
        $itemModel->doAddEditItem();
    }

    public function updateItem($data)
    {
        $itemModel = new \Models\ItemEditModel($data);
        $itemModel->doUpdateItem();
    }
    public function uploadPhoto($images, $imageType)
    {
        $itemModel = new \Models\ItemEditModel();
        $itemModel->doUploadPhoto($images, $imageType);
    }
    public function setImageType($data)
    {
        $itemModel = new \Models\ItemEditModel($data);
        $itemModel->doSetImageType($data);
    }
    public function deleteItem($data)
    {
        $itemModel = new \Models\ItemEditModel($data);
        $itemModel->hardDeleteItem();
    }

    public function renameImages($data)
    {
        $itemModel = new \Models\ItemEditModel($data);
        $itemModel->doRenameImages($data);
    }
}
