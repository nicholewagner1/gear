<?php
require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$chartType = $_GET['chart'] ?? 'bar';
$date_start = $_GET['date_start'] ?? date('Y-m-d', strtotime('-90 days'));
$date_end = $_GET['date_end'] ?? date('Y-m-d');
$report = $_GET['report'] ?? '';

echo '<h1>Report - '.$report.'</h1>';
echo '<h3>'.$date_start.' - '.$date_end.'</h3>';
?>
<form id="reportPicker" action="/reports.php">
	<div class="row">
		<div class="col">
	<input name="date_start" type=date value="<?= $date_start;?>" class="form-control">
	<label for="date_start">Start Date</label>
		</div>
		<div class="col">
	<input name="date_end" type=date value="<?= $date_end;?>" class="form-control">
	<label for="date_end">End Date</label>
		</div>
		<div class="col">
		<select class="form-control" name="report">
			<option value="reportProfitLossCategory">Expenses by Category</option>
			<option value="reportProfitLoss">Income/Expenses by Month</option>
		</select>
		</div>
	</div>
	<input type="submit"></form>
</form>
<script src="/js/reports.js"></script>
<div class="row">
	<div class="col">
 <div>
   <canvas id="profitLossChart"></canvas>

 </div>
	</div><div class="col">
 <div id="dataList"></div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if ($report == 'reportProfitLossCategory' ){?>
<script> doreportProfitLossCategory('<?= $date_start ?>','<?= $date_end ?>'); </script>
<?php }
if ($report == 'reportProfitLoss'){?>
	<script> doReportProfitLoss('<?= $date_start ?>','<?= $date_end ?>'); </script>
<?php
	
} ?>
		
<script>
	$(document).ready(function () {

});
</script>

	<?php


include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
