<?php
namespace Controllers;

use Api\Database;
 error_reporting(E_ALL);
ini_set('display_errors', 1);
class ItemController {
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
	public $photoURLs;
	public $documentURLs;
	public $missing;
	public $filter;
	public $value;
	public $sort;
	public function __construct($db, $data = '')

{
	$this->db = $db;
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
		$this->purchase_location = $data['purchase_location'] ?? '';

		$this->notes = $data['notes'] ?? '';
		$this->asset_tag = $data['asset_tag'] ?? '';
		$this->checked_in = $data['checked_in'] ?? '';
		$this->missing = $data['asset_tag'] ?? '';
		$this->filter = $data['filter'] ?? '';
		$this->value = $data['value'] ?? '';
		$this->sort = $data['sort'] ?? '';
	}
}
private function insertImages($id, $image_url, $imageType) {
	$sql = "INSERT INTO images (item_id, url, type) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE item_id = VALUES(item_id);";
	$stmt = $this->db->conn->prepare($sql);
	$stmt->bind_param("iss", $id, $image_url, $imageType );
 if (!$stmt->execute()) {
	 // Handle SQL error
	 echo json_encode(array("message" => "Item photo insertion failed: " . $stmt->error));
	 $stmt->close();
	 return;
 }
 else {
			 $itemId = $stmt->insert_id;
	
		 }

}
public function setThumbnail($data) {
	$image_url = $data['url'];
	$sql = "UPDATE images SET thumbnail = 1 WHERE item_id = ? and url = ?";
	$stmt = $this->db->conn->prepare($sql);
	$stmt->bind_param("is", $this->id, $image_url);
 if (!$stmt->execute()) {
	 // Handle SQL error
	 echo json_encode(array("message" => "Item photo update failed: " . $stmt->error));
	 $stmt->close();
	 return;
 }
 else {
	echo json_encode(array("message" => "Item photo set as thumb"));

		 }

}
public function addEditItem($data) {

	if ($this->id == '') {
 	   $sql = "INSERT INTO item (brand, name, category, subcategory, location, model, year, serial_number, status, purchase_price, replacement_value, purchase_location, notes, asset_tag, date_acquired) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
} else {
	$sql = "UPDATE item SET brand = ?, name = ?, category = ?, subcategory = ?, location = ?, model = ?, year = ?, serial_number = ?, status = ?, purchase_price = ?, replacement_value = ?, purchase_location = ?, notes = ?, asset_tag = ?, date_acquired = ? WHERE id = " . $this->id;
}
 	$stmt = $this->db->conn->prepare($sql);
 	$stmt->bind_param("sssssssssssssss", $this->brand, $this->name, $this->category, $this->subcategory, $this->location, $this->model, $this->year, $this->serial_number, $this->status, $this->purchase_price, $this->replacement_value, $this->purchase_location,$this->notes,$this->asset_tag, $this->date_acquired );
 if (!$stmt->execute()) {
	 // Handle SQL error
	 echo json_encode(array("message" => "Item insertion failed: " . $stmt->error));
	 $stmt->close();
	 return;
 }
 else {
			 $itemId = $stmt->insert_id;
			 if ($itemId == 0){
				 $itemId = $this->id;
			 }
			// echo $itemId;
			if ($this->photoURLs) {
				$photos = explode(",", rtrim($this->photoURLs, ','));
			  foreach ($photos as $image){
				  $image = trim($image);
				  $this->insertImages($itemId, $image, 'photo');
				  }
			  }
			  if ($this->documentURLs) {
				  $documents = explode(",", rtrim($this->documentURLs, ','));
				foreach ($documents as $image){
					$image = trim($image);
					$this->insertImages($itemId, $image, 'document');
					}
				}
			 echo json_encode(array("message" => "Item insertion success - $itemId"));
	
		 }


}
	
	public function autocomplete($data) {
	$sql = "SELECT DISTINCT ".$this->name." as value FROM item ORDER BY value ASC";
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

	public function deleteItem($data) {
		
	$sql = "UPDATE item SET status = '".$this->status ."' WHERE id = ".$this->id;
	$stmt = $this->db->conn->prepare($sql);


    if (!$stmt->execute()) {
		 // Handle SQL error
		 echo json_encode(array("message" => "Item delete failed: " . $stmt->error));
		 $stmt->close();
		 return;
	 }
	 else {
				 echo json_encode(array("message" => "Item delete success"));
		
			 }
		}

public function hardDeleteItem($data) {
		
		$checkPhoto = "SELECT photo from item where id=".$this->id;
		$checkPhotostmt = $this->db->conn->prepare($checkPhoto);
		$checkPhotostmt->execute();
		$result = $checkPhotostmt->get_result();
		//var_dump($result);

		if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
		$this->deleteImage($row['photo'], 1);
			}
		}
		$sql = "DELETE FROM items WHERE id = ".$this->id;
		$stmt = $this->db->conn->prepare($sql);
		$stmt->execute();
		if (!$stmt->execute()) {
			 // Handle SQL error
			 echo json_encode(array("message" => "Item delete failed: " . $stmt->error));
			 $stmt->close();
			 return;
		 }
		 else {
			 echo json_encode(array("message" => "Item delete success"));
			
				 }
			}
	private function deleteImage($imageName, $internal = 0){
		$targetDirectory = "../images/items/";
	
		// Construct the full path to the image
		$imagePath = $targetDirectory . $imageName;
	   // echo $imagePath;
		// Check if the file exists before attempting to delete
		if (file_exists($imagePath)) {
			// Attempt to delete the file
			if (unlink($imagePath)) {
				// Respond with success message or other appropriate response
			if (!$internal){  echo json_encode(array('status' => 'success', 'message' => 'File deleted successfully.'));
		}
			} else {
				// Respond with an error message if deletion fails
			  if (!$internal){   echo json_encode(array('status' => 'error', 'message' => 'Failed to delete the file.'));}
			}
		} else {
			// Respond with an error if the file does not exist
			if (!$internal){ echo json_encode(['status' => 'error', 'message' => 'File not found.']);}
		}
	} 	
	
	public function returnItems($data)
	{
		$sql = "SELECT * ";
	
		$sql .= " FROM item i";
		if ($this->id != '') {
			$sql.= " WHERE i.id = ( " .$this->id ." ) ";
		}	
		if ($this->missing == 'info') {
			$sql .= " WHERE (brand = ' ' OR brand IS NULL) OR (model = ' ' OR model IS NULL) OR (status = ' ' OR status IS NULL) OR (category = ' ' OR category IS NULL) OR (subcategory = ' ' OR subcategory IS NULL)";
		}
		if ($this->missing != '' && $this->missing !='info') {
			$sql .= " WHERE ".$this->missing. " = ' ' OR " .$this->missing. " IS NULL ";
		}
	
		if ($this->filter && $this->value) {
			$sql .= " WHERE ".$this->filter. " = '". $this->value."'";
		}
		if ($this->status != '') {
			$sql .= " AND status  = '" .$this->status ."'";
		}
		if ($this->status == '' && $this->filter =='') {
			$sql .= " WHERE status  = 'Current'";
		}
		$sql .= " GROUP BY  i.id";
		if ($this->sort != '') {
		$sql .= " ORDER BY " .$this->sort." DESC";
		}
		if ($this->sort == '' ) {	

		$sql .= " ORDER BY category DESC";
		}
		//echo $sql;
		$stmt = $this->db->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();
	
		if ($result->num_rows > 0) {
			$items = array();
			while ($row = $result->fetch_assoc()) {
				$items[] = $row;
			}
			echo json_encode($items);
		} else {
			echo json_encode(array("message" => "No items found."));
		}
	
		$stmt->close();
	}

	public function	returnItemImages($data) {
		$sql = "SELECT * from images where item_id=".$this->id;
		$stmt = $this->db->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows > 0) {
			$items = array();
			while ($row = $result->fetch_assoc()) {
				$items[] = $row;
			}
			echo json_encode($items);
		} else {
			//echo json_encode(array("message" => "No items found."));
		}
		
		$stmt->close();
	}
public function uploadPhoto($images, $imageType) {
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
			$uploadPath = '/Applications/XAMPP/xamppfiles/htdocs/gear/images/items/' . basename($image['name']);
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
	
		echo json_encode(array("images" => $uploadedPhotos, "imageType"=> $imageType));
	}

	
}