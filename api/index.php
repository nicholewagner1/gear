<?php

if ($_SERVER['HTTP_HOST'] !== 'wardrobe.localhost' || $_SERVER['HTTP_HOST'] !== '127.0.0.1') {
	require('../vendor/autoload.php');
}
else {
	require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');

}
use Api\Database;

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];
$db = new Api\Database();
if ($method == 'GET'){
	$data = json_encode($_GET);
	$data = json_decode($data, true);
}
if ($method == 'POST') {
	$data = json_encode($_POST);
	$data = json_decode(file_get_contents('php://input'), true);
}
$itemController = new Controllers\ItemController($db, $data);
$outfitController = new Controllers\OutfitController($db, $data);

switch ($method) {
case 'GET':
	// Search users
	if ($_GET['action'] === 'list') {
		$itemController->returnItems($data);
	}
	if ($_GET['action'] === 'images') {
		$itemController->returnItemImages($data);
	}
	if ($_GET['action'] === 'outfitList') {
		$outfitController->returnAllOutfits();
	}
	if ($_GET['action'] === 'updateLastWorn'){
		$outfitController->updateItemLastWorn($data);

	}
	if ($_GET['action'] === 'autocomplete') {
		$itemController->autocomplete($data);
	}
	if ($_GET['action'] === 'autocompleteVibe') {
		$outfitController->autocompleteVibe($data);
	}
	if ($_GET['action'] === 'delete') {
		$itemController->deleteItem($data);
	}
	if ($_GET['action'] === 'hardDelete') {
		$itemController->hardDeleteItem($data);
	}
	if ($_GET['action'] === 'setThumbnail') {
		$itemController->setThumbnail($data);
	}
 break;
	case 'POST':
		// Add user with genres
		if ($_GET['action'] === 'addEditItem') {
			$itemController->addEditItem($data);
		}
		if ($_GET['action'] === 'updateItem') {
			$itemController->updateItem($data);
		}
		if ($_GET['action'] === 'uploadPhoto') {
			$imageType = $_POST['imageType'] ?? '';
			$imagesArray = $_FILES['images'] ?? [];
			$images = [];
		
			foreach ($imagesArray as $key => $value) {
				foreach ($value as $index => $fileData) {
					$images[$index][$key] = $fileData;
				}
			}
		
			$itemController->uploadPhoto($images, $imageType);
		}

	
	if ($_GET['action'] === 'addEditOutfit') {
		$outfitController->addEditOutfit($data);
		}
 break;
		
			default:
				http_response_code(405); // Method Not Allowed
				echo json_encode(array("message" => "Method not allowed."));
				break;
		}
		
		$db->closeConnection();
