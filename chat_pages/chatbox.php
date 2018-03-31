<?php
include ("config.php");
if(isset($_SESSION['userFirst'])){
?>
 <h2>SOHBET</h2>
 <div class='msgs'>
  <?php include("msgs.php");?>
 </div>
 <form id="msg_form">
  <input name="msg" size="30" type="text"/>
  <button>Gönder</button>
 </form>
<?php
}
?>