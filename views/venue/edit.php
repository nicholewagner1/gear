<form id="venueForm">
	<input type="hidden" name="id" value="<?= $id ?? ''; ?>" />
	
	<div class="form-group row">
	
		<div class="col">
		<label for="name">Name:</label>
		<input type="text" class="form-control" name="name" value="<?= $name ?? ''; ?>" required />
	</div>
	</div>
	
	<div class="form-group row">
		<div class="col">
			<label for="type">Type:</label>
			<select class="autocomplete type form-control" data-table="venue" multiple id="venue_type" name="venue_type" value="<?= $venue_type ?? ''; ?>" /></select>
		</div>
		<div class="col">
			<label for="type">Status:</label>
			<select class="autocomplete status form-control" data-table="venue" multiple id="status" name="status" value="<?= $status ?? ''; ?>" /></select>
		</div>
		
	<div class="col">
		<label for="roundtrip_mileage">Round Trip Mileage:</label>
		<input type="number" class="form-control" name="roundtrip_mileage" value="<?= $roundtrip_mileage ?? ''; ?>" required />
	</div>
	<div class="col">
		<label for="city">City:</label>
		<input type="text" class="form-control" name="city" value="<?= $city ?? ''; ?>" required />
	</div>
	<div class="col">
		<label for="state">State:</label>
		<input type="text" class="form-control" name="state" value="<?= $state ?? ''; ?>" required />
	</div>
	<div class="col">
		<label for="country">Country:</label>
		<input type="text" class="form-control" name="country" value="<?= $country ?? ''; ?>" required />
	</div>
	</div>
	<div class="form-group">
		<label for="booking_contact">Booking Contact:</label>
		<textarea class="form-control" name="booking_contact"><?= $booking_contact ?? ''; ?></textarea>
	</div>
	
	
	<button id="venueSubmit" class="btn btn-primary">Submit</button>
</form>