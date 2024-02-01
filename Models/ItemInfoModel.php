<?php

namespace Models;

use Api\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class ItemInfoModel
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
	
	public function returnItems()
{
	$sql = "SELECT i.*, (SELECT url FROM images WHERE item_id = i.id AND thumbnail = 1) as url ";
	$sql .= " FROM item i";
	$sql .= " LEFT JOIN images img ON img.item_id = i.id";
	
	if ($this->id != '') {
		$sql .= " WHERE i.id = ( " .$this->id ." ) ";
	}
	if ($this->missing == 'info') {
		$sql .= " WHERE (brand = ' ' OR brand IS NULL) OR (model = ' ' OR model IS NULL) OR (status = ' ' OR status IS NULL) OR (category = ' ' OR category IS NULL) OR (subcategory = ' ' OR subcategory IS NULL)";
	}
	if ($this->status == '' && $this->id == '') {
		$sql .= " WHERE status  = 'Current' ";
	}
	if ($this->status != '' && $this->id == '') {
		$sql .= " AND status  = ".$this->status ;
	}
	if ($this->missing != '' && $this->missing != 'info') {
		$sql .= " AND ".$this->missing. " = ' ' OR " .$this->missing. " IS NULL ";
	}
	if ($this->filter !='' && $this->value !='') {
		$sql .= " AND ".$this->filter. " = '". $this->value."'";
	}
  
	$sql .= " GROUP BY  i.id";
	if ($this->sort != '') {
		$sql .= " ORDER BY " .$this->sort." DESC";
	}
	if ($this->sort == '') {

		$sql .= " ORDER BY category DESC";
	}
 // echo $sql;
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

public function returnItemImages($id)
{
	$sql = "SELECT * from images where item_id=".$id." ORDER BY thumbnail DESC , serial DESC";
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
		//echo json_encode(array("message" => "No items found."));
	}

	$stmt->close();
}
}