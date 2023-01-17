<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
if (!$_SESSION['user'])
  header('Location: ../index.php');
$title = "Личный кабинет";
require_once($_SERVER['DOCUMENT_ROOT']."/header.php"); 
?>
<div class="container">
  <div class="row">
    <div class="col-md-2">
      <?php 
        $liActive = "PersonalInformation";
        require_once($_SERVER['DOCUMENT_ROOT']."/personalArea/menu.php"); 
      ?>
    </div>
    <div class="col-md-10">
        <h3>Персональные данные</h3>   
        <hr>
        <form class="needs-validation" id="PersonalInformation-form" novalidate>
            <div class="mb-3">
                <label for="ChangePassword-inputPassword" class="form-label">Старый пароль</label>
                <input type="password" class="form-control" id="ChangePassword-inputPassword" maxlength="25" required>
                <div class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
                <label for="ChangePassword-inputNewPassword" class="form-label">Новый пароль</label>
                <input type="password" class="form-control" id="ChangePassword-inputNewPassword" required>
                <div class="invalid-feedback"></div>
            </div>      
            <div class="mb-3">
                <label for="ChangePassword-inputNewPasswordСonfirm" class="form-label">Подтвердите новый пароль</label>
                <input type="password" class="form-control" id="ChangePassword-inputNewPasswordСonfirm" required>
                <div class="invalid-feedback"></div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-dark">Изменить пароль</button>
            </div>
        </form>
    </div>
  </div>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/footer.php"); ?>