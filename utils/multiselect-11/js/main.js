$(function() {

	$('.js-multiple-select').select2({
		tags: true,
		tokenSeparators: [',', ' '],
		placeholder: 'Select an option or start typing'
	});

});