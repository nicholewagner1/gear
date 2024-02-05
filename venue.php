<?php
$action = $_GET['action'] ?? 'list';
$title = '- Profit/Loss - '.$action ;
require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$id = $_GET['id'] ?? '';
use Controllers\VenueController;
$venueController = new VenueController();
 if ($id != '' || $action == 'new'){
     //echo "id".$id;
     $venueController->editVenue($id);
 } 
if ($id == '' && $action == 'list') {
 $venueController->returnVenues('','',0,'','','');
 }
?>
<script src="/js/venue.js"></script>

<script>

</script>

	<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
