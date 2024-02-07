$(document).ready(function() {

if (table) {
	table.on('click', '.delete', function() {
		deleteThis($(this));
	});

	table.on('click', '.toggleUpdate', function() {
		toggleUpdate($(this));
	});
} else {
	console.warn('Table is not defined. Skipping event binding.');
}

});