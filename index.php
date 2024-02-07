<?php
$title = 'Home';
require($_SERVER['DOCUMENT_ROOT'].'/config/environment.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$missing = $_GET['missing'] ?? '';
$filter = $_GET['filter'] ?? '';
$value = $_GET['value'] ?? '';
$status = $_GET['status'] ?? '';
$view = $_GET['view'] ?? 'table';
$sort = $_GET['sort'] ?? '';

use Controllers\ItemInfoController;

$itemController = new ItemInfoController();
?>

<h2>Items: <?php echo $value; ?></h2>
<?php $itemController->displayItemsList($view, $missing, $status, $filter, $value, $sort); ?>
<!-- Initialize DataTable -->
<script>
$(document).ready(function() {
    getValues('item');


    $('#itemTable').DataTable({
        "pageLength": 30,
        "searching": true,
        "order": [
            [2, "desc"]
        ],
        fixedHeader: true,
        responsive: true,

    });
});
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
</body>

</html>