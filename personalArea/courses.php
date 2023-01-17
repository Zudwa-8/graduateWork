<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
if (!$_SESSION['user'])
  header('Location: ../index.php');
$title = "Курсы";
require_once($_SERVER['DOCUMENT_ROOT']."/header.php"); 
?>
<div class="container">
  <div class="row">
    <div class="col-md-2">
      <?php 
        $liActive = "courses";
        require_once($_SERVER['DOCUMENT_ROOT']."/personalArea/menu.php"); 
      ?>
    </div>
    <div class="col-md-10">
        <h3>Курсы</h3>   
        <hr>
        
    </div>
  </div>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/footer.php"); ?>