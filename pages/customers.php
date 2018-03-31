<?php
	include 'includes/navigation.php';
	$dataTables = 'true';
	$jsFile = 'customers';
	
	// Delete User Account
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteCustomer') {
		$cid = $mysqli->real_escape_string($_POST['cid']);
		
			// Delete the Account
			$stmt = $mysqli->prepare("DELETE FROM customers WHERE customerID = ?");
			$stmt->bind_param('s', $cid);
			$stmt->execute();
			$stmt->close();

				
			// Delete all related Tasks
			$stmt = $mysqli->prepare("DELETE FROM tasks WHERE custId = ?");
			$stmt->bind_param('s', $cid);
			$stmt->execute();
			$stmt->close();	
		
    }
	
	// users Data
	$qry = "SELECT * FROM customers";
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
	
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
				<?php echo $customerListNavLink; ?>
				<span class="pull-right">
					<a data-toggle="modal" href="#newCustomer" class="btn btn-success btn-sm"><i class="fa fa-university" data-toggle="tooltip" data-placement="left" title="<?php echo $newCustomerNavLink; ?>"></i></a>
				</span>
			</h3>
			<hr />

			<?php if ($msgBox) { echo $msgBox; } ?>
			
			<?php if(mysqli_num_rows($res) < 1) { ?>
				<div class="alertMsg message">
					<i class="fa fa-info-circle"></i> <?php echo $noCustomersFoundMsg; ?>
				</div>
			<?php } else { ?>
				<table id="customerList" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php echo $CustomerNameTH; ?></th>
							<th><?php echo $ContractType; ?></th>
							<th><?php echo $ContractStart; ?></th>
							<th><?php echo $ContractDue; ?></th>
							<th><?php echo $ActivePassive; ?></th>
							<th></th>
						</tr>
					</thead>

					<tbody>
						<?php
							while ($row = mysqli_fetch_assoc($res)) {
							
						?>
							<tr>								
								<td><?php echo $row['cust_name']	; ?></td>
								<td><?php echo $row['contract_type']	; ?></td>
								<td><?php echo $row['contract_startdate']	; ?></td>
								<td><?php echo $row['contract_enddate']	; ?></td>
								<td><?php echo $row['isActive']	; ?></td>
								<td>
									<a href="index.php?page=viewCustomer&cid=<?php echo $row['customerID']; ?>" class="info"><i class="fa fa-pencil warning" data-toggle="tooltip" data-placement="top" title="<?php echo $editTooltip; ?>"></i></a>																											
								</td>
							</tr>
							
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
			
		</div>
	</div>
<?php } ?>