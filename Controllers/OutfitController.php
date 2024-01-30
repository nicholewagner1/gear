<?php
namespace Controllers;

use Api\Database;

class OutfitController {
	private $db;
	public $id;
	public $dateToday;
	public $date;
	public $itemIds;
	public $vibe;

	
	public function __construct($db, $data = '')

{
	$this->db = $db;
	$this->dateToday = date("Y-m-d H:i:s");
	if ($data) {
		$this->id = $data['id'] ?? '';
		$this->date = $data['date'] ?? $this->dateToday;
		$this->itemIds = $data['items'] ?? '';
		$this->vibe = $data['vibe'] ?? '';

	}
}

public function addEditOutfit() {
	if ($this->id == '') {
	$insertOutfitSQL = "INSERT INTO outfits (date_worn, vibe) VALUES (?, ?)";
	}
	if ($this->id != '') {
	$insertOutfitSQL = "UPDATE outfits SET date_worn = ?, vibe = ? WHERE id =". $this->id;
	}
$stmtOutfit = $this->db->conn->prepare($insertOutfitSQL);
$stmtOutfit->bind_param("ss",$this->date, $this->vibe);
	//echo $insertOutfitSQL;
if (!$stmtOutfit->execute()) {
	// Handle SQL error
	echo json_encode(array("message" => "Outfit insertion failed: " . $stmtOutfit->error));
	$stmtOutfit->close();
	return;
	}
	else {
		//echo $stmtOutfit->insert_id ;
		//echo $this->id;
			if ($stmtOutfit->insert_id > '0'){
				$outfitId = $stmtOutfit->insert_id;
			}
			else {
				$outfitId = $this->id;
			}
		//	echo $outfitId;
			$this->itemOufits($outfitId, $this->itemIds);
			echo json_encode(array("message" => "Outfit items added successfull"));

			$stmtOutfit->close();

}
}
	private function itemOufits($outfitId, $itemIds) {
		$removeOutfitItemsSQL = "DELETE FROM outfit_items WHERE outfit_id = ? AND item_id NOT IN (" . implode(",", $itemIds) . ")";
		$stmtRemove = $this->db->conn->prepare($removeOutfitItemsSQL);
		$stmtRemove->bind_param("i", $outfitId);
		$stmtRemove->execute();
		$stmtRemove->close();
	
		$insertOutfitItemsSQL = "INSERT IGNORE INTO outfit_items (outfit_id, item_id) VALUES (?, ?)";
		$stmtInsert = $this->db->conn->prepare($insertOutfitItemsSQL);
	
		foreach ($itemIds as $itemId) {
			$stmtInsert->bind_param("ii", $outfitId, $itemId);
			$this->updateItemLastWorn($itemId);
			$stmtInsert->execute();
		}
	
		$stmtInsert->close();
	}

	
	public function returnAllOutfits()
	{
			$sql = "SELECT outfits.*, ";
			$sql .= " GROUP_CONCAT(items.id) AS item_ids, ";
			$sql .= " GROUP_CONCAT(items.name) AS item_names, ";
			$sql .= " GROUP_CONCAT(CONCAT(items.id, ':', items.photo, ':', items.name)) AS item_images"; // Concatenate item_id and image_id
			$sql .= " FROM outfits";
			$sql .= " LEFT JOIN outfit_items oi ON oi.outfit_id = outfits.id";
			$sql .= " LEFT JOIN items ON items.id = oi.item_id";
			if ($this->id != '') {
				$sql .= " WHERE outfits.id = " . $this->id;
			}
			$sql .= " GROUP BY outfits.id";
			$sql .= " ORDER BY outfits.date_worn DESC";
		
			$stmt = $this->db->conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($result->num_rows > 0) {
				$outfits = [];
		
				foreach ($result as $row) {
					// Create a nested structure
					$outfits[] = [
						'id' => $row['id'],
						'date' => $row['date_worn'],
						'items'=>$row['item_ids'],
						'itemImages' => $this->parseItemIds($row['item_images']), // Parse item_ids to an array
						'vibe' => $row['vibe'],
					];
				}
			
		
	//		return $outfits;
	
			$jsonResult = json_encode($outfits, JSON_PRETTY_PRINT);
			// Output the JSON result
			header('Content-Type: application/json');
			echo $jsonResult;
				} else {
			echo json_encode(array("message" => "No outfits found."));
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
				'item_name' =>$itemName,
			];
		}
	
		return $items;
	}
	public function autocompleteVibe($data) {
		$sql = "SELECT DISTINCT vibe as value FROM outfits ORDER BY vibe ASC";
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
	private function updateItemLastWorn($itemId)
	{
		$sql = "UPDATE items";
		$sql .= " SET last_worn = ?, wear_count = (wear_count + 1) WHERE id = ?";
		$stmt = $this->db->conn->prepare($sql);
		$stmt->bind_param("si", $this->date, $itemId);

		if (!$stmt->execute()) {
			// Handle SQL error
			return json_encode(array("notice" => "Item update failed: " . $stmt->error));
		} else {
			return json_encode(array("notice" => "Item updated"));
		}
	
		$stmt->close();
	}

}