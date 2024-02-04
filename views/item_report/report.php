
<?php
require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');

use Bbsnly\ChartJs\Chart;
use Bbsnly\ChartJs\Config\Data;
use Bbsnly\ChartJs\Config\Dataset;
use Bbsnly\ChartJs\Config\Options;

use \Controllers\ItemReportController;
$itemController = new ItemReportController();

$itemList = $itemController->reportItemValue('','','Current', 'subcategory', 'purchase_price', 'purchase_price');

$values = array_column($itemList, 'value');
$labels = array_column($itemList, 'filter');
$valuesList = "'".implode("','", $values)."'";
$labelsList = "'".implode("','", $labels)."'";

// $chart = new Chart;
// $chart->type = 'line';
// // 
// $data = new Data();
// $data->labels = $labels;
// $dataset = new Dataset();
// $dataset->data = $values;
// $data->datasets['data'] = $dataset->data;
// print_r($data);
// 
// $chart->data($data);
// 
// $options = new Options();
// $options->responsive = true;
// $chart->options($options);
// $data = new Data();
// $data->labels = ['Red', 'Green', 'Blue'];
// 
// $dataset = new Dataset();
// $dataset->data = [5, 10, 20];
// $data->datasets[] = $dataset->data;
// 
// $chart->data($data);
// 
// $options = new Options();
// $options->responsive = true;
// $chart->options($options);
// $chart->get(); // Returns the array of chart data
// $chart->toJson(); // Returns the JSON representation of the chart data
// $chart->toHtml('my_chart'); // Returns the HTML and JavaScript code for the chart

?>

<div>
  <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
	type: 'bar',
	data: {
	  labels: [<?= ($labelsList); ?>],
	  datasets: [{
		  minBarLength: 2,

		label: '$ spend',
		data: [<?= ($valuesList); ?>],
		borderWidth: 1
	  }]
	},
	options: {
	  scales: {
		  x: {
			  grid: {
				offset: true
			  }
		  },
		y: {
		  beginAtZero: false,
		  
			  // min: 100,
			  // max: 5000,
		
		}
	  }
	}
  });
</script>