<?php

namespace Controllers;

use Api\Database;

class MaintenanceController
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

    public function addEditMaintenance()
    {
        if ($this->id == '') {
            $insertMaintenanceSQL = "INSERT INTO maintenance (date, service, item_id, notes, cost) VALUES (?, ?, ?, ?, ?)";
        }
        if ($this->id != '') {
            $insertMaintenanceSQL = "UPDATE maintenance SET date = ?, service = ?, item_id = ?, notes = ?, cost = ? WHERE id =". $this->id;
        }
        $stmtMaintenance = $this->db->conn->prepare($insertMaintenanceSQL);
        $stmtMaintenance->bind_param("ssisi", $this->date, $this->service, $this->itemId, $this->notes, $this->cost);
        //echo $insertOutfitSQL;
        if (!$stmtMaintenance->execute()) {
            // Handle SQL error
            echo json_encode(array("message" => "Outfit insertion failed: " . $stmtMaintenance->error));
            $stmtMaintenance->close();
            return;
        } else {
            //echo $stmtOutfit->insert_id ;
            //echo $this->id;
            if ($stmtMaintenance->insert_id > '0') {
                $maintenanceId = $stmtMaintenance->insert_id;
            } else {
                $maintenanceId = $this->id;
            }
            //	echo $outfitId;
            echo json_encode(array("message" => "Maintenance items added successful"));

            $stmtOutfit->close();

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
                $outfits[] = [
                    'id' => $row['id'],
                    'date' => $row['date'],
                    'item' => $row['item_name'],
                    'service' => $row['service'],
                    'notes' => $row['notes'],
                    'cost' => $row['cost'],


                ];
            }


            //		return $outfits;

            $jsonResult = json_encode($outfits, JSON_PRETTY_PRINT);
            // Output the JSON result
            header('Content-Type: application/json');
            echo $jsonResult;
        } else {
            //echo json_encode(array("message" => "No outfits found."));
        }

        $stmt->close();
    }

    // Helper function to parse item_ids and extract item_id and image_id
    private function parseItemIds($itemIds)
    {
        $items = [];
        $itemPairs = explode(',', $itemIds);

        foreach ($itemPairs as $pair) {
            list($itemId, $imageId, $itemName) = explode(':', $pair);
            $items[] = [
                'item_id' => $itemId,
                'image_url' => $imageId,
                'item_name' => $itemName,
            ];
        }

        return $items;
    }
    public function getMaintenanceValues($data)
    {
        $sql = "SELECT DISTINCT service as value FROM maintenance ORDER BY service ASC";
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
