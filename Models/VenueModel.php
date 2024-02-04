<?php

namespace Models;

use Api\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class VenueModel
{
	private $db;
	public $id;
	public $gig_id;
	public $venue_id;
	
	public $venue_name;
	public $booking_contact;
	public $city;
	public $state;
	public $country;
	public $venue_type;
	public $roundtrip_mileage;
	public $geocode;
	public $venue_status;
	
	public $filter;
	public $value;
	public $date_start;
	public $date_end;
	
	public $autocomplete;
	
	public function __construct($db, $data = '')
	{
		$this->db = $db;
		if ($data) {
			
			 $this->venue_id = $data['venue_id'] ?? '';
			 
			 $this->venue_name = $data['venue_name'] ?? '';
			 $this->booking_contact = $data['booking_contact'] ?? '';
			 $this->city = $data['city'] ?? '';
			 $this->state = $data['state'] ?? '';
			 $this->country = $data['country'] ?? '';
			 $this->venue_type = $data['venue_type'] ?? '';
			 $this->roundtrip_mileage = $data['roundtrip_mileage'] ?? '';
			 $this->geocode = $data['geocode'] ?? '';
			 $this->venue_status = $data['venue_status'] ?? '';
			 
			 $this->filter = $data['filter'] ?? '';
			 $this->value = $data['value'] ?? '';
			 $this->autocomplete = $data['autocomplete'] ?? '';
		}
	}
	
	public function doReturnVenues()
{
	if ($this->autocomplete == '1') {
		$sql = "SELECT venue_id, name";
	}
	else {
	$sql = "SELECT *";
	}
	$sql .= " FROM venue";
//	$sql .= " LEFT JOIN gig g on g.profit_loss_id = pl.id ";

	if ($this->venue_id != '') {
		$sql .= " WHERE venue_id = ( " .$this->venue_id ." ) ";
	}
	if ($this->filter !='' && $this->value !='') {
		$sql .= " AND ".$this->filter. " = '". $this->value."'";
	}
	$sql .= " ORDER BY name DESC";
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
		echo json_encode(array("message" => "No venues found."));
	}

	$stmt->close();
}




public function doUpsertProfitLossData(){
	if ($this->id == '') {
			$sql = "INSERT INTO profit_loss (date, name, type, amount, paid, notes, tax_forms, account) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		} else {
			$sql = "UPDATE profit_loss SET date = ?, name = ?, type = ?, amount = ?, paid = ?, notes = ?, tax_forms = ?, account = ? WHERE id = " . $this->id;
		}
		$stmt = $this->db->conn->prepare($sql);
		$stmt->bind_param("sssiisis", $this->date, $this->name, $this->type, $this->amount, $this->paid, $this->profit_loss_notes, $this->tax_forms, $this->account);
		if (!$stmt->execute()) {
			// Handle SQL error
			echo json_encode(array("message" => "P&L insertion failed: " . $stmt->error));
			$stmt->close();
			return;
		} else {
			$pl_id = $stmt->insert_id;
			if ($pl_id == 0) { //update
				$pl_id = $this->id;
				if ($this->venue_payout) {
					$this->insertGigInfo($pl_id, $this->gig_id, $this->venue_payout, $this->merch, $this->tips, $this->cost_to_play, $this->show_length, $this->venue_id, $this->gig_notes);
				}
			}
			else {;
			if ($this->venue_payout) { //insert
					$this->insertGigInfo($pl_id, '', $this->venue_payout, $this->merch, $this->tips, $this->cost_to_play, $this->show_length, $this->venue_id, $this->gig_notes);
				}
			}
			echo json_encode(array("message" => "P&L insertion success - $pl_id"));
	
		}
	
	}


private function insertGigInfo($pl_id, $gig_id, $venue_payout, $merch, $tips, $cost_to_play, $show_length, $venue_id, $gig_notes) {
	if ($gig_id == ''){
	$sql = "INSERT INTO gig_info (profit_loss_id, venue_payout, merch, tips, cost_to_play, show_length, venue_id, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";}
	else {
		$sql = "UPSERT INTO gig_info SET profit_loss_id = ?, venue_payout =?, merch =?, tips=?, cost_to_play=?, show_length=?, venue_id =?, notes=? WHERE gig_id =".$gig_id;
	}
	$stmt = $this->db->conn->prepare($sql);
	$stmt->bind_param("iiiiiiis", $pl_id, $venue_payout, $merch, $tips, $cost_to_play, $show_length, $venue_id, $gig_notes);
	
	if (!$stmt->execute()) {
		// Handle SQL error
		echo json_encode(array("message" => "Gig info insertion failed: " . $stmt->error));
		$stmt->close();
		return;
	} else {
		echo json_encode(array("message" => "Gig info insertion success"));
	}
	$stmt->close();
}

	public function returnProfitLossAutocompleteData()
{
	$sql = "SELECT DISTINCT ".$this->filter." as value FROM profit_loss ORDER BY value ASC";
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

}