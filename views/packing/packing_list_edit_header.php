<div class="row">
	<div class="col">
		<h2>Create/Edit Packing List</h2>
	</div>
</div>
<div class="row">
	<div class="col">
		<form id="packingList">
			<input type="text" id="id" name="id" hidden value="<?php echo $id; ?>"><br>
			<label for="name">Name:</label>
			<input type="text" id="name" class="form-control" name="name" required value="<?php echo $lists[$id]['name'] ?? '' ; ?>">
			
			<table id="itemList">
				<thead>
					<tr>
						<td>Count Needed</td>
						<td>Item</td>
						<td>Subcategory</td>
					</tr>
				</thead>
				<tbody>
					