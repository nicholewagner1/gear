function doreportProfitLossCategory(date_start, date_end) {
	fetch('/api/?action=reportProfitLossCategory&date_start=' + date_start + '&date_end=' + date_end)
		.then(response => response.json())
		.then(data => {
			// Extract data for Chart.js
			const labels = data.map(entry => entry.type);
			const totals = data.map(entry => entry.total);

			// Create a pie chart
			createPieChart(labels, totals);
		})
		.catch(error => console.error('Error fetching data:', error));
}

function doReportProfitLoss(date_start, date_end) {
	fetch('/api/?action=reportProfitLoss&date_start=' + date_start + '&date_end=' + date_end)
		.then(response => response.json())
		.then(data => {
			// Extract data for Chart.js
			const labels = data.map(entry => entry.month);
			const totals = data.map(entry => entry.total);

			// Create a bar chart
			createBarChart(labels, totals);
		})
		.catch(error => console.error('Error fetching data:', error));
}

function createPieChart(labels, data) {
	const ctx = document.getElementById('profitLossChart').getContext('2d');
	const myChart = new Chart(ctx, {
		type: 'pie',
		data: {
			labels: labels,
			datasets: [{
				data: data,
				backgroundColor: [
					'rgba(255, 99, 132, 0.7)',
					'rgba(54, 162, 235, 0.7)',
					'rgba(255, 206, 86, 0.7)',
					'rgba(75, 192, 192, 0.7)',
					// Add more colors as needed
				],
				borderColor: [
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					// Add more colors as needed
				],
				borderWidth: 1
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			legend: {
				position: 'right',
			}
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
		listItem.textContent = `${labels[i]}: ${data[i]}`;
		list.appendChild(listItem);
	}

	// Append the list to the container
	dataListContainer.appendChild(list);
}

function createBarChart(labels, data) {
	const ctx = document.getElementById('profitLossChart').getContext('2d');
	const myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: labels,
			datasets: [{
				label: 'Total Amount',
				data: data,
				backgroundColor: 'rgba(75, 192, 192, 0.2)',
				borderColor: 'rgba(75, 192, 192, 1)',
				borderWidth: 1
			}]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	});
	updateDataList(labels, data);

}
