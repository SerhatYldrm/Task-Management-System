<?php
	include 'includes/navigation.php';
	$jsFile = 'searchResults';
	
	$searchTerm = $mysqli->real_escape_string($_POST['searchTerm']);
	$searchUC = strtolower($searchTerm);
	$searchLC = strtoupper($searchTerm);
	
	// Search Tasks
	$qry1 = "SELECT
				tasks.taskId,
				tasks.userId,
				tasks.custId,
				tasks.taskTitle,
				tasks.taskDesc,
				tasks.taskPriority,
				tasks.taskStatus,
				tasks.taskPercent,
				tasks.taskDue,
				tasks.isClosed,
				UNIX_TIMESTAMP(tasks.isClosed) AS orderDate,
				users.userFirst,
				users.userLast
			FROM
				tasks
				LEFT JOIN users ON tasks.userId = users.userId
			WHERE
				(tasks.taskTitle LIKE '%".$searchTerm."%' OR tasks.taskDesc LIKE '%".$searchTerm."%' OR
				tasks.taskTitle LIKE '%".$searchUC."%' OR tasks.taskDesc LIKE '%".$searchUC."%' OR
				tasks.taskTitle LIKE '%".$searchLC."%' OR tasks.taskDesc LIKE '%".$searchLC."%')
			GROUP BY tasks.taskId
			ORDER BY orderDate";
	$res1 = mysqli_query($mysqli, $qry1) or die('-1'.mysqli_error());
	$rowstot1 = mysqli_num_rows($res1);
	
	// Search Categories
	$qry2 = "SELECT
				customerID,
				cust_name,
				contract_type,
				contract_startdate,
				UNIX_TIMESTAMP(contract_startdate) AS orderDate,
				isActive
			FROM
				customers
			WHERE
				(cust_name LIKE '%".$searchTerm."%' OR contract_type LIKE '%".$searchTerm."%' OR
				cust_name LIKE '%".$searchUC."%' OR contract_type LIKE '%".$searchUC."%' OR
				cust_name LIKE '%".$searchLC."%' OR contract_type LIKE '%".$searchLC."%')
			GROUP BY customerID
			ORDER BY orderDate";
	$res2 = mysqli_query($mysqli, $qry2) or die('-1'.mysqli_error());
	$rowstot2 = mysqli_num_rows($res2);
	
	// Search Dates
	$qry3 = "SELECT
				eventId,
				startDate,
				UNIX_TIMESTAMP(startDate) AS orderDate,
				endDate,
				eventTitle,
				eventDesc,
				isTask
			FROM
				events
			WHERE
				(eventTitle LIKE '%".$searchTerm."%' OR eventDesc LIKE '%".$searchTerm."%' OR
				eventTitle LIKE '%".$searchUC."%' OR eventDesc LIKE '%".$searchUC."%' OR
				eventTitle LIKE '%".$searchLC."%' OR eventDesc LIKE '%".$searchLC."%') AND
				userId = ".$userId."
			GROUP BY eventId
			ORDER BY orderDate";
	$res3 = mysqli_query($mysqli, $qry3) or die('-1'.mysqli_error());
	$rowstot3 = mysqli_num_rows($res3);
	
	// Search Users
	$qry4 = "SELECT
				userId,
				userEmail,
				userFirst,
				userLast,
				joinDate,
				isActive
			FROM
				users
			WHERE
				(userFirst LIKE '%".$searchTerm."%' OR userLast LIKE '%".$searchTerm."%' OR
				userFirst LIKE '%".$searchUC."%' OR userLast LIKE '%".$searchUC."%' OR
				userFirst LIKE '%".$searchLC."%' OR userLast LIKE '%".$searchLC."%')
			GROUP BY userId
			ORDER BY joinDate";
	$res4 = mysqli_query($mysqli, $qry4) or die('-1'.mysqli_error());
	$rowstot4 = mysqli_num_rows($res4);
	
	// Total Results
	$totResults = $rowstot1 + $rowstot2 + $rowstot3 + $rowstot4;
	
?>
<div class="content-col" id="page">
	<div class="inner-content">
		<h3 class="font-weight-thin no-margin-top">
			Search Results
			<small class="pull-right mt-10 text-success"><?php echo $totResults; ?> Results Found</small>
		</h3>
		<hr />

		<?php if ($msgBox) { echo $msgBox; } ?>
		
		<?php
			if ($totResults > 0) {
				if ($rowstot1 > 0) {
					while ($row1 = mysqli_fetch_assoc($res1)) {
		?>
						<div class="list-group mt-10 mb-0">
							<a href="index.php?page=viewTask&taskId=<?php echo $row1['taskId']; ?>" class="list-group-item">
								<h4 class="list-group-item-heading">
									<div class="icon"><i class="fa fa-tasks"></i></div>
									<span class="label label-tasked"><i class="fa fa-tag"></i><?php echo clean($row1['taskTitle']); ?></span>
									<?php echo "Kullanıcı: ".clean($row1['userFirst']); ?><?php echo " ".clean($row1['userLast']); ?>
								</h4>
								<p class="list-group-item-text"><?php echo "Acıklama: ".ellipsis($row1['taskDesc'],90); ?></p>
							</a>
						</div>
			<?php
					}
				}
				if ($rowstot2 > 0) {
					while ($row2 = mysqli_fetch_assoc($res2)) {
			?>
						<div class="list-group mt-10 mb-0">
							<a href="index.php?page=viewCustomer&cid=<?php echo $row2['customerID']; ?>" class="list-group-item">
								<h4 class="list-group-item-heading">
									<div class="icon"><i class="fa fa-tag"></i></div>
									<?php echo clean($row2['cust_name']); ?>
								</h4>
								<p class="list-group-item-text"><?php echo ellipsis($row2['contract_type'],90); ?></p>
							</a>
						</div>
			<?php
					}
				}
				if ($rowstot3 > 0) {
					while ($row3 = mysqli_fetch_assoc($res3)) {
						$eventDesc = str_replace(array("\r", "\n"), " ", $row3['eventDesc']);
			?>
						<div class="list-group mt-10 mb-0">
							<a href="index.php?page=calendar" class="list-group-item">
								<h4 class="list-group-item-heading">
									<div class="icon"><i class="fa fa-calendar-o"></i></div>
									<span class="label label-tasked"><i class="fa fa-calendar"></i><?php echo dateFormat($row3['startDate']); ?></span>
									<?php echo clean($row3['eventTitle']); ?>
								</h4>
								<p class="list-group-item-text"><?php echo ellipsis($eventDesc,90); ?></p>
							</a>
						</div>
			<?php
					}
				}
				if ($rowstot4 > 0) {
					while ($row4 = mysqli_fetch_assoc($res4)) {
				
			?>				
						<div class="list-group mt-10 mb-0">
							<?php
								if($row4['userId'] == $userId){				//if user search own name 
									echo '<a href="index.php?page=profile"';
								}
								else{
									echo '<a href="index.php?page=viewUser&uid='.$row4['userId'].'" class="list-group-item">';
								}									
							?>	
								<h4 class="list-group-item-heading">
									<div class="icon"><i class="fa fa-tag"></i></div>
									<?php echo clean($row4['userFirst']); ?><?php echo " ".clean($row4['userLast']); ?>
								</h4>
								<p class="list-group-item-text"><?php echo ellipsis($row4['userEmail'],90); ?></p>
							</a>
						</div>						
		<?php
					}
				}
			}	
		?>		
	</div>
</div>