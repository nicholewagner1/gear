<div class="row">
	<div class="col">
<h2>Log Maintenance</h2>

<form id="logMaintenaceForm">
	<input type="text" name="id" hidden value=""><br>

	<label for="date">Date:</label>
	<input type="date" name="date" required value="<?= $date ?>"><br>
	<label for="date">Service:</label>
	<select id="service" class="js-multiple-select service autocompleteMaintenance form-control" type="text" name="service" value=""></select><br>

	<div class="row">
		 <div class="col">
			 <label for="item">Item</label>
			<select id="items" class="js-multiple-select autocomplete item form-control" multiple="multiple" name="name" value="">
			</select>
	<div id="itemImages"></div>


		</div>
		<div class="row">
			<div class="col">
			<label for="notes">Notes</label>	<input type="text" id="notes" class="form-control notes" name="notes">
			
			<label for="cost">Cost</label>	<input type="text" id="cost" class="form-control cost" name="cost">
			</div>
	  </div>

	<input class="btn btn-primary "  type="submit" value="Log Maintenance">
</form>
	</div></div>