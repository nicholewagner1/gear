<?php

namespace Models;

use Api\Database;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class PackingListModel {
	private $db;
	public $id;
	public $items;
	public $name;
	
	public function __construct($db, $data = '')
	{
		$this->db = $db;
		if ($data) {
			$this->id = $data['id'] ?? '';
			$this->items = $data['items'] ?? '';
			$this->name = $data['name'] ?? '';
		}
	}
	
		public function returnPackingLists()
		{
			$sql = "SELECT l.*, li.*, i.name as item_name";
			$sql .= " FROM list l";
			$sql .= " LEFT JOIN list_items li ON l.id = li.list_id";
			$sql .= " LEFT JOIN item i ON li.item = i.id ";
			if ($this->id != '') {
				$sql .= " WHERE l.id = " . $this->id;
				$sql .= " ORDER BY i.category ASC" ;
			}
			else {
				$sql .= " ORDER BY l.name ASC";
			}
			// echo $sql;
			$stmt = $this->db->conn->prepare($sql);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($result->num_rows > 0) {
				$listItems = [];
				foreach ($result as $row) {
					// Create a nested structure
					$list_id = $row['list_id'];
					if (!isset($listItems[$list_id])) {
						$listItems[$list_id] = [
							'id' => $row['id'],
							'name' => $row['name'],
							'items' => [],
						];
					}
					$listItems[$list_id]['items'][] = [
						'count_needed' => $row['count_needed'],
						'item' => $row['item'],
						'subcategory' => $row['subcategory'],
					];
				}
					$jsonResult = json_encode($listItems, JSON_PRETTY_PRINT);
				// Output the JSON result
				//header('Content-Type: application/json');
				return $jsonResult;
			} else {
				return '';//echo json_encode(array("message" => "No outfits found."));
			}
	
			$stmt->close();
		}
	
		public function checkPackingLists()
		{
			$sql = "SELECT z.name as list_name, l.subcategory, l.count_needed, l.item, i.name,";
			$sql .= "(SELECT  COUNT(i.subcategory) FROM  item i  WHERE  i.subcategory = l.subcategory  AND i.checked_in = 0) as count_category,";
			$sql .= "CASE WHEN ( SELECT COUNT(i.id) FROM item i WHERE i.id = l.item AND i.checked_in = 0)";
			$sql .= " OR ( SELECT COUNT(i.subcategory) FROM item i WHERE i.subcategory = l.subcategory AND i.checked_in = 0)  >= l.count_needed";
			$sql .= " THEN '1' ELSE '0' END AS packed ";
			$sql .= "FROM list_items l ";
			$sql .= "LEFT JOIN item i ON l.item = i.id LEFT JOIN list z ON z.id = l.list_id WHERE l.list_id = ?";
			$stmt = $this->db->conn->prepare($sql);
			$stmt->bind_param("i", $this->id);
			$stmt->execute();
			$result = $stmt->get_result();
	
			// Initialize an array to store required items
			$packingLists = [];
	
			foreach ($result as $row) {
				$subcategory = ($row['subcategory'] != 'NULL') ? $row['subcategory'] : '';
	
				// Create item data
				$itemData = [
					'subcategory' => $subcategory,
					'item_name' => $row['name'],
					'count_needed' => $row['count_needed'],
					'count_category' => $row['count_category'],
					'packed' => $row['packed']
				];
	
				// Check 'packed' status and organize accordingly
				$listName = $row['list_name'];
				if (!isset($packingLists[$listName])) {
					$packingLists[$listName] = [
						'name' => $listName,
						'packed' => [],
						'missing' => [],
					];
				}
	
				// Add item to 'packed' or 'missing' array based on status
				if ($row['packed'] == '1') {
					$packingLists[$listName]['packed'][] = $itemData;
				} elseif ($row['packed'] == '0') {
					$packingLists[$listName]['missing'][] = $itemData;
				}
			}
	
			// Move the json_encode outside the loop
			return json_encode(array_values($packingLists));

		}
	
		public function updatePackingListItems()
		{
			if ($this->id == '') {
				$sql = "INSERT INTO list (name) VALUES (?)";
			} else {
				$sql = "UPDATE list SET name = ? WHERE id = ".$this->id;
			}
			$stmt = $this->db->conn->prepare($sql);
			$stmt->bind_param("s", $this->name);
			if (!$stmt->execute()) {
				// Handle SQL error
				echo json_encode(array("message" => "List creation failed: " . $stmt->error));
				$stmt->close();
				return;
			} else {
				$listId = $stmt->insert_id;
				if ($listId == 0) {
					$listId = $this->id;
				}
				$this->resetListItems($listId);
				foreach ($this->items as $item) {
					if ($item['count_needed'] != 0) {
						$this->packingListItems($listId, $item);
					}
				}
				echo json_encode(array("message" => "List update success"));
			}
		}
	
	
		private function packingListItems($id, $item)
		{
			$count_needed = $item['count_needed'];
			$itemId = $item['item'][0] ?? 'NULL';
			$subcategory = $item['subcategory'][0] ?? 'NULL';

			$sql = "INSERT INTO list_items (list_id, count_needed, item, subcategory) VALUES (?, ?, ?, ?)";
			$stmt = $this->db->conn->prepare($sql);
			$stmt->bind_param("isss", $id, $count_needed, $itemId, $subcategory);
			if (!$stmt->execute()) {
				// Handle SQL error
				echo json_encode(array("message" => "List item insertion failed: " . $stmt->error));
				$stmt->close();
				return;
			} else {
				return;
			//	echo json_encode(array("message" => "List update success"));
			}
		}
	
		private function resetListItems($id)
		{
	
			$sql = "DELETE FROM list_items WHERE list_id = ?";
			$stmt = $this->db->conn->prepare($sql);
			$stmt->bind_param("i", $id);
	
			if (!$stmt->execute()) {
				// Handle SQL error
				//echo json_encode(array("message" => "List item insertion failed: " . $stmt->error));
				$stmt->close();
				return;
			} else {
				return;
			//	echo json_encode(array("message" => "List update success"));
			}
		}
	
}