<?php
	include 'includes/navigation.php';
	$dataTables = 'true';
	$jsFile = 'closedTasks';
	
	function mysql_get_var($query,$y=0){
       $res = mysql_query($query);
       $row = mysql_fetch_array($res);
       mysql_free_result($res);
       $rec = $row[$y];
       return $rec;
}
	
	// Reopen Task
	if (isset($_POST['submit']) && $_POST['submit'] == 'reopenTask') {
		$taskId = $mysqli->real_escape_string($_POST['taskId']);
		$taskStatus = 'Beklemede';
		$dateClosed = '0000-00-00 00:00:00';
		$lastUpdated = date("Y-m-d H:i:s");
		$uID = $mysqli->real_escape_string($_POST['uID']);

		if ($uID == $userId || $isAdmin == '1') {
			$stmt = $mysqli->prepare("UPDATE
										tasks
									SET
										taskStatus = ?,
										isClosed = 0,
										dateClosed = ?,
										lastUpdated = ?
									WHERE
										taskId = ?"
			);
			$stmt->bind_param('ssss',
								$taskStatus,
								$dateClosed,
								$lastUpdated,
								$taskId
			);
			$stmt->execute();
			$msgBox = alertBox($taskReopenedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		} else {
			$msgBox = alertBox($taskReopenErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
		}
    }
	
				// Reopen Task ADD MOVEMENT
	if (isset($_POST['submit']) && $_POST['submit'] == 'reopenTask') {
			
		$taskkId = $mysqli->real_escape_string($_POST['taskId']);
		$taskStatuss = 'Beklemede';				
		$userrId = $mysqli->real_escape_string($_POST['uID']);			//göreve sahip kullanıcı
		
		$srg = "SELECT beginDate,dueDate,moveDesc FROM movements WHERE taskId='".$taskkId."' ORDER BY moveId DESC";
		$roww = mysqli_query($mysqli, $srg) or die('-1'.mysqli_error());
		$rowww = mysqli_fetch_assoc($roww);
		
		$beginDate = $rowww['beginDate'];
		$dueDate = $rowww['dueDate'];
		$moveDesc = $rowww['moveDesc'];
		
		/*
		$price = mysql_query("SELECT beginDate FROM movements ORDER BY moveId DESC LIMIT 1");
		$result = mysql_fetch_array($price);
		$beginDate = $result[0];
		$dueDate = '0000-00-00';
		$moveDesc = 'a';
		*/
		/*
		$name = mysql_get_var("SELECT beginDate FROM movements where moveId=15");
		$moveDesc = 'a';
		*/
		if ($uID == $userId || $isAdmin == '1') {
			$stmt = $mysqli->prepare("INSERT INTO
									movements(
										taskId,
										userId,
										state,
										beginDate,
										dueDate,
										moveDesc
									) VALUES (
										?,
										?,
										?,
										?,
										?,
										?										
									)
			");
			$stmt->bind_param('ssssss',
								$taskkId,
								$userrId,
								$taskStatuss,	
								$beginDate,
								$dueDate,
								$moveDesc
			);
			$stmt->execute();
			$msgBox = alertBox($taskMarkedCompMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		} else {
			$msgBox = alertBox($taskCompErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
		}
    }

	// Delete Task
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteTask') {
		$taskId = $mysqli->real_escape_string($_POST['taskId']);
		$uID = $mysqli->real_escape_string($_POST['uID']);

		if ($uID == $userId || $isAdmin == '1') {
			// Delete the task
			$stmt = $mysqli->prepare("DELETE FROM tasks WHERE taskId = ?");
			$stmt->bind_param('s', $taskId);
			$stmt->execute();
			$stmt->close();

			// Delete all related Task Notes
			$stmt = $mysqli->prepare("DELETE FROM tasknotes WHERE taskId = ?");
			$stmt->bind_param('s', $taskId);
			$stmt->execute();
			$msgBox = alertBox($taskedDeletedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		} else {
			$msgBox = alertBox($taskDeleteErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
		}
    }
	
	// Closed Tasks
	$qry = "SELECT
				tasks.taskId,
				tasks.userId,
				tasks.custId,
				tasks.taskTitle,
				tasks.taskDesc,
				tasks.taskPriority,
				tasks.taskStatus,
				tasks.dateClosed,
				users.userFirst,
				users.userLast
			FROM
				tasks
				LEFT JOIN users ON tasks.userId = users.userId
			WHERE
				tasks.isClosed = '1'";
	$res = mysqli_query($mysqli, $qry) or die('-1'.mysqli_error());
?>
<div class="content-col" id="page">
	<div class="inner-content">
		<h3 class="font-weight-thin no-margin-top">
			<?php echo $closedTasksNavLink; ?>
			<span class="pull-right">
				<a data-toggle="modal" href="#newTask" class="btn btn-info btn-sm"><i class="fa fa-tasks" data-toggle="tooltip" data-placement="left" title="<?php echo $newTaskTooltip; ?>"></i></a></span>
		</h3>
		<hr />

		<?php if ($msgBox) { echo $msgBox; } ?>
		
		<?php if(mysqli_num_rows($res) < 1) { ?>
			<div class="alertMsg message">
				<i class="fa fa-info-circle"></i> <?php echo $noClosedTasksMsg; ?>
			</div>
		<?php } else { ?>
			<table id="taskList" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><?php echo $taskTitleTH; ?></th>
						<th><?php echo $usersText; ?></th>
						<th><?php echo $priorityTH; ?></th>
						<th><?php echo $statusTH; ?></th>
						<th><?php echo $dateClosedTH; ?></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<?php while ($row = mysqli_fetch_assoc($res)) { ?>
						<tr>
							<td><a href="index.php?page=viewTask&taskId=<?php echo $row['taskId']; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $viewTaskTooltip; ?>"><?php echo clean($row['taskTitle']); ?></a></td>
							<td><?php echo clean($row['userFirst']); ?></td>
							<td><?php echo clean($row['taskPriority']); ?></td>
							<td><?php echo clean($row['taskStatus']); ?></td>
							<td><?php echo dateFormat($row['dateClosed']); ?></td>
							<td>
								<a data-toggle="modal" href="#reopenTask<?php echo $row['taskId']; ?>" class="success"><i class="fa fa-reply-all info" data-toggle="tooltip" data-placement="top" title="<?php echo $reopenTaskText; ?>"></i></a>
								<a href="index.php?page=viewTask&taskId=<?php echo $row['taskId']; ?>" class="info"><i class="fa fa-pencil warning" data-toggle="tooltip" data-placement="top" title="<?php echo $editTooltip; ?>"></i></a>
								<a data-toggle="modal" href="#deleteTask<?php echo $row['taskId']; ?>" class="danger"><i class="fa fa-times-circle danger" data-toggle="tooltip" data-placement="top" title="<?php echo $deleteTooltip; ?>"></i></a>
							</td>
						</tr>
						
						<div class="modal fade" id="reopenTask<?php echo $row['taskId']; ?>" tabindex="-1" role="dialog" aria-labelledby="reopenTask<?php echo $row['taskId']; ?>" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<form action="" method="post">
										<div class="modal-body">
											<p class="lead"><?php echo $reopenTaskQuip; ?> <strong>"<?php echo clean($row['taskTitle']); ?>"</strong>?</p>
										</div>
										<div class="modal-footer">
											<input name="taskId" type="hidden" value="<?php echo $row['taskId']; ?>" />
											<input name="uID" type="hidden" value="<?php echo $row['userId']; ?>" />
											<button type="input" name="submit" value="reopenTask" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
											<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<div class="modal fade" id="deleteTask<?php echo $row['taskId']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteTask<?php echo $row['taskId']; ?>" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<form action="" method="post">
										<div class="modal-body">
											<p class="lead"><?php echo $deleteTaskQuip; ?> <strong>"<?php echo clean($row['taskTitle']); ?>"</strong>?</p>
										</div>
										<div class="modal-footer">
											<input name="taskId" type="hidden" value="<?php echo $row['taskId']; ?>" />
											<input name="uID" type="hidden" value="<?php echo $row['userId']; ?>" />
											<button type="input" name="submit" value="deleteTask" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $yesBtn; ?></button>
											<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
										</div>
									</form>
								</div>
							</div>
						</div>
					<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		
	</div>
</div>