
$(document).ready(function(){
	
	
	// Set the Category Select
	var taskCat = $("#taskCat").val();
	$("#catId").find("option:contains('"+taskCat+"')").each(function() {
		if ($(this).text() == taskCat) {
			$(this).attr("selected","selected");
		}
	});
	
	/** ******************************
    * Table 
    ****************************** **/
	
	$('#movementsList').dataTable({
		"columnDefs": [{
			"orderable": false, "targets": 6
		}],
		"order": [ 2, 'asc' ],
		"pageLength": 25
	});
	

	/** ******************************
    * Date Pickers
    ****************************** **/
    $('#editTaskStart').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#editTaskDue').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#editDateClosed').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#editstartTime').datetimepicker({
		format: 'hh:ii',
		startDate: '2014-01-01',
		weekStart: 1,
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15
	});
	$('#editendTime').datetimepicker({
		format: 'hh:ii',
		startDate: '2014-01-01',
		weekStart: 1,
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15
	});
	


});
