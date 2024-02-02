<div id="accordion" class="mt-3">
	<div class="card">
		<div class="card-header" id="headingOne">
			<h5 class="mb-0">
				<button class="btn btn-link" data-toggle="collapse" data-target="#maintenanceLogCard" aria-expanded="true" aria-controls="maintenanceLogCard">
					Maintenance Log</button>
			</h5>
		</div>
		<div id="maintenanceLogCard" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
			<div class="card-body">
				<table id='maintenanceLog'>
					<thead>
						<tr>
							<th>Date</th>
							<th>Service</th>
							<th>Notes</th>
							<th>Cost</th>
						</tr>
					</thead>
					<?php foreach ($maintenance as $service) {
							echo "<tr><td>".$service['date']."</td><td>".$service['service']."</td><td>".$service['notes']."</td><td>".$service['cost']."</td></tr>";
					} ?>
				</table>
			</div>
		</div>
	</div>
</div>