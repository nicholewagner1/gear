<?php

namespace Controllers;

use \Models\ItemEditModel;

class ItemEditController {
	
public function addEditItem($db, $data) {

   $itemModel = new \Models\ItemEditModel($db, $data);
   $itemModel->doAddEditItem();

   }
   	
   public function updateItem($db, $data) {
   
	  $itemModel = new \Models\ItemEditModel($db, $data);
	  $itemModel->doUpdateItem();
   
	  }
    public function uploadPhoto($db, $images, $imageType) {
	  
		$itemModel = new \Models\ItemEditModel($db);
		$itemModel->doUploadPhoto($images, $imageType);
	  
		}
	public function setImageType($db, $data) {
		$itemModel = new \Models\ItemEditModel($db, $data);
		$itemModel->doSetImageType($data);
		
		}
  public function deleteItem($db, $data) {
		$itemModel = new \Models\ItemEditModel($db, $data);
		$itemModel->hardDeleteItem();
		
		}
		
	public function renameImages($db, $data) {
	$itemModel = new \Models\ItemEditModel($db, $data);
	$itemModel->doRenameImages($data);
	
	}

}
