<?php

namespace Models;

use Api\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class HelpersModel
{
	private $db;
	public $id;
	public $id_field;
	public $table;
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
			$this->id_field = $data['id_field'] ?? 'id';
			$this->table = $data['table'] ?? '';
			$this->status = $data['status'] ?? '';
			$this->missing = $data['missing'] ?? '';
			$this->filter = $data['filter'] ?? '';
			$this->value = $data['value'] ?? '';
			$this->sort = $data['sort'] ?? '';

		}
	}
	
	public function returnAutocompleteData()
{
	$sql = "SELECT DISTINCT ".$this->filter." as value ";
	if ($this->table == 'venue' && $this->filter == 'venue_id' ){
		$sql .= ", name";	
	}
	$sql .= " FROM ".$this->table." ORDER BY value ASC";
//	echo $sql;
	$stmt = $this->db->conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
		$items = array();
		while ($row = $result->fetch_assoc()) {
			$items[] = $row;
		}
		echo json_encode($items);
		$stmt->close();
	}
}

	public function doUpdateFieldData()
{
	$sql = "UPDATE ".$this->table." SET ";
	$sql .= $this->filter ." = '". $this->value."'";
	$sql .= " WHERE ".$this->id_field." = '".$this->id."'";
	$stmt = $this->db->conn->prepare($sql);

	if (!$stmt->execute()) {
		echo json_encode(array("message" => "Update failed: " . $stmt->error));
		$stmt->close();
		return;
	} else {
		echo json_encode(array("message" => "Field updated"));
	}
		$stmt->close();
	
}

	public function doDeleteData()
	{
		if ($this->id){
		$sql = "DELETE FROM ".$this->table ;

		$sql.=" WHERE  ".$this->filter." = ". $this->id;
		$stmt = $this->db->conn->prepare($sql);
				if (!$stmt->execute()) {
			// Handle SQL error
			echo json_encode(array("message" => "Item delete failed: " . $stmt->error));
			$stmt->close();
			return;
		} else {
			echo json_encode(array("message" => "Item delete success"));
		
		}
	}
}
}