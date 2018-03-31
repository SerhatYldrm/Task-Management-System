<?php 
include 'includes/navigation.php';
include("chat_pages/config.php");
?>
<!DOCTYPE html>
<html>
 <head>	
  <script src="//code.jquery.com/jquery-latest.js"></script>
  <script src="js/includes/chat.js"></script>
  <link href="css/chat.css" rel="stylesheet"/>
 </head> 
 <body>
	<div class="content-col">
		<div class="inner-content">
			<h1 class="font-weight-thin no-margin-top">Kalem Yazılım Grup Sohbeti</h1>
			<hr />		
  <div id="content" style="margin-top:10px;height:100%;">
   <div class="chat">
    <div class="users">
     <?php include("users.php");?>
    </div>
    <div class="chatbox">
     <?php
	 if(isset($_SESSION['userFirst'])){
      include("chatbox.php");
	 }
     ?>
	 </div>
	</div>
    </div>   
   </div>
  </div>
 </body>
</html>
