<?php
	include 'includes/navigation.php';
	$cid = $_GET['cid'];
	$jsFile = 'viewCustomer';
	// Edit User Account
	if (isset($_POST['submit']) && $_POST['submit'] == 'editCustomer') {
		if($_POST['custName'] == "") {
            $msgBox = alertBox($customerNameReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['contract_type'] == "") {
            $msgBox = alertBox($contractTypeReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['contractStart'] == "") {
            $msgBox = alertBox($contractStartReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['contractEnd'] == "") {
            $msgBox = alertBox($contractEndReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['activepassive'] == "") {
            $msgBox = alertBox($contractEndReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$custName = $mysqli->real_escape_string($_POST['custName']);
			$contract_type = $mysqli->real_escape_string($_POST['contract_type']);
			$contractStart = $mysqli->real_escape_string($_POST['contractStart']);
			$contractEnd = $mysqli->real_escape_string($_POST['contractEnd']);
			$activepassive = $mysqli->real_escape_string($_POST['activepassive']);

			$stmt = $mysqli->prepare("UPDATE
										customers
									SET
										cust_name = ?,
										contract_type = ?,
										contract_startdate = ?,
										contract_enddate = ?,										
										isActive = ?
									WHERE
										customerID = ?"
			);
			$stmt->bind_param('ssssss',
								$custName,
								$contract_type,
								$contractStart,
								$contractEnd,
								$activepassive,
								$cid
			);
			$stmt->execute();
			$msgBox = alertBox($custAccUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		}
	}
	// User Data
	$qry = "SELECT
				*
			FROM
				customers
			WHERE
				customerID = ".$cid;
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
	$row = mysqli_fetch_assoc($res);

	
	if ($row['isActive'] == '1') { $theStatus = 'selected'; } else { $theStatus = ''; }
	
	if ($isAdmin != '1') {
?>
	<div class="content-col" id="page">
		<div class="inner-content">
			<h1 class="font-weight-thin no-margin-top"><?php echo $accessErrorHeader; ?></h1>
			<hr />

			<div class="alertMsg danger">
				<i class="fa fa-warning"></i> <?php echo $permissionDenied; ?>
			</div>
		</div>
	</div>
<?php } else { ?>
	<div class="content-col" id="page">
		<div class="inner-content">
			<h3 class="font-weight-thin no-margin-top">
				<?php echo $customerNavLink; ?>: <?php echo clean($row['cust_name']) ?>
				<span class="pull-right">
					<a data-toggle="modal" href="#newCustomer" class="btn btn-success btn-sm"><i class="fa fa-university" data-toggle="tooltip" data-placement="left" title="<?php echo $newCustomerNavLink; ?>"></i></a>
				</span>
			</h3>
			<hr />

			<?php if ($msgBox) { echo $msgBox; } ?>

			<form action="" method="post" class="panel form-horizontal form-bordered" name="form-account">
				<div class="panel-body">
					<div class="form-group header bgcolor-default">
						<div class="col-md-12">
							 <h4>
								<?php echo $customerDetailsTitle; ?>
							</h4>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $CustomerNameTH ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="custName" value="<?php echo clean($row['cust_name']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $ContractType ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="contract_type" value="<?php echo clean($row['contract_type']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $ContractStart; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="contractStart" id="editStartDate" value="<?php echo dbDateFormat($row['contract_startdate']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $ContractDue; ?></label>
						<div class="col-sm-8">
							<input class="form-control" type="text" required="" name="contractEnd" id="editEndDate" value="<?php echo dbDateFormat($row['contract_enddate']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $ActivePassive; ?></label>
						<div class="col-sm-8">
							<select class="form-control" name="activepassive">
								<option value="0"><?php echo $inactiveAccText; ?></option>
								<option value="1" <?php echo $theStatus; ?>><?php echo $activeAccText; ?></option>
							</select>
						</div>
					</div>
				</div>
				<hr />
				<button type="input" name="submit" value="editCustomer" class="btn btn-success btn-lg btn-icon mt-10"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
			</form>

		</div>
	</div>
<?php } ?>