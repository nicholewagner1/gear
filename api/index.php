<?php

if ($_SERVER['HTTP_HOST'] !== 'gear.localhost' || $_SERVER['HTTP_HOST'] !== '127.0.0.1') {
    require('../vendor/autoload.php');
} else {
    require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');

}
header('Content-Type: application/json');

use Api\Database;

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];
$db = new Api\Database();
if ($method == 'GET') {
    $data = json_encode($_GET);
    $data = json_decode($data, true);
}
if ($method == 'POST') {
    $data = json_encode($_POST);
    $data = json_decode(file_get_contents('php://input'), true);
}
$helperController = new Controllers\HelpersController();

//$itemController = new Controllers\ItemController($db, $data);
$itemInfoController = new Controllers\ItemInfoController();
$itemEditController = new Controllers\ItemEditController();
$itemReportController = new Controllers\ItemReportController();
$itemMaintenanceController = new Controllers\ItemMaintenanceController();
//$maintenanceController = new Controllers\MaintenanceController($db, $data);
$assetController = new Controllers\AssetController();
$packingController = new Controllers\PackingListController();
$plController = new Controllers\ProfitLossEditController();
$venueController = new Controllers\VenueController();

switch ($method) {
    case 'GET':
        // Search users
       
        if ($_GET['action'] === 'autocomplete') {
            $helperController->autocomplete($db, $data);
        }
        if ($_GET['action'] === 'delete') {
            $helperController->delete($db, $data);
        }
        if ($_GET['action'] === 'list') {
            $itemInfoController->list($db, $data);
        }

        if ($_GET['action'] === 'deleteItem') {
            $itemEditController->deleteItem($db, $data);
        }
        if ($_GET['action'] === 'setImageType') {
            $itemEditController->setImageType($db, $data);
        }
        if ($_GET['action'] === 'checkIn') {
            $assetController->updateItemCheckinStatus($db,$data);
        }
        if ($_GET['action'] === 'deleteList') {
            $packingController->deleteList($db,$data);
        }
         if ($_GET['action'] === 'renameImages') {
             $itemEditController->renameImages($db, $data);
         }
         if ($_GET['action'] === 'reportItemValue') {
              $itemReportController->reportItemValue($db, $data);
          }
          if ($_GET['action'] === 'reportItemCount') {
                $itemReportController->reportItemCount($db, $data);
            }
            if ($_GET['action'] === 'reportProfitLoss') {
                  $itemReportController->profitLoss($db, $data);
              }
              if ($_GET['action'] === 'reportProfitLossCategory') {
                    $itemReportController->profitLossCategory($db, $data);
                }
            if ($_GET['action'] === 'profitLoss') {
                $plController->profitLoss($db, $data);
            }
            if ($_GET['action'] === 'returnVenues') {
                $venueController->returnVenues($db, $data, 1);
            }
            if ($_GET['action'] === 'returnProfitLossAutocompleteData') {
                $plController->returnProfitLossAutocompleteData($db, $data);
            }
            if ($_GET['action'] === 'updateField') {
                $helperController->updateField($db, $data);
            }
            if ($_GET['action'] === 'gigVenueDetails') {
                $itemReportController->gigVenueDetails($db, $data);
            }
          
        break;
    case 'POST':
        // Add user with genres
        if ($_GET['action'] === 'checkIn') {
            $assetController->updateItemCheckinStatus($db, $data);
        }
        if ($_GET['action'] === 'addEditItem') {
            $itemEditController->addEditItem($db, $data);
        }
        if ($_GET['action'] === 'updateItem') {
            $itemEditController->updateItem($db, $data);
        }
        if ($_GET['action'] === 'addMaintenanceLog') {
            $itemMaintenanceController->upsertMaintenance($db, $data);
        }
        if ($_GET['action'] === 'packingList') {
            $packingController->updatePackingList($db,$data);
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
            $itemEditController->uploadPhoto($db, $images, $imageType);
        }
        if ($_GET['action'] === 'upsertProfitLossData') {
            $plController->upsertProfitLossData($db,$data);
        }
        if ($_GET['action'] === 'upsertVenueData') {
            $venueController->upsertVenueData($db,$data);
        }
      if ($_GET['action'] === 'updateField') {
          $helperController->updateField($db, $data);
      }
        
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(array("message" => "Method not allowed."));
        break;
}

$db->closeConnection();
