<?php

if ($_SERVER['HTTP_HOST'] !== 'gear.localhost' || $_SERVER['HTTP_HOST'] !== '127.0.0.1') {
    require('../vendor/autoload.php');
} else {
    require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
}
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
    $data = json_encode($_GET);
    $data = json_decode($data, true);
}
if ($method == 'POST') {
    $data = json_encode($_POST);
    $data = json_decode(file_get_contents('php://input'), true);
}
$helperController = new Controllers\HelpersController();
$itemInfoController = new Controllers\ItemInfoController();
$itemEditController = new Controllers\ItemEditController();
$itemReportController = new Controllers\ItemReportController();
$itemMaintenanceController = new Controllers\ItemMaintenanceController();
$assetController = new Controllers\AssetController();
$packingController = new Controllers\PackingListController();
$plController = new Controllers\ProfitLossEditController();
$venueController = new Controllers\VenueController();
$csvController = new Controllers\CSVImportController();

switch ($method) {
    case 'GET':
        // Search users

        if ($_GET['action'] === 'autocomplete') {
            $helperController->autocomplete($data);
        }
        if ($_GET['action'] === 'delete') {
            $helperController->delete($data);
        }
        if ($_GET['action'] === 'list') {
            $itemInfoController->list($data);
        }

        if ($_GET['action'] === 'deleteItem') {
            $itemEditController->deleteItem($data);
        }
        if ($_GET['action'] === 'setImageType') {
            $itemEditController->setImageType($data);
        }
        if ($_GET['action'] === 'checkIn') {
            $assetController->updateItemCheckinStatus($data);
        }
        if ($_GET['action'] === 'deleteList') {
            $packingController->deleteList($data);
        }
         if ($_GET['action'] === 'renameImages') {
             $itemEditController->renameImages($data);
         }
         if ($_GET['action'] === 'reportItemValue') {
             $itemReportController->reportItemValue($data);
         }
          if ($_GET['action'] === 'reportItemCount') {
              $itemReportController->reportItemCount($data);
          }
            if ($_GET['action'] === 'reportProfitLoss') {
                $itemReportController->profitLoss($data);
            }
              if ($_GET['action'] === 'reportProfitLossCategory') {
                  $itemReportController->profitLossCategory($data);
              }
            if ($_GET['action'] === 'profitLoss') {
                $plController->profitLoss($data);
            }
            if ($_GET['action'] === 'outstandingPayments') {
                $itemReportController->outstandingPayments($data);
            }

            if ($_GET['action'] === 'returnVenues') {
                $venueController->returnVenues($data, 1);
            }
            if ($_GET['action'] === 'returnProfitLossAutocompleteData') {
                $plController->returnProfitLossAutocompleteData($data);
            }
            if ($_GET['action'] === 'updateField') {
                $helperController->updateField($data);
            }
            if ($_GET['action'] === 'gigVenueDetails') {
                $itemReportController->gigVenueDetails($data);
            }

        break;
    case 'POST':
        // Add user with genres
        if ($_GET['action'] === 'checkIn') {
            $assetController->updateItemCheckinStatus($data);
        }
        if ($_GET['action'] === 'addEditItem') {
            $itemEditController->addEditItem($data);
        }
        if ($_GET['action'] === 'updateItem') {
            $itemEditController->updateItem($data);
        }
        if ($_GET['action'] === 'addMaintenanceLog') {
            $itemMaintenanceController->upsertMaintenance($data);
        }
        if ($_GET['action'] === 'packingList') {
            $packingController->updatePackingList($data);
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
            $itemEditController->uploadPhoto($images, $imageType);
        }
        if ($_GET['action'] === 'importCSV') {
            $csvController->importCSVProfitLoss($data);
        }
        if ($_GET['action'] === 'upsertProfitLossData') {
            $plController->upsertProfitLossData($data);
        }
        if ($_GET['action'] === 'upsertVenueData') {
            $venueController->upsertVenueData($data);
        }
      if ($_GET['action'] === 'updateField') {
          $helperController->updateField($data);
      }

        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(array("message" => "Method not allowed."));
        break;
}
