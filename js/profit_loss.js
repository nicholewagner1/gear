var table = $('#profitLossTable').DataTable({
	"pageLength": 30,
	"order": [[0, "DESC"]],
	fixedHeader: true,
	responsive: true
});

// Add individual column filters for 'type' and 'paid'
$('#profitLossTable thead th').each(function () {
	var title = $(this).text();
	if (title === 'Type' || title === 'Paid' || title === 'Date') {
		$(this).prepend('<br><input type="text" class="form-control" placeholder="Search ' + title + '" />');
	}
	console.log('filter');
});

// Apply column filters
table.columns().every(function () {
	var that = this;

	$('input', this.header()).on('keyup change', function () {
		if (that.search() !== this.value) {
			that
				.search(this.value)
				.draw();
		}
	});
});

$("#category").change(function (event) {
	var type = $('#category').val();
	if (type == 'Show'){
	$('#gigInfo').toggleClass('hidden');
	}
});


$("#profitLossSubmit").click(function (event) {
	event.preventDefault();

	const formId = '#profitLossForm';
	const form = $(formId)[0];
	const formData = new FormData(form);			
	const jsonData = {};
	formData.forEach((value, key) => {
		jsonData[key] = value;
	});
	$.ajax({
		type: "POST",
		url: "/api/index.php?action=upsertProfitLossData",
		contentType: false,
		processData: false,
		data: JSON.stringify(jsonData),
		success: function (response) {
		console.log(response);
		window.location = "/profit_loss.php?action=edit&id="+response.item_id;
		},
		error: function () {
			alert("Error processing the form.");
		}
	});

});

function gigMath()
{
var venuePayout = parseInt($("#venue_payout").val()) || 0;
var merchValue = parseInt($("#merch").val()) || 0;
var tipsValue = parseInt($("#tips").val()) || 0;
var totalAmount = venuePayout + merchValue + tipsValue;
$("#amount").val(totalAmount);
console.log(totalAmount);
}
$(".gig_math").change(function (event) {
gigMath();
});

	$(document).ready(function () {
		
		var paid = $('#paid').data('start-value');
		var taxForms = $('#tax_forms').data('start-value');
		$('#tax_forms').val(taxForms);
		$('#paid').val(paid);

		});
