<?php

namespace Controllers;

use \Models\HelpersModel;

class HelpersController {
	
	public function autocomplete($db, $data) {

   $helperModel = new \Models\HelpersModel($db, $data);
   $helperModel->returnAutocompleteData();

   }
   
   public function delete($db, $data) {
   
	  $helperModel = new \Models\HelpersModel($db, $data);
	  $helperModel->doDeleteData();
	  }
	public function updateField($db, $data) {
	
	  $helperModel = new \Models\HelpersModel($db, $data);
	  $helperModel->doUpdateFieldData();
	  }  
	  
   }