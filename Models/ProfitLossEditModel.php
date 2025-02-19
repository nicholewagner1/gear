<?php

namespace Models;

use Api\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class ProfitLossEditModel
{
    private $db;
    public $id;
    public $gig_id;
    public $profit_loss_id;
    public $venue_payout;
    public $merch;
    public $tips;
    public $cost_to_play;
    public $show_length;
    public $venue_id;
    public $gig_notes;
    public $booking_fee;
    public $booking_fee_percent;

    public $date;
    public $name;
    public $category;
    public $subcategory;
    public $amount;
    public $paid;
    public $profit_loss_notes;
    public $tax_forms;
    public $account;

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

    public function __construct($data = '')
    {
        $this->db = new \Api\Database();
        if ($data) {
            $this->gig_id = $data['gig_id'] ?? '';
            $this->profit_loss_id = $data['id'] ?? '';
            $this->venue_payout = $data['venue_payout'] ?? '';
            $this->merch = $data['merch'] ?? '';
            $this->tips = $data['tips'] ?? '';
            $this->cost_to_play = $data['cost_to_play'] ?? '';
            $this->booking_fee = $data['booking_fee'] ?? '';
            $this->booking_fee_percent = $data['booking_fee_percent'] ?? '';

            $this->show_length = $data['show_length'] ?? '';
            $this->venue_id = $data['venue_id'] ?? '';
            $this->gig_notes = $data['gig_notes'] ?? '';

            $this->date = $data['date'] ?? '';
            $this->name = $data['name'] ?? '';
            $this->category = $data['category'] ?? '';
            $this->subcategory = $data['subcategory'] ?? '';
            $this->amount = $data['amount'] ?? '';
            $this->paid = $data['paid'] ?? '';
            $this->profit_loss_notes = $data['profit_loss_notes'] ?? '';
            $this->tax_forms = $data['tax_forms'] ?? '';
            $this->account = $data['account'] ?? '';

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
            $this->date_start = $data['date_start'] ?? '';
            $this->date_end = $data['date_end'] ?? '';
        }
    }

    public function doReturnProfitLoss()
    {
        $sql = "SELECT pl.*, g.*";
        $sql .= " FROM profit_loss pl";
        $sql .= " LEFT JOIN gig g on g.profit_loss_id = pl.id ";

        if ($this->profit_loss_id != '') {
            $sql .= " WHERE pl.id = ( " .$this->profit_loss_id ." ) ";
        }
        if ($this->filter !='' && $this->value !='') {
            $sql .= " AND ".$this->filter. " = '". $this->value."'";
        }
        $sql .= " GROUP BY pl.id";
        $sql .= " ORDER BY date DESC";
        //  echo $sql;
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
            return json_encode(array("message" => "No profit_loss found."));
        }

        $stmt->close();
    }

    public function doUpsertProfitLossData()
    {
        if ($this->profit_loss_id == '') {
            $sql = "INSERT INTO profit_loss (date, name, category, subcategory, amount, paid, notes, tax_forms, account) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } else {
            $sql = "UPDATE profit_loss SET date = ?, name = ?, category = ?, subcategory = ?, amount = ?, paid = ?, notes = ?, tax_forms = ?, account = ? WHERE id = " . $this->profit_loss_id;
        }
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("ssssiisis", $this->date, $this->name, $this->category, $this->subcategory, $this->amount, $this->paid, $this->profit_loss_notes, $this->tax_forms, $this->account);
        if (!$stmt->execute()) {
            // Handle SQL error
            echo json_encode(array("message" => "P&L insertion failed: " . $stmt->error));
            $stmt->close();
            return;
        } else {
            $pl_id = $stmt->insert_id;
            //echo $pl_id;

            if ($pl_id == 0) { //update
                $pl_id = $this->profit_loss_id;
                if ($this->category == 'Show') {
                    $this->insertGigInfo($pl_id, $this->gig_id, $this->venue_payout, $this->merch, $this->tips, $this->cost_to_play, $this->booking_fee, $this->booking_fee_percent, $this->show_length, $this->venue_id, $this->gig_notes);
                }
            } else {
                if ($this->category == 'Show') { //insert
                    $this->insertGigInfo($pl_id, '', $this->venue_payout, $this->merch, $this->tips, $this->cost_to_play, $this->booking_fee, $this->booking_fee_percent, $this->show_length, $this->venue_id, $this->gig_notes);
                }
            }
            echo json_encode(array("message" => "P&L insertion success - $pl_id", "item_id"=>$pl_id));
        }
    }


    private function insertGigInfo($pl_id, $gig_id, $venue_payout, $merch, $tips, $cost_to_play, $booking_fee, $booking_fee_percent, $show_length, $venue_id, $gig_notes)
    {
        if ($gig_id == '') {
            $sql = "INSERT INTO gig (profit_loss_id, venue_payout, merch, tips, cost_to_play, booking_fee, booking_fee_percent, show_length, venue_id, gig_notes) VALUES ($pl_id, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } else {
            $sql = "UPDATE gig SET venue_payout =?, merch =?, tips=?, cost_to_play=?, booking_fee=?, booking_fee_percent=?, show_length=?, venue_id =?, gig_notes=? WHERE gig_id = ".$gig_id;
        }
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("iiiiiiiis", $venue_payout, $merch, $tips, $cost_to_play, $booking_fee, $booking_fee_percent, $show_length, $venue_id, $gig_notes);

        if (!$stmt->execute()) {
            // Handle SQL error
            echo json_encode(array("message" => "Gig info insertion failed: " . $stmt->error));
            $stmt->close();
            return;
        } else {
            //echo json_encode(array("message" => "Gig info insertion success"));
        }
        $stmt->close();
    }

    public function doProfitLossAutocompleteData()
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
