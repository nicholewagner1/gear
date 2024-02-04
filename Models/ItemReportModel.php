<?php

namespace Models;

use Api\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class ItemReportModel
{
	private $db;
	public $id;
	public $status;
	public $missing;
	public $filter;
	public $value;
	public $sort;

	
	public function __construct($db, $data = '')
	{
		$this->db = $db;
		if ($data) {
			$this->id = $data['id'] ?? '';
			$this->status = $data['status'] ?? '';
			$this->missing = $data['missing'] ?? '';
			$this->filter = $data['filter'] ?? '';
			$this->value = $data['value'] ?? '';
			$this->sort = $data['sort'] ?? '';

		}
	}
	
	public function returnValues()
{
	$sql = "SELECT SUM(i.".$this->value.") as value, COUNT(i.id) as count, ".$this->filter." as filter";
	$sql .= " FROM item i";
	
	if ($this->status != '') {
		$sql .= " WHERE status  = '".$this->status."' AND ".$this->filter." != ''" ;
	}
  
	$sql .= " GROUP BY  i.".$this->filter;
	$sql .= " ORDER BY ".$this->sort." DESC";
	echo $sql;
	$stmt = $this->db->conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$items = array();
		while ($row = $result->fetch_assoc()) {
			$items[] = $row;
		}
		return ($items);
	} else {
		echo json_encode(array("message" => "No items found."));
	}

	$stmt->close();
}
public function returnCounts()
{
	$sql = "SELECT COUNT(i.id) as count, ".$this->filter." as filter";
	$sql .= " FROM item i";
	
	if ($this->status != '') {
		$sql .= " WHERE status  = '".$this->status."'" ;
	}
	$sql .= " GROUP BY  i.".$this->filter;
	$stmt = $this->db->conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$items = array();
		while ($row = $result->fetch_assoc()) {
			$items[] = $row;
		}
		return ($items);
	} else {
		echo json_encode(array("message" => "No items found."));
	}

	$stmt->close();
}

}