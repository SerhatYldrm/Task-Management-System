$(document).ready(function(){

	$('#customerList').dataTable({
		"columnDefs": [{
			"orderable": false, "targets": 5
		}],
		"order": [ 2, 'asc' ],
		"pageLength": 25
	});

});