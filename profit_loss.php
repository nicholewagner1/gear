<?php
$action = $_GET['action'] ?? 'list';
$title = '- Profit/Loss - '.$action ;
require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$id = $_GET['id'] ?? '';
use Controllers\ProfitLossEditController;

$profitLossController = new ProfitLossEditController();
 if ($id != '' || $action == 'new') {
     //echo "id".$id;
     $profitLossController->editProfitLoss($id);
 }
if ($id == '' && $action == 'list') {
    $profitLossController->returnProfitLoss();
}
?>
<script src="/js/profit_loss.js"></script>


<script>
$(document).ready(function() {

});
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
