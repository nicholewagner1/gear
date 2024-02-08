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

?>
<form method="post" enctype="multipart/form-data" id="csvForm">
    <input type="file" name="csv_file" />
    <input type="submit" value="Upload" />
</form>
<script>
$(document).ready(function() {
    // Submit the form via AJAX when the form is submitted
    $('#csvForm').submit(function(e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/api/?action=importCSV',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response); // Handle the response from the server
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });
});
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
</body>

</html>