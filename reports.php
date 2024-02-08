<?php
$title = '- Reports';
require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
include($_SERVER['DOCUMENT_ROOT'].'/header.php');

$chartType = $_GET['chart'] ?? 'bar';
$date_start = $_GET['date_start'] ?? date('Y-m-d', strtotime('-90 days'));
$date_end = $_GET['date_end'] ?? date('Y-m-d');
$report = $_GET['report'] ?? '';
$range = $_GET['range'] ?? '';

use Controllers\ItemReportController;

$reportController = new ItemReportController();
?>
<div class="row mb-4 border-success border-bottom">
    <form id="reportPicker" action="/reports.php">
        <div class="row mb-2">
            <div class="col">
                <label for="report">Report:</label>

                <select class="form-control js-multiple-select" id="reportChoice" name="report" value="<?= $report; ?>">
                    <option value="reportProfitLossCategory">Expenses by Category</option>
                    <option value="reportProfitLoss">Income/Expenses by Month</option>
                    <option value="outstandingPayments">Outstanding Payments</option>
                    <option value="insuranceReport">Insured Item Report</option>
                    <option value="gigVenueDetails">Income by Venue</option>
                </select>
            </div>
            <div class="col">
                <label for="dateRange">Date Range:</label>
                <select class="form-control" name="range" value="<?= $range;?>" id="dateRange">
                    <option value="30">Last 30 days</option>
                    <option value="60">Last 60 days</option>
                    <option value="90">Last 90 days</option>
                    <option value="lastYear">Last Year</option>
                    <option value="yearToDate">Year to Date</option>
                    <option value="custom">Custom</option>
                    <option value="allTime">All Time</option>
                </select>
            </div>
            <div class="col">
                <label for="date_start">Start Date</label>
                <input name="date_start" type="date" id="date_start" class="form-control" value="<?= $date_start; ?>">
            </div>
            <div class="col">
                <label for="date_end">End Date</label>
                <input name="date_end" type="date" id="date_end" class="form-control" value="<?= $date_end; ?>">
            </div>
            <div class="col">
                <label></label>
                <input class="btn btn-secondary form-control" type="submit">
            </div>
        </div>
    </form>
</div>
<script src="/js/reports.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-autocolors"></script>

<?php if ($report == 'reportProfitLossCategory') {?>
<script>
doreportProfitLossCategory('<?= $date_start ?>', '<?= $date_end ?>');
</script>
<?php }
if ($report == 'reportProfitLoss') {?>
<script>
doReportProfitLoss('<?= $date_start ?>', '<?= $date_end ?>');
</script>
<?php
}
if ($report == 'outstandingPayments') {?>
<script>
doReportOutstandingPayments('<?= $date_start ?>', '<?= $date_end ?>');
</script>
<?php
}

if ($report == 'gigVenueDetails') {
    $data = array('date_start' => $date_start, 'date_end'=> $date_end);
    $reportController->gigVenueDetails($data);
} if ($report == 'insuranceReport') {
    $reportController->insuranceReport();
} ?>
<div class="row">
    <div class="col">
        <div>
            <canvas id="profitLossChart"></canvas>

        </div>
    </div>
    <div class="col">
        <div id="dataList"></div>
    </div>
</div>
<script>
$(document).ready(function() {

});
</script>

<?php


include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
