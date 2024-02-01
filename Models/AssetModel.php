<?php

namespace Models;

use Api\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class AssetModel {
	private $db;
	public $asset_tag;
	public $checked_in;
	
	public function __construct($db, $data = '')
	{
		$this->db = $db;
		if ($data) {
			$this->asset_tag = $data['asset_tag'] ?? '';
			$this->checked_in = $data['checked_in'] ?? '';
	
		}
	}
	public function changeCheckInStatus($data)
		{
			$asset_tag = $_GET['asset_tag'];
			$checked_in = $_GET['check_in'];
			$today = date("Y-m-d H:i:s");
	
			$apiCache = $_SERVER['DOCUMENT_ROOT'].'/cache';
	
			$sql = "UPDATE item SET checked_in = '".$checked_in."' , check_date = '".$today."' WHERE asset_tag = '" . $asset_tag."'";
		  //  echo $sql;
			 file_put_contents($apiCache.'/log.txt', $sql." \n", FILE_APPEND);
	
	
			$stmt = $this->db->conn->prepare($sql);
			if (!$stmt->execute()) {
				// Handle SQL error
				echo json_encode(array("message" => "Item check in out failed: " . $stmt->error));
				$stmt->close();
				return;
			} else {
				$itemId = $stmt->insert_id;
				echo json_encode(array("message" => "Item checked in out success"));
			}
		}
	
	
		public function getCheckedOutItems()
		{
			$sql = "SELECT id, name from item where checked_in = 0 ORDER BY subcategory";
			$stmt = $this->db->conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				$items[] = $row;
			}
			return ($items);
			$stmt->close();
		}
	
}