<?php

namespace Models;

use Api\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class ItemMaintenanceModel
{
	private $db;
	public $id;
	public $dateToday;
	public $date;
	public $itemId;
	public $service;
	public $notes;
	public $cost;
	
	public function __construct($db, $data = '')
	{
		$this->db = $db;
		$this->dateToday = date("Y-m-d H:i:s");
		if ($data) {
			$this->id = $data['id'] ?? '';
			$this->date = $data['date'] ?? $this->dateToday;
			$this->itemId = $data['item'] ?? '';
			$this->service = $data['service'] ?? '';
			$this->notes = $data['notes'] ?? '';
			$this->cost = $data['cost'] ?? '';
	
		}
	}
	
	public function returnAllMaintenance()
	{
		$sql = "SELECT maintenance.*, i.name as item_name";
		$sql .= " FROM maintenance";
		$sql .= " LEFT JOIN item i ON i.id = maintenance.item_id";
		if ($this->id != '') {
			$sql .= " WHERE maintenance.item_id = " . $this->id;
		}
		$sql .= " ORDER BY maintenance.date DESC";
	
		$stmt = $this->db->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows > 0) {
			$outfits = [];
	
			foreach ($result as $row) {
				// Create a nested structure
				$maintenance[] = [
					'id' => $row['id'],
					'date' => $row['date'],
					'item' => $row['item_name'],
					'service' => $row['service'],
					'notes' => $row['notes'],
					'cost' => $row['cost'],
				];
			}
			return $maintenance;
		} else {
			
		}
	
		$stmt->close();
	}

}