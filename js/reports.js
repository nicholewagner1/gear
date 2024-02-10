$(document).ready(function() {
	$('#gigsTable').DataTable({
		"order": [
			[3, "desc"]
		],
		fixedHeader: true,
		responsive: true, // Set the default sorting to the first column in ascending order
	});

	$('#insuranceTable').DataTable({
		"order": [
			[0, "desc"]
		],
		fixedHeader: true,
		responsive: true, // Set the default sorting to the first column in ascending order
	});
	var reportPicked = $("#reportChoice").attr('value');
	var rangePicked = $("#dateRange").attr('value');
	preFillSelector('dateRange',rangePicked);

	preFillSelector('reportChoice',reportPicked);
	
	$('#dateRange').change(function () {
		var today = new Date();
		var endDate = today.toISOString().split('T')[0]; // Get today's date
	
		var startDate;
		switch ($(this).val()) {
			case '30':
				startDate = new Date(today);
				startDate.setDate(today.getDate() - 30);
				break;
			case '60':
				startDate = new Date(today);
				startDate.setDate(today.getDate() - 60);
				break;
			case '90':
				startDate = new Date(today);
				startDate.setDate(today.getDate() - 90);
				break;
			case 'lastYear':
				startDate = new Date(today.getFullYear() - 1, 0, 1);
				today = new Date(today.getFullYear() - 0, 0, -0);

				break;
			case 'yearToDate':
				startDate = new Date(today.getFullYear(), 0, 1);
				break;
				case 'allTime':
				startDate = new Date(2016,0, 0,0);
				break;
			default:
				break;
		}
	
		$('#date_start').val(startDate.toISOString().split('T')[0]);
		$('#date_end').val(today.toISOString().split('T')[0]);
	});
});

function doreportProfitLossCategory(date_start, date_end) {
	fetch('/api/?action=reportProfitLossCategory&date_start=' + date_start + '&date_end=' + date_end)
		.then(response => response.json())
		.then(data => {
			// Extract data for Chart.js
			const labels = data.map(entry => entry.category);
			const totals = data.map(entry => entry.total);

			// Create a pie chart
			createPieChart(labels, totals);
		})
		.catch(error => console.error('Error fetching data:', error));
}
function doReportOutstandingPayments(date_start, date_end) {
	fetch('/api/?action=outstandingPayments&date_start=' + date_start + '&date_end=' + date_end)
		.then(response => response.json())
		.then(data => {
			// Extract data for Chart.js
			const labels = data.map(entry => entry.month);

			const category = data.map(entry => entry.category);
			const totals = data.map(entry => entry.total);
			const datasets = [
			{
				label: 'Category',
				data: category,
				borderWidth: 1,
				stack: 'stackGroup', 
				backgroundColor: 'rgba(53, 65, 33, 0.8)' // Assign a common stack group for income
			  },	
			  {
				label: 'Total',
				data: totals,
				borderWidth: 1,
				stack: 'stackGroup', 
				backgroundColor: 'rgba(53, 130, 33, 0.8)' // Assign a common stack group for income
			  },
			];
			// Create a pie chart
			createBarChart(labels, datasets);
			updateDataList (labels, totals)
		})
		.catch(error => console.error('Error fetching data:', error));
}

function doReportProfitLoss(date_start, date_end) {
	fetch('/api/?action=reportProfitLoss&date_start=' + date_start + '&date_end=' + date_end)
		.then(response => response.json())
		.then(data => {
			const labels = data.map(entry => entry.month);

			// Extract data for Chart.js
			const incomeData = data.map(entry => entry.income);
			const expenseData = data.map(entry => entry.expense); // Make expenses negative for stacking
			const totalsData = data.map(entry => entry.total);
			const datasets = [
				
			  {
				label: 'Income',
				data: incomeData,
				borderWidth: 1,
				stack: 'stackGroup', 
				backgroundColor: 'rgba(53, 130, 33, 0.8)' // Assign a common stack group for income
			  },
			  {
				label: 'Expenses',
				data: expenseData,
				borderWidth: 1,
				stack: 'stackGroup',
				backgroundColor: 'rgba(205, 21, 21, 0.8)' //// Assign the same stack group for expenses
			  },
			 {
				 label: 'Totals',
				 data: totalsData,
				 borderWidth: 1,
				 stack: 'stackGroup',
				 backgroundColor: 'rgba(203, 203, 201, 0.8)' // Assign a common stack group for income
			   }
			];

			// Create a bar chart
			createBarChart(labels, datasets);
		})
		.catch(error => console.error('Error fetching data:', error));
}

function createPieChart(labels, data) {
	const autocolors = window['chartjs-plugin-autocolors'];

	const ctx = document.getElementById('profitLossChart').getContext('2d');
	const myChart = new Chart(ctx, {
		type: 'pie',
		data: {
			labels: labels,
			datasets: [{
				data: data,
				borderWidth: 1
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			legend: {
				position: 'right',
			},
			plugins: [
				autocolors
			  ]
		}
	});
	updateDataList(labels, data);

}

function updateDataList(labels, data) {
	const dataListContainer = document.getElementById('dataList');

	// Clear previous content
	dataListContainer.innerHTML = '';

	// Create a list
	const list = document.createElement('ul');

	// Add list items based on the chart data
	for (let i = 0; i < labels.length; i++) {
		const listItem = document.createElement('li');
		listItem.textContent = labels[i]+': $' + parseInt(data[i],10).toLocaleString();
		list.appendChild(listItem);
	}

	// Append the list to the container
	dataListContainer.appendChild(list);
}

function updateDataTable(labels, datasets) {
	const dataTableContainer = document.getElementById('dataList');

	// Clear previous content
	dataTableContainer.innerHTML = '';

	// Create a table
	const table = document.createElement('table');
	table.classList.add('table');

	// Create table headers
	const thead = document.createElement('thead');
	const headerRow = document.createElement('tr');

	// Adding headers for labels and each dataset
	const labelTh = document.createElement('th');
	labelTh.textContent = 'Label';
	headerRow.appendChild(labelTh);

	for (let i = 0; i < datasets.length; i++) {
		const datasetLabel = datasets[i].label;
		const datasetTh = document.createElement('th');
		datasetTh.textContent = datasetLabel;
		headerRow.appendChild(datasetTh);
	}

	thead.appendChild(headerRow);
	table.appendChild(thead);

	// Create table body
	const tbody = document.createElement('tbody');

	// Add rows based on the labels and each dataset
	for (let j = 0; j < labels.length; j++) {
		const dataRow = document.createElement('tr');
		const labelTd = document.createElement('td');
		labelTd.textContent = labels[j];
		dataRow.appendChild(labelTd);

		for (let i = 0; i < datasets.length; i++) {
			const datasetData = datasets[i].data;

			const td = document.createElement('td');
			td.textContent = '$'+parseInt(datasetData[j],10).toLocaleString() || ''; // Use the label as the index
			dataRow.appendChild(td);
		}

		tbody.appendChild(dataRow);
	}

	// Calculate totals for each dataset
	const totals = datasets.map(dataset => dataset.data.reduce((acc, val) => parseInt(acc, 10) + parseInt((val || 0), 0),10));
	// Append the total row after all label rows
	const totalRow = document.createElement('tr');
	const totalLabelTd = document.createElement('td');
	totalLabelTd.textContent = 'Total';
	totalRow.appendChild(totalLabelTd);

	for (let i = 0; i < totals.length; i++) {
		const td = document.createElement('td');
		td.textContent = '$'+totals[i].toLocaleString();
		totalRow.appendChild(td);
	}

	tbody.appendChild(totalRow);
	table.appendChild(tbody);

	// Append the table to the container
	dataTableContainer.appendChild(table);
}


function createBarChart(labels, data) {
	const autocolors = window['chartjs-plugin-autocolors'];

	const ctx = document.getElementById('profitLossChart').getContext('2d');
	const myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: labels,
			datasets: data
		},
		options: {
			scales: {
				y: {
					beginAtZero: true,
					stacked: true

				},
				x: {
					stacked: true
				},
			},
			plugins: [
				autocolors
			  ]
		},
		
	});
	updateDataTable(labels, data);

}