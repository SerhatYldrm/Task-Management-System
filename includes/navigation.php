<?php
	$msgBox = '';

	// Add New Task
    if (isset($_POST['submit']) && $_POST['submit'] == 'addNewTask') {
        // Validation
		if($_POST['taskTitle'] == "") {
            $msgBox = alertBox($taskTitleReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['custId'] == "...") {
            $msgBox = alertBox($taskCatReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['usrId'] == "...") {
            $msgBox = alertBox($taskUsrReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskPriority'] == "") {
            $msgBox = alertBox($taskPriorityReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskStatus'] == "") {
            $msgBox = alertBox($taskStatusReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskPercent'] == "") {
            $msgBox = alertBox($taskPercentCompReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskStart'] == "") {
            $msgBox = alertBox($taskStartDateReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskDue'] == "") {
            $msgBox = alertBox($taskDueDateReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['taskDesc'] == "") {
            $msgBox = alertBox($taskDescReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else {
			$taskTitle = $mysqli->real_escape_string($_POST['taskTitle']);
			$usrId = $mysqli->real_escape_string($_POST['usrId']);
			$taskDesc = $_POST['taskDesc'];
			$taskPriority = $mysqli->real_escape_string($_POST['taskPriority']);
			$taskStatus = $mysqli->real_escape_string($_POST['taskStatus']);
			$taskPercent = $mysqli->real_escape_string($_POST['taskPercent']);			
			$dateOfEvent = $mysqli->real_escape_string($_POST['taskStart']);
			$timeOfEvent = $mysqli->real_escape_string($_POST['startTime']);
			$taskStart = $dateOfEvent.' '.$timeOfEvent.':00';			
			$endOfEvent = $mysqli->real_escape_string($_POST['taskDue']);
			$endTimeOfEvent = $mysqli->real_escape_string($_POST['endTime']);
			$taskDue = $endOfEvent.' '.$endTimeOfEvent.':00';

			$stmt = $mysqli->prepare("
								INSERT INTO
									tasks(
										custId,
										userId,
										taskTitle,
										taskDesc,
										taskPriority,
										taskStatus,
										taskPercent,
										taskStart,
										taskDue
									) VALUES (
										?,
										?,
										?,
										?,
										?,
										?,
										?,
										?,
										?
									)
			");
			$stmt->bind_param('sssssssss',
								$usrId,
								$usrId,
								$taskTitle,
								$taskDesc,
								$taskPriority,
								$taskStatus,
								$taskPercent,
								$taskStart,
								$taskDue
			);
			$stmt->execute();
			$stmt->close();

			if (isset($_POST['addCal']) && $_POST['addCal'] == '1') {
				$startDate = $endDate = $taskDue.' 00:00:00';
				$eventTitle = 'Task: '.$taskTitle;
				$eventDesc = $mysqli->real_escape_string($_POST['taskDesc']);
				$isTask = '1';
				$eventColor = '#87b633';

				$stmt = $mysqli->prepare("
									INSERT INTO
										events(
											userId,
											startDate,
											endDate,
											eventTitle,
											eventDesc,
											eventColor,
											isTask
										) VALUES (
											?,
											?,
											?,
											?,
											?,
											?,
											?
										)
				");
				$stmt->bind_param('sssssss',
									$userId,
									$startDate,
									$endDate,
									$eventTitle,
									$eventDesc,
									$eventColor,
									$isTask
				);
				$stmt->execute();
				$stmt->close();
				$msgBox = alertBox($taskedSavedMsg1, "<i class='fa fa-check-square'></i>", "success");
			} else {
				$msgBox = alertBox($taskedSavedMsg2, "<i class='fa fa-check-square'></i>", "success");
			}
			// Clear the Form of values
			$_POST['taskTitle'] = $_POST['taskDesc'] = $_POST['taskPriority'] = $_POST['taskStatus'] = $_POST['taskPercent'] = $_POST['taskStart'] = $_POST['taskDue'] = '';
		}
	}
	
	
	// Add New User Account
	if (isset($_POST['submit']) && $_POST['submit'] == 'newUser') {
        // Validation
        if($_POST['userEmail'] == "") {
            $msgBox = alertBox($userEmailReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['password1'] == "") {
            $msgBox = alertBox($newUserPassReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['password1'] != $_POST['password2']) {
			$msgBox = alertBox($passwordsNotMatchMsg, "<i class='fa fa-warning'></i>", "warning");
        } else if($_POST['userFirst'] == "") {
            $msgBox = alertBox($userFirstReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['userLast'] == "") {
            $msgBox = alertBox($userLastReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else {
			// Set some variables
			$dupEmail = '';
			$newEmail = $mysqli->real_escape_string($_POST['userEmail']);
			$userFirst = $mysqli->real_escape_string($_POST['userFirst']);
			$userLast = $mysqli->real_escape_string($_POST['userLast']);

			// Check for Duplicate email
			$check = $mysqli->query("SELECT 'X' FROM users WHERE userEmail = '".$newEmail."'");
			if ($check->num_rows) {
				$dupEmail = 'true';
			}

			// If duplicates are found
			if ($dupEmail != '') {
				$msgBox = alertBox($accountExists1." ".$newEmail.".", "<i class='fa fa-warning'></i>", "warning");
			} else {
				if (isset($_POST['setActive']) && $_POST['setActive'] == '1') {
					// Create the new account and set it to Active
					$hash = md5(rand(0,1000));
					$isActive = '1';
					$joinDate = date("Y-m-d H:i:s");
					$password = encryptIt($_POST['password1']);

					$stmt = $mysqli->prepare("
										INSERT INTO
											users(
												userEmail,
												password,
												userFirst,
												userLast,
												joinDate,
												hash,
												isActive
											) VALUES (
												?,
												?,
												?,
												?,
												?,
												?,
												?
											)");
					$stmt->bind_param('sssssss',
						$newEmail,
						$password,
						$userFirst,
						$userLast,
						$joinDate,
						$hash,
						$isActive
					);
					$stmt->execute();
					$msgBox = alertBox($newUserAccCreatedMsg1, "<i class='fa fa-check-square'></i>", "success");
					// Clear the form of Values
					$_POST['userEmail'] = $_POST['password1'] = $_POST['password2'] = $_POST['userFirst'] = $_POST['userLast'] = '';
					$stmt->close();
				} else {
					// Create the new account & send Activation Email to Client
					$hash = md5(rand(0,1000));
					$isActive = '0';
					$joinDate = date("Y-m-d H:i:s");
					$password = encryptIt($_POST['password1']);

					$stmt = $mysqli->prepare("
										INSERT INTO
											users(
												userEmail,
												password,
												userFirst,
												userLast,
												joinDate,
												hash,
												isActive
											) VALUES (
												?,
												?,
												?,
												?,
												?,
												?,
												?
											)");
					$stmt->bind_param('sssssss',
						$newEmail,
						$password,
						$userFirst,
						$userLast,
						$joinDate,
						$hash,
						$isActive
					);
					$stmt->execute();

					// Send out the email in HTML
					$installUrl = $set['installUrl'];
					$siteName = $set['siteName'];
					$siteEmail = $set['siteEmail'];
					$newPass = $mysqli->real_escape_string($_POST['password1']);

					$subject = $newUserEmailSubject;

					$message = '<html><body>';
					$message .= '<h3>'.$subject.'</h3>';
					$message .= '<p>'.$newUserEmail1.'</p>';
					$message .= '<hr>';
					$message .= '<p>'.$newUserEmail2.' '.$newPass.'</p>';
					$message .= '<p>'.$newUserEmail3.' '.$installUrl.'activate.php?userEmail='.$newEmail.'&hash='.$hash.'</p>';
					$message .= '<hr>';
					$message .= '<p>'.$newUserEmail4.'</p>';
					$message .= '<p>'.$thankYouText.'<br>'.$siteName.'</p>';
					$message .= '</body></html>';

					$headers = "From: ".$siteName." <".$siteEmail.">\r\n";
					$headers .= "Reply-To: ".$siteEmail."\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=utf8\r\n";

					if (mail($newEmail, $subject, $message, $headers)) {
						$msgBox = alertBox($newUserAccCreatedMsg2, "<i class='fa fa-check-square'></i>", "success");
						// Clear the form of Values
						$_POST['userEmail'] = $_POST['password1'] = $_POST['password2'] = $_POST['userFirst'] = $_POST['userLast'] = '';
					}
					$stmt->close();
				}
			}
		}
	}
	
	$qcats = "SELECT 'X' FROM users WHERE isActive = 1";
	$catCheck = mysqli_query($mysqli, $qcats) or die('-4'.mysqli_error());
	$totCheck = mysqli_num_rows($catCheck);
	
	
	// Add New Customer
    if (isset($_POST['submit']) && $_POST['submit'] == 'newCustomer') {
        // Validation
		if($_POST['cust_name'] == "") {
            $msgBox = alertBox($customerNameReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['contract_type'] == "...") {
            $msgBox = alertBox($contractTypeReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['contract_startdate'] == "...") {
            $msgBox = alertBox($contractStartReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['contract_enddate'] == "") {
            $msgBox = alertBox($contractEndReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if(isset($_POST['setActive']) && $_POST['setActive'] == '0') {
            $isActivee = '0';
        } else {
			$cust_name = $mysqli->real_escape_string($_POST['cust_name']);
			$contract_type = $mysqli->real_escape_string($_POST['contract_type']);
			$contract_startdate = $mysqli->real_escape_string($_POST['contract_startdate']);
			$contract_enddate = $mysqli->real_escape_string($_POST['contract_enddate']);	
			$isActivee = '1';
			$stmt = $mysqli->prepare("
								INSERT INTO
									customers(
										cust_name,
										contract_type,
										contract_startdate,
										contract_enddate,
										isActive
									) VALUES (
										?,
										?,
										?,
										?,
										?
									)
			");
			$stmt->bind_param('sssss',
								$cust_name,
								$contract_type,
								$contract_startdate,
								$contract_enddate,
								$isActivee
			);
			$stmt->execute();
			$stmt->close();
		}
	}
?>
<div class="nav-col">
	<nav id="nav" class="clearfix" role="navigation">

	<div class="user-wrapper">
		<p>
			<a href="index.php?page=profile" class="toggle-login"><i class="fa fa-user"></i> <?php echo $profileNavLink; ?></a>
			<span class="v-divider"></span>
			<a href="#signOut" data-toggle="modal" class="toggle-signup"><i class="fa fa-sign-out"></i> <?php echo $signOutNavLink; ?></a>
		</p>
	</div>
		<ul class="primary-nav">
			<li><a href="index.php"><?php echo $dashboardNavLink; ?></a></li>
			<li><a href="index.php?page=calendar"><?php echo $calendarNavLink; ?></a></li>
			<li class="has-children task-parent-li"><a class="task-parent" href=""><?php echo $tasksNavLink; ?><span class="task-icon"></span></a>
				<ul>
					<li><a href="index.php?page=openTasks"><?php echo $openTasksNavLink; ?></a></li>
					<li><a href="index.php?page=closedTasks"><?php echo $closedTasksNavLink; ?></a></li>
					<li><a data-toggle="modal" href="#newTask"><?php echo $newTaskNavLink; ?></a></li>
				</ul>
			</li>
			<li><a href="index.php?page=chat"><?php echo $chatLink; ?></a></li>
			<?php if ($isAdmin == '1') { ?>
				<li class="has-children task-parent-li"><a class="task-parent" href=""><?php echo $usersNavLink; ?><span class="task-icon"></span></a>
					<ul>
						<li><a href="index.php?page=users"><?php echo $userListNavLink; ?></a></li>
						<li><a href="index.php?page=customers"><?php echo $customerListNavLink; ?></a></li>
					</ul>
				</li>
				<li><a href="index.php?page=siteSettings"><?php echo $siteSettingsNavLink; ?></a></li>
			<?php } ?>
		</ul>
	</nav>
</div>

<div class="modal fade" id="signOut" tabindex="-1" role="dialog" aria-labelledby="signOutLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<p class="lead"><?php echo $userFullName.' '.$signOutQuip; ?></p>
			</div>
			<div class="modal-footer">
				<a href="index.php?action=logout" class="btn btn-success btn-icon-alt"><?php echo $signOutBtn; ?> <i class="fa fa-sign-out"></i></a>
				<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> <?php echo $cancelBtn; ?></button>
			</div>
		</div>
	</div>
</div>

<div class="logo-col">
	<a href="#" class="toggle-menu"><i class="fa fa-bars"></i></a>
	<div class="logo-wrapper">
		<a href="index.php" class="clearfix"> <img id="logo" src="images/logo.png" alt="<?php echo $taskedLogoAltText; ?>"></a>
	</div>
	<div class="search-wrapper">
		<a data-original-title="search" href="#" class="toggle-search tooltip-hover" title="<?php echo $searchTooltip; ?>" data-placement="left"><i class="fa fa-search"></i></a>
		<div class="search-panel">
			<form action="index.php?page=searchResults" method="post">
				<input class="form-control" type="search" name="searchTerm" placeholder="<?php echo $searchPlaceholder; ?>">
			</form>
		</div>
	</div>
</div>


			<!-- NEW TASK -->

<div class="modal fade" id="newTask" tabindex="-1" role="dialog" aria-labelledby="newTask" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content modal-lg">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-times"></i></span><span class="sr-only"><?php echo $closeBtn; ?></span></button>
				<h4 class="modal-title"><?php echo $addNewTaskModal; ?></h4>
			</div>
			<?php if ($totCheck == '0') { ?>
				<div class="modal-body">
					<p class="lead"><?php echo $addNewTaskQuip; ?></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
				</div>
			<?php } else { ?>
				<form action="" method="post">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="taskTitle"><?php echo $taskTitleField; ?></label>
									<input type="text" class="form-control" required="" name="taskTitle" value="<?php echo isset($_POST['taskTitle']) ? $_POST['taskTitle'] : ''; ?>" />
									<span class="help-block"><?php echo $taskTitleHelp; ?></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="usrId"><?php echo $viewUserTitle; ?></label>
									<select class="form-control" name="usrId">
										<?php
											$tusr = "SELECT userId,userFirst,userLast FROM users WHERE isActive = 1";
											$rest = mysqli_query($mysqli, $tusr) or die('-2'.mysqli_error());
										?>
										<option value="..."><?php echo $selectOption; ?></option>
										<?php while ($tusrrow = mysqli_fetch_assoc($rest)) { ?>
											<option value="<?php echo $tusrrow['userId']; ?>"><?php echo clean($tusrrow['userFirst']);?><?php echo " ".clean($tusrrow['userLast']); ?></option>
										<?php } ?>
									</select>
									<span class="help-block"><?php echo $userHelp; ?></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="custId"><?php echo $customerNavLink; ?></label>
									<select class="form-control" name="custId">
										<?php
											$tusr = "SELECT customerID,cust_name FROM customers WHERE isActive = 1";
											$rest = mysqli_query($mysqli, $tusr) or die('-2'.mysqli_error());
										?>
										<option value="..."><?php echo $selectOption; ?></option>
										<?php while ($tusrrow = mysqli_fetch_assoc($rest)) { ?>
											<option value="<?php echo $tusrrow['cId']; ?>"><?php echo clean($tusrrow['cust_name']);?></option>
										<?php } ?>
									</select>
									<span class="help-block"><?php echo $customerHelp; ?></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="taskPriority"><?php echo $priorityField; ?></label>
									<select class="form-control" name="taskPriority">
										<option value="..."><?php echo $selectOption; ?></option>
										<option value="Dusuk">Dusuk</option>
										<option value="Normal">Normal</option>
										<option value="Yuksek">Yuksek</option>
										<option value="Onemli">Onemli</option>										
									</select>
									<span class="help-block"><?php echo $priorityHelp; ?></span>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="taskStatus"><?php echo $statusField; ?></label>
									<select class="form-control" name="taskStatus">
										<option value="..."><?php echo $selectOption; ?></option>
										<option value="Beklemede">Beklemede</option>
										<option value="Atama">Atama Yapıldı</option>
										<option value="Urun">Ürün Geliştirme</option>		
										<option value="Test">Test Aşamasında</option>
										<option value="Tamamlandı">Tamamlandı</option>	
										<option value="Musteri">Müşteriye Bildirildi</option>								
									</select>
									<span class="help-block"><?php echo $statusHelp; ?></span>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="taskPercent"><?php echo $perentCompField; ?></label>
									<input type="text" class="form-control" required="" name="taskPercent" value="<?php echo isset($_POST['taskPercent']) ? $_POST['taskPercent'] : ''; ?>" />
									<span class="help-block"><?php echo $perentCompHelp; ?></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="taskStart"><?php echo $startDateField; ?></label>
									<input type="text" class="form-control" required="" name="taskStart" id="newtaskStart" value="<?php echo isset($_POST['taskStart']) ? $_POST['taskStart'] : ''; ?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="taskDue"><?php echo $dueDateField; ?></label>
									<input type="text" class="form-control" required="" name="taskDue" id="newtaskDue" value="<?php echo isset($_POST['taskDue']) ? $_POST['taskDue'] : ''; ?>" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="startTime"><?php echo $startTimeField; ?></label>
									<input type="text" class="form-control" name="startTime" id="tstartTime" value="<?php echo isset($_POST['startTime']) ? $_POST['startTime'] : ''; ?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="endTime"><?php echo $endTimeField; ?></label>
									<input type="text" class="form-control" name="endTime" id="tendTime" value="<?php echo isset($_POST['endTime']) ? $_POST['endTime'] : ''; ?>" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="taskDesc"><?php echo $taskDescField; ?></label>
							<textarea class="form-control" required="" name="taskDesc" rows="5"><?php echo isset($_POST['taskDesc']) ? $_POST['taskDesc'] : ''; ?></textarea>
						</div>
						<input type="checkbox" id="addToCal" name="addCal" value="0" />
						<label for="addToCal" class="addCal">
							<i class="fa fa-square-o"></i>  <span class="addCalText"><?php echo $addToCalCheckbox; ?></span>
						</label>
						<span class="help-block"><?php echo $addToCalHelp; ?></span>
					</div>
					<div class="modal-footer">
						<button type="input" name="submit" value="addNewTask" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveBtn; ?></button>
						<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
					</div>
				</form>
			<?php } ?>
		</div>
	</div>
</div>




<?php if ($isAdmin == '1') { ?>
	<div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="newUser" aria-hidden="true">		<!-- Add New User -->
		<div class="modal-dialog">
			<div class="modal-content modal-lg">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-times"></i></span><span class="sr-only"><?php echo $closeBtn; ?></span></button>
					<h4 class="modal-title"><?php echo $newUserModal; ?></h4>
				</div>
				<form action="" method="post">
					<div class="modal-body">
						<div class="form-group">
							<label for="userEmail"><?php echo $emailAddyField; ?></label>
							<input type="text" class="form-control" required="" name="userEmail" value="<?php echo isset($_POST['userEmail']) ? $_POST['userEmail'] : ''; ?>" />
							<span class="help-block"><?php echo $newUserEmailHelp; ?></span>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="password1"><?php echo $passwordField; ?></label>
									<div class="input-group">
										<input type="password" class="form-control" required="" autocomplete="off" name="password1" id="password1" value="<?php echo isset($_POST['password1']) ? $_POST['password1'] : ''; ?>" />
										<span class="input-group-addon"><a href="" id="generate" data-toggle="tooltip" data-placement="top" title="<?php echo $generatePassTooltip; ?>"><i class="fa fa-key"></i></a></span>
									</div>
									<span class="help-block">
										<a href="" id="show1" class="btn btn-warning btn-xs"><?php echo $showPlainText; ?></a>
										<a href="" id="hide1" class="btn btn-info btn-xs"><?php echo $hidePlainText; ?></a>
									</span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="password2"><?php echo $repeatText.' '.$passwordField; ?></label>
									<input type="password" class="form-control" required="" autocomplete="off" name="password2" id="password2" value="<?php echo isset($_POST['password2']) ? $_POST['password2'] : ''; ?>" />
								</div>
							</div>
						</div>
						<div class="row mb-10">
							<div class="col-md-6">
								<div class="form-group">
									<label for="userFirst"><?php echo $usersText.' '.$firstNameField; ?></label>
									<input type="text" class="form-control" required="" name="userFirst" value="<?php echo isset($_POST['userFirst']) ? $_POST['userFirst'] : ''; ?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="userLast"><?php echo $usersText.' '.$lastNameField; ?></label>
									<input type="text" class="form-control" required="" name="userLast" value="<?php echo isset($_POST['userLast']) ? $_POST['userLast'] : ''; ?>" />
								</div>
							</div>
						</div>
						<input type="checkbox" id="activeUser" name="setActive" value="0" />
						<label for="activeUser" class="setActive">
							<i class="fa fa-square-o"></i>  <span class="activeUserText"><?php echo $inactiveAccountCheckbox; ?></span>
						</label>
						<span class="help-block"><?php echo $inactiveAccountHelp; ?></span>
					</div>
					<div class="modal-footer">
						<button type="input" name="submit" value="newUser" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveBtn; ?></button>
						<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
										<!-- Add New Customer -->
	
	<div class="modal fade" id="newCustomer" tabindex="-1" role="dialog" aria-labelledby="newCustomer" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-lg">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-times"></i></span><span class="sr-only"><?php echo $closeBtn; ?></span></button>
					<h4 class="modal-title"><?php echo $newCustomerModal; ?></h4>
				</div>
				<form action="" method="post">
					<div class="modal-body">						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="cust_name"><?php echo $CustomerNameTH ?></label>
									<input type="text" class="form-control" required="" name="cust_name" value="<?php echo isset($_POST['cust_name']) ? $_POST['cust_name'] : ''; ?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="contract_type"><?php echo $ContractType ?></label>
									<input type="text" class="form-control" required="" name="contract_type" value="<?php echo isset($_POST['contract_type']) ? $_POST['contract_type'] : ''; ?>" />
								</div>
							</div>
						</div>
						<div class="row mb-10">
							<div class="col-md-6">
								<div class="form-group">
									<label for="contract_startdate"><?php echo $ContractStart ?></label>
									<input type="text" class="form-control" required="" name="contract_startdate" id="newcustomerStart" value="<?php echo isset($_POST['contract_startdate']) ? $_POST['contract_startdate'] : ''; ?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="contract_enddate"><?php echo $ContractDue ?></label>
									<input type="text" class="form-control" required="" name="contract_enddate" id="newcustomerDue" value="<?php echo isset($_POST['contract_enddate']) ? $_POST['contract_enddate'] : ''; ?>" />
								</div>
							</div>
						</div>		
						<input type="checkbox" id="activeCustomer" name="setActive" value="0" />
						<label for="activeCustomer" class="setActive">
							<i class="fa fa-square-o"></i>  <span class="activeCustomerText"><?php echo $inactiveCustomerCheckbox; ?></span>
						</label>
						<span class="help-block"><?php echo $inactiveCustomerHelp; ?></span>		
					</div>
					<div class="modal-footer">
						<button type="input" name="submit" value="newCustomer" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveBtn; ?></button>
						<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
<?php } ?>