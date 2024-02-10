<?php

namespace Models;

use Api\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class VenueModel
{
    private $db;
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

    public function __construct($data = '')
    {
        $this->db = new \Api\Database();
        if ($data) {
            $this->venue_id = $data['id'] ?? '';

            $this->venue_name = $data['name'] ?? '';
            $this->booking_contact = $data['booking_contact'] ?? '';
            $this->city = $data['city'] ?? '';
            $this->state = $data['state'] ?? '';
            $this->country = $data['country'] ?? '';
            $this->venue_type = $data['venue_type'] ?? '';
            $this->roundtrip_mileage = $data['roundtrip_mileage'] ?? '';
            $this->geocode = $data['geocode'] ?? '';
            $this->venue_status = $data['status'] ?? '';

            $this->filter = $data['filter'] ?? '';
            $this->value = $data['value'] ?? '';
            $this->autocomplete = $data['autocomplete'] ?? '';
        }
    }

    public function doReturnVenues()
    {
        if ($this->autocomplete == '1') {
            $sql = "SELECT venue_id, name";
        } else {
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
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $items = array();
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            return $items;
        } else {
            echo json_encode(array("message" => "No venues found."));
        }
        $stmt->close();
    }

    public function doReturnGigsAtVenue()
    {
        $sql = "SELECT pl.id, pl.date, pl.name, g.gig_notes";

        $sql .= " FROM profit_loss pl";
        $sql .= " LEFT JOIN gig g on g.profit_loss_id = pl.id ";

        $sql .= " WHERE g.venue_id = ( " .$this->venue_id ." ) ";

        $sql .= " ORDER BY date DESC";
        //echo $sql;
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $items = array();
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            return $items;
        } else {
            echo json_encode(array("message" => "No gigs at this Venue found."));
        }
        $stmt->close();
    }

    public function doUpsertVenueData()
    {
        if ($this->venue_id == '') {
            $sql = "INSERT INTO venue (name, booking_contact, city, state, country, venue_type, roundtrip_mileage, geocode, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } else {
            $sql = "UPDATE venue SET name = ?, booking_contact = ?, city = ?, state = ?, country = ?, venue_type = ?, roundtrip_mileage = ?, geocode = ?, status = ? WHERE venue_id = " . $this->venue_id;
        }
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("ssssssiss", $this->venue_name, $this->booking_contact, $this->city, $this->state, $this->country, $this->venue_type, $this->roundtrip_mileage, $this->geocode, $this->venue_status);
        if (!$stmt->execute()) {
            // Handle SQL error
            echo json_encode(array("message" => "Venue insertion failed: " . $stmt->error));
            $stmt->close();
            return;
        } else {
            $venue_id = $stmt->insert_id;
            if ($venue_id == 0) { //update
                $venue_id = $this->venue_id;
            }
        }
        echo json_encode(array("message" => "Venue insertion success - $venue_id"));
    }


    private function insertGigInfo($pl_id, $gig_id, $venue_payout, $merch, $tips, $cost_to_play, $show_length, $venue_id, $gig_notes)
    {
        if ($gig_id == '') {
            $sql = "INSERT INTO gig_info (profit_loss_id, venue_payout, merch, tips, cost_to_play, show_length, venue_id, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        } else {
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

    public function returnVenueAutocompleteData()
    {
        $sql = "SELECT DISTINCT ".$this->filter." as value FROM venue ORDER BY value ASC";
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
