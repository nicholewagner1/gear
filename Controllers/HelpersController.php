<?php

namespace Controllers;

use \Models\HelpersModel;

class HelpersController {
	
	public function generateTableHeaders($id, $columns, $class = 'mt-3'){
			echo '<table id="'.$id.'" class="table "'.$class.'">';
			echo '<thead>';
			echo '<tr>';
			foreach ($columns as $column) {
				echo '<th>' . htmlspecialchars($column) . '</th>';
			}
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

		}
	public function generateTableFooters(){

		echo '</tbody>';
		echo '</table>';
	}
	
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