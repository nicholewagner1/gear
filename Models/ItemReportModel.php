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
	public $date_start;
	public $date_end;

	
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
			$this->date_start = $data['date_start'] ?? '';
			$this->date_end = $data['date_end'] ?? '';

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
	//echo $sql;
	$stmt = $this->db->conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$items = array();
		while ($row = $result->fetch_assoc()) {
			$items[] = $row;
		}
		echo json_encode ($items);
	} else {
		echo json_encode(array("message" => "No items found."));
	}

	$stmt->close();
}
	public function doInsuranceReport()
{
	$sql = "SELECT id, name, brand, model, serial_number, replacement_value from item where insured = 1";
	
	//echo $sql;
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
public function doReturnGigDetails(){

	$sql ="select v.name, avg(g.venue_payout) as venue_average, avg(g.tips) as tips_average, COUNT(g.gig_id) as played from gig g 	LEFT JOIN venue v on v.venue_id = g.venue_id group by v.name";
	$stmt = $this->db->conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();
	//echo $sql;
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
public function doReturnProfitLoss() {

	$sql ="SELECT YEAR(date) AS year, MONTH(date) AS month, SUM(pl.amount) AS total,";
	$sql .= " (SELECT SUM(amount) FROM profit_loss WHERE amount > 0 AND YEAR(date) = year AND MONTH(date) = month) AS income,";
	$sql .= " (SELECT SUM(amount) FROM profit_loss WHERE amount < 0 AND YEAR(date) = year AND MONTH(date) = month) AS expense ";
	
	$sql .= " FROM profit_loss pl ";
	if ($this->date_start && $this->date_end){
	$sql .= " WHERE date >= '". $this->date_start."' AND date <= '". $this->date_end."'";
	}
	$sql .=  " GROUP BY year, month ";
	$stmt = $this->db->conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();
//	echo $sql;
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
public function doReturnProfitLossCategory() {

	$sql ="SELECT YEAR(date) AS year, type,  SUM(pl.amount) AS total";
	$sql .= " FROM profit_loss pl ";
	$sql .= "WHERE amount < 0 ";
	if ($this->date_start && $this->date_end){
	$sql .= " AND date >= '". $this->date_start."' AND date <= '". $this->date_end."'";
	}
	$sql .=  " GROUP BY year, type ";
//	echo $sql;
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


