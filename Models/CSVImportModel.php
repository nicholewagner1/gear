<?php

namespace Models;

use Api\Database;

require($_SERVER['DOCUMENT_ROOT'].'/config/environment.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
class CSVImportModel
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


    public function __construct($data = '')
    {
        $this->db = new \Api\Database();
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

    public function doUploadCSVProfitLoss()
    {
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
            $uploadFile = $_ENV['UPLOAD_PATH'] . basename($_FILES['csv_file']['name']);

            move_uploaded_file($_FILES['csv_file']['tmp_name'], $uploadFile);

            $handle = fopen($uploadFile, "r");

            if ($handle !== false) {
                // Flag to track if it's the first row
                $isFirstRow = true;

                $duplicateCheck = "SELECT id from profit_loss_clone where (date = ? and name = ? and amount = ?)";

                // Prepare the SQL statement for profit_loss table
                $sqlProfitLoss = "INSERT INTO profit_loss_clone (date, name, category, subcategory, amount, paid, tax_forms, notes, account) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtProfitLoss = $this->db->conn->prepare($sqlProfitLoss);

                $sqlProfitLossUpdate = "UPDATE profit_loss_clone SET category = ?, subcategory = ?, paid = ?, tax_forms = ? , notes = ?, account = ? WHERE id = ?";

                // Prepare the SQL statement for gig table
                $sqlGig = "INSERT INTO gig_clone (profit_loss_id, venue_payout, merch, tips, cost_to_play, show_length, venue_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmtGig = $this->db->conn->prepare($sqlGig);

                $sqlGigUpdate = "UPDATE gig_clone SET venue_payout = ?, merch = ?, tips = ?, cost_to_play = ? , show_length = ?, venue_id = ? WHERE profit_loss_id = ?";
                $stmtGigUpdate = $this->db->conn->prepare($sqlGigUpdate);
                // Prepare the SQL statement to select venue_id based on the name
                $venueSql = "SELECT venue_id FROM venue_clone WHERE name = ?";

                $newVenueSql = "INSERT INTO venue_clone (name) VALUES (?)";
                $stmtNewVenue = $this->db->conn->prepare($newVenueSql);


                // Loop through each row in the CSV file
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    // Check if it's the first row (header)
                    if ($isFirstRow) {
                        // Set the flag to false and skip processing this row
                        $isFirstRow = false;
                        continue;
                    }
                    //check for dupes in table
                    $pl_id = 0;
                    $stmtDuplicateCheck = $this->db->conn->prepare($duplicateCheck);
                    $stmtDuplicateCheck->bind_param("ssi", $data[0], $data[1], $data[4]);
                    if (!$stmtDuplicateCheck->execute()) {
                        // Handle SQL error
                        echo json_encode(array("message" => "Duplicate found but update failed: " . $stmtGig->error));
                    } else {
                        $stmtDuplicateCheck->bind_result($pl_id);
                        $stmtDuplicateCheck->fetch(); // Fetch the result
                        $stmtDuplicateCheck->close();
                        echo $pl_id;
                    }

                    if ($pl_id != 0) { //  match found
                        //upsert sql
                        $stmtProfitLossUpdate = $this->db->conn->prepare($sqlProfitLossUpdate);

                        $stmtProfitLossUpdate->bind_param("ssiissi", $data[2], $data[3], $data[5], $data[6], $data[7], $data[8], $pl_id);
                        $stmtProfitLossUpdate->execute();
                        $stmtProfitLossUpdate->close();

                        echo  json_encode(array("message" => "Item insertion - match found $pl_id"));

                        if ($data[2] == 'Show') {
                            $stmtVenue = $this->db->conn->prepare($venueSql);
                            $stmtVenue->bind_result($venueId);
                            $stmtVenue->bind_param("s", $data[9]);
                            $stmtVenue->execute();
                            $stmtVenue->fetch(); // Fetch the result
                            $stmtVenue->close();

                            // If venueId is not found, insert a new venue
                            if ($venueId == 0) {
                                $stmtNewVenue->bind_param("s", $data[9]);
                                if (!$stmtNewVenue->execute()) {
                                    // Handle SQL error
                                    echo json_encode(array("message" => "New Venue creation failed: " . $stmtNewVenue->error));
                                    $stmtNewVenue->close();
                                    return;
                                } else {
                                    // Get the insert_id from the new venue query
                                    $venueId = $stmtNewVenue->insert_id;
                                    echo json_encode(array("message" => "New Venue insertion success"));
                                }
                            } else {
                                // Bind parameters for gig table
                                $stmtGigUpdate->bind_param("iiiiiii", $data[10], $data[11], $data[12], $data[13], $data[14], $venueId, $pl_id);

                                // Execute the gig query
                                if (!$stmtGigUpdate->execute()) {
                                    // Handle SQL error
                                    echo json_encode(array("message" => "Gig update failed: " . $stmtGigUpdate->error));
                                } else {
                                    echo json_encode(array("message" => "Gig update success"));
                                }
                            }
                        }
                    } else { //no match found
                        $stmtProfitLoss->bind_param("ssssiiiss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8]);

                        // Execute the profit_loss query
                        if (!$stmtProfitLoss->execute()) {
                            // Handle SQL error
                            echo json_encode(array("message" => "Item insertion failed: " . $stmtProfitLoss->error));
                            $stmtProfitLoss->close();
                            return;
                        } else {
                            $insert_id = $stmtProfitLoss->insert_id;

                            if ($data[2] == 'Show') {
                                $stmtVenue = $this->db->conn->prepare($venueSql);
                                $stmtVenue->bind_result($venueId);
                                $stmtVenue->bind_param("s", $data[9]);
                                $stmtVenue->execute();
                                $stmtVenue->fetch(); // Fetch the result
                                $stmtVenue->close();

                                // If venueId is not found, insert a new venue
                                if ($venueId == 0) {
                                    $stmtNewVenue->bind_param("s", $data[9]);
                                    if (!$stmtNewVenue->execute()) {
                                        // Handle SQL error
                                        echo json_encode(array("message" => "New Venue creation failed: " . $stmtNewVenue->error));
                                        $stmtNewVenue->close();
                                        return;
                                    } else {
                                        // Get the insert_id from the new venue query
                                        $venueId = $stmtNewVenue->insert_id;
                                        echo json_encode(array("message" => "New Venue insertion success"));
                                    }
                                } else {
                                    // Bind parameters for gig table
                                    $stmtGig->bind_param("iiiiiii", $insert_id, $data[10], $data[11], $data[12], $data[13], $data[14], $venueId);

                                    // Execute the gig query
                                    if (!$stmtGig->execute()) {
                                        // Handle SQL error
                                        echo json_encode(array("message" => "Gig insertion failed: " . $stmtGig->error));
                                    } else {
                                        echo json_encode(array("message" => "Gig insertion success"));
                                    }
                                }
                            }
                            echo json_encode(array("message" => "New Profit Loss record success"));
                        }
                    }
                }
                // Close the prepared statements
                $stmtProfitLoss->close();
                $stmtGig->close();

                // Close the file handle
                fclose($handle);
            } else {
                // Handle the case when the file can't be opened
                echo json_encode(array("message" => "Error opening the file"));
            }
        } else {
            // Handle the case when there's an issue with the uploaded file
            echo json_encode(array("message" => "Error with the uploaded file"));
        }
    }
}
