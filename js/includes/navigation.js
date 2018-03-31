$(document).ready(function () {
	
	/** ******************************
    * New Task Check Box
    ****************************** **/
	$("#addToCal").on("click", function () {
        var check = $("#addToCal").prop("checked");
		$('#addToCal').val('0');

        if (check) {
            if ($('.addCal i').hasClass('fa-square-o')) {
                $('.addCal i').removeClass('fa-square-o').addClass('fa-check-square-o');
				$('.addCalText').html('Takvimime eklenilecek');
				$('#addToCal').attr('checked','checked');
				$('#addToCal').val('1');
            }
        } else {
            if ($('.addCal i').hasClass('fa-check-square-o')) {
                $('.addCal i').removeClass('fa-check-square-o').addClass('fa-square-o');
				$('.addCalText').html('Takvimime Ekle');
				$('#addToCal').removeAttr('checked');
				$('#addToCal').val('0');
            }
        }

    });
	
	/** ******************************
    * Date Pickers
    ****************************** **/
    $('#newtaskStart').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#newtaskDue').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#tstartTime').datetimepicker({
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
	$('#tendTime').datetimepicker({
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
	
	
	$('#newcustomerStart').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#newcustomerDue').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	
	/** ******************************
    * New User Check Box
    ****************************** **/
	$("#activeUser").on("click", function () {
        var check = $("#activeUser").prop("checked");
		$('#activeUser').val('0');

        if (check) {
            if ($('.setActive i').hasClass('fa-square-o')) {
                $('.setActive i').removeClass('fa-square-o').addClass('fa-check-square-o');
				$('.activeUserText').html('Aktif Hesap');
				$('#activeUser').attr('checked','checked');
				$('#activeUser').val('1');
            }
        } else {
            if ($('.setActive i').hasClass('fa-check-square-o')) {
                $('.setActive i').removeClass('fa-check-square-o').addClass('fa-square-o');
				$('.activeUserText').html('İnaktif Hesap');
				$('#activeUser').removeAttr('checked');
				$('#activeUser').val('0');
            }
        }
    });
	
	
	/** ******************************
    * New Customer Check Box
    ****************************** **/
	$("#activeCustomer").on("click", function () {
        var check = $("#activeCustomer").prop("checked");
		$('#activeCustomer').val('0');

        if (check) {
            if ($('.setActive i').hasClass('fa-square-o')) {
                $('.setActive i').removeClass('fa-square-o').addClass('fa-check-square-o');
				$('.activeCustomerText').html('Aktif Firma');
				$('#activeCustomer').attr('checked','checked');
				$('#activeCustomer').val('1');
            }
        } else {
            if ($('.setActive i').hasClass('fa-check-square-o')) {
                $('.setActive i').removeClass('fa-check-square-o').addClass('fa-square-o');
				$('.activeCustomerText').html('İnaktif Firma');
				$('#activeCustomer').removeAttr('checked');
				$('#activeCustomer').val('0');
            }
        }
    });
	
	// Hide the two links on page load
	$('#hide1').hide();

	// Show the Password field as plain text
	$('#show1').click(function(e) {
		e.preventDefault();
		$('#password1').prop('type','text');
		$('#show1').hide();
		$('#hide1').show();
		$('#password2').prop('type','text');
		$('#show2').hide();
		$('#hide2').show();
	});
	// Show the Password field as asterisks
	$('#hide1').click(function(e) {
		e.preventDefault();
		$('#password1').prop('type','password');
		$('#hide1').hide();
		$('#show1').show();
		$('#password2').prop('type','password');
		$('#hide2').hide();
		$('#show2').show();
	});

	// Generate Random Password
	$('#generate').click(function (e) {
		e.preventDefault();

		// You can change the password length by changing the
		// integer to the length you want in generatePassword(8).
		var pwd = generatePassword(8);

		// Populates the fields with the new generated password
        $('#password1, #password2').val(pwd);
    });
});
