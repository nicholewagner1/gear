<?php

namespace Models;

require($_SERVER['DOCUMENT_ROOT'].'/config/environment.php');

use Api\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class ItemEditModel
{
    private $db;
    public $id;
    public $photos;
    public $brand;
    public $name;
    public $category;
    public $subcategory;
    public $model;
    public $status;
    public $serial_number;
    public $date_acquired;
    public $purchase_location;
    public $dateToday;
    public $year;
    public $purchase_price;
    public $replacement_value;
    public $notes;
    public $asset_tag;
    public $checked_in;
    public $location;
    public $insured;
    public $photoURLs;
    public $documentURLs;
    public $missing;
    public $filter;
    public $value;
    public $sort;
    public function __construct($data = '')
    {
        $this->db = new \Api\Database();
        $this->dateToday = date("Y-m-d H:i:s");
        if ($data) {
            $this->id = $data['id'] ?? '';
            $this->photos = $data['photos[]'] ?? '';
            $this->photoURLs = $data['photo'] ?? '';
            $this->documentURLs = $data['document'] ?? '';
            $this->brand = $data['brand'] ?? '';
            $this->name = $data['name'] ?? '';
            $this->category = $data['category'] ?? '';
            $this->subcategory = $data['subcategory'] ?? '';
            $this->location = $data['location'] ?? '';
            $this->model = $data['model'] ?? '';
            $this->year = $data['year'] ?? '';
            $this->serial_number = $data['serial_number'] ?? '';
            $this->date_acquired = $data['date_acquired'] ?? '';
            $this->status = $data['status'] ?? '';
            $this->purchase_price = $data['purchase_price'] ?? '';
            $this->replacement_value = $data['replacement_value'] ?? '';
            $this->insured = $data['insured'] ?? '';
            $this->purchase_location = $data['purchase_location'] ?? '';
            $this->notes = $data['notes'] ?? '';
            $this->asset_tag = $data['asset_tag'] ?? '';
            $this->checked_in = $data['checked_in'] ?? '';
            $this->missing = $data['missing'] ?? '';
            $this->filter = $data['filter'] ?? '';
            $this->value = $data['value'] ?? '';
            $this->sort = $data['sort'] ?? '';
        }
    }

    public function doUpdateItem()
    {
        $sql = "UPDATE item SET ".$this->filter." = '".$this->value."'  WHERE id = " . $this->id;
        //echo $sql;
        $stmt = $this->db->conn->prepare($sql);
        if (!$stmt->execute()) {
            // Handle SQL error
            echo json_encode(array("message" => "Item insertion failed: " . $stmt->error));
            $stmt->close();
            return;
        } else {
            $itemId = $stmt->insert_id;
            echo json_encode(array("message" => "Item update success"));
        }
    }
    public function doAddEditItem()
    {
        if ($this->id == '') {
            $sql = "INSERT INTO item (brand, name, category, subcategory, location, model, year, serial_number, status, purchase_price, replacement_value, purchase_location, notes, asset_tag, date_acquired, insured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } else {
            $sql = "UPDATE item SET brand = ?, name = ?, category = ?, subcategory = ?, location = ?, model = ?, year = ?, serial_number = ?, status = ?, purchase_price = ?, replacement_value = ?, purchase_location = ?, notes = ?, asset_tag = ?, date_acquired = ?, insured = ? WHERE id = " . $this->id;
        }
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("sssssssssssssssi", $this->brand, $this->name, $this->category, $this->subcategory, $this->location, $this->model, $this->year, $this->serial_number, $this->status, $this->purchase_price, $this->replacement_value, $this->purchase_location, $this->notes, $this->asset_tag, $this->date_acquired, $this->insured);
        if (!$stmt->execute()) {
            // Handle SQL error
            echo json_encode(array("message" => "Item insertion failed: " . $stmt->error));
            $stmt->close();
            return;
        } else {
            $itemId = $stmt->insert_id;
            if ($itemId == 0) {
                $itemId = $this->id;
            }
            // echo $itemId;
            if ($this->photoURLs) {
                $photos = explode(",", rtrim($this->photoURLs, ','));
                foreach ($photos as $image) {
                    $image = trim($image);
                    $this->insertImages($itemId, $image, 'photo');
                }
            }
            if ($this->documentURLs) {
                $documents = explode(",", rtrim($this->documentURLs, ','));
                foreach ($documents as $image) {
                    $image = trim($image);
                    $this->insertImages($itemId, $image, 'document');
                }
            }
            echo json_encode(array("message" => "Item insertion success - $itemId", "item_id"=>$itemId));
        }
    }

    private function insertImages($id, $image_url, $imageType)
    {
        $sql = "INSERT INTO images (item_id, url, type) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE item_id = VALUES(item_id);";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("iss", $id, $image_url, $imageType);
        if (!$stmt->execute()) {
            // Handle SQL error
            echo json_encode(array("message" => "Item photo insertion failed: " . $stmt->error));
            $stmt->close();
            return;
        } else {
            $itemId = $stmt->insert_id;
        }
    }
    public function doSetImageType($data)
    {
        $image_url = urlencode($data['url']);
        $image_type = $data['type'];
        $set = $data['set'];
        $sql = "UPDATE images SET ".$image_type." = ".$set." WHERE item_id = ".$this->id." and url ='" .$image_url."'";
        // echo $sql;
        $stmt = $this->db->conn->prepare($sql);
        if (!$stmt->execute()) {
            // Handle SQL error
            echo json_encode(array("message" => "Item photo update failed: " . $stmt->error));
            $stmt->close();
            return;
        } else {
            echo json_encode(array("message" => "Item photo set as thumb"));
            $stmt->close();
        }
    }


    public function deleteItem($data)
    {
        $sql = "UPDATE item SET status = '".$this->status ."' WHERE id = ".$this->id;
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

    public function hardDeleteItem()
    {
        $checkPhoto = "SELECT url from images where item_id=".$this->id;
        $checkPhotostmt = $this->db->conn->prepare($checkPhoto);
        $checkPhotostmt->execute();
        $result = $checkPhotostmt->get_result();
        //var_dump($result);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->deleteImage($row['url'], 1);
            }
        }
        $checkPhotostmt->close();

        $deletePhoto = "DELETE from images where item_id=".$this->id;
        $deletePhotostmt = $this->db->conn->prepare($deletePhoto);
        $deletePhotostmt->execute();
        $resultDelete = $deletePhotostmt->get_result();
        $deletePhotostmt->close();
        $sql = "DELETE FROM item WHERE id = ".$this->id;
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        if (!$stmt->execute()) {
            // Handle SQL error
            echo json_encode(array("message" => "Item delete failed: " . $stmt->error));
            $stmt->close();
            return;
        } else {
            echo json_encode(array("message" => "Item delete success"));
        }
        $stmt->close();
    }
    private function deleteImage($imageName, $internal = 0)
    {
        $targetDirectory = "../images/items/";

        // Construct the full path to the image
        $imagePath = $targetDirectory . $imageName;
        // echo $imagePath;
        // Check if the file exists before attempting to delete
        if (file_exists($imagePath)) {
            // Attempt to delete the file
            if (unlink($imagePath)) {
                // Respond with success message or other appropriate response
                if (!$internal) {
                    echo json_encode(array('status' => 'success', 'message' => 'File deleted successfully.'));
                }
            } else {
                // Respond with an error message if deletion fails
                if (!$internal) {
                    echo json_encode(array('status' => 'error', 'message' => 'Failed to delete the file.'));
                }
            }
        } else {
            // Respond with an error if the file does not exist
            if (!$internal) {
                echo json_encode(['status' => 'error', 'message' => 'File not found.']);
            }
        }
    }


    public function doUploadPhoto($images, $imageType)
    {
        $uploadedPhotos = [];

        // If $images is not an array, convert it to an array to handle single file upload
        if (!is_array($images)) {
            $images = [$images];
        }
        foreach ($images as $image) {
            if (!isset($image)) {
                continue; // Skip if no file uploaded
            }
            //var_dump($image);
            $uploadPath = $_ENV['IMAGE_UPLOAD_PATH'] . basename($image['name']);
            $fileType = strtolower(pathinfo($uploadPath, PATHINFO_EXTENSION));

            // Allow only certain file types (adjust as needed)
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
            if (!in_array($fileType, $allowedTypes)) {
                echo json_encode(array("message" => "Invalid file type."));
                exit;
            }

            if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
                $uploadedPhotos[] = basename($image['name']);
            } else {
                echo json_encode(array("message" => "File upload failed."));
                exit;
            }
        }

        echo json_encode(array("images" => $uploadedPhotos, "imageType" => $imageType));
    }

    public function doRenameImages()
    {
        $folderPath = $_ENV['IMAGE_UPLOAD_PATH'];

        $query = "SELECT images.image_id, images.item_id, images.type, images.url, images.serial, images.thumbnail, item.name AS item_name  
				  FROM images 
				  JOIN item ON images.item_id = item.id";

        $stmt = $this->db->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die('Error fetching data from the database: ' . $this->db->conn->error);
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imageId = $row['image_id'];
                $itemName = str_replace(' ', '', $row['item_name']);
                $itemName = preg_replace('/[^A-Za-z0-9\-]/', '', $itemName);
                $imageType = $row['type'];
                $imageSerial = $row['serial'];
                $isThumbnail = $row['thumbnail'];

                // Build the new filename
                $newFilename = $itemName . '_' . $imageType;
                if ($imageSerial == 1) {
                    $newFilename .= '_serial';
                }
                if ($isThumbnail == 1) {
                    $newFilename .= '_thumbnail';
                }
                $newFilename .= '_' . $imageId;

                // Get the current file extension
                $extension = pathinfo($row['url'], PATHINFO_EXTENSION);

                // Build the old and new file paths
                $oldFilePath = $folderPath . '/' . $row['url'];
                $newFilePath = $folderPath . '/' . $newFilename . '.' . $extension;
                $newFileURL = $newFilename . '.' . $extension;

                // Rename the file
                if (rename($oldFilePath, $newFilePath)) {
                    // Update the database with the new filename
                    $updateQuery = "UPDATE images SET url = ? WHERE image_id = ?";
                    $stmt2 = $this->db->conn->prepare($updateQuery);
                    $stmt2->bind_param("si", $newFileURL, $imageId);
                    $stmt2->execute();

                    if ($stmt2->affected_rows > 0) {
                        echo "File '$row[url]' renamed to '$newFilename.$extension' and database updated.\n";
                    } else {
                        echo "File '$row[url]' not renamed - no update needed.\n";
                    }
                } else {
                    echo "Failed to rename file '$row[url]'.\n";
                }
            }
        }

        $stmt->close();
        echo json_encode($result);
    }
}
