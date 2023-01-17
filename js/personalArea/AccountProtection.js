function check(inputName, err) {
    var elem = document.getElementById(inputName) 
    if (typeof err !== 'undefined') {
        $(elem).addClass("is-invalid")
        $(elem).next().html(err)
    }
    else{
        $(elem).addClass("is-valid")
    }
}
  
var form = document.getElementById("ChangePassword-form")
  
form.addEventListener('submit', (e) =>{
    if(typeof $("#ChangePassword-alert") !== 'undefined'){
      $("#ChangePassword-alert").remove();
    }
    $(form).find("div input").removeClass('is-invalid')
    $(form).find("div input").removeClass('is-valid')
    var btn = $(form).closest('form').find(':submit');
    btn.closest('form').find(':submit').addClass('disabled');

    var data = {};
  
    // формируем массив для отправки на сервер 
    data['validation[ChangePassword-inputPassword]'] = document.getElementById('ChangePassword-inputPassword').value;
    data['validation[ChangePassword-inputNewPassword]'] = document.getElementById('ChangePassword-inputNewPassword').value;
    data['validation[ChangePassword-inputNewPasswordСonfirm]'] = document.getElementById('ChangePassword-inputNewPasswordСonfirm').value;
    
    // делаем ajax-запрос методом POST на текущий адрес, в ответ ждем данные HTML
    $.ajax({
      type: 'POST',
      url: '/php/PersonalArea/AccountProtectionChangePassword.php',
      dataType: 'html',
      data: data,
      // до выполнения запроса
      beforeSend: function()
      {
      },
      // в случае удачного выполнения добавляем блок с сообщением
      success: function(errors)
      {
        var jsonEncode = jQuery.parseJSON(errors);
        if (jsonEncode == 'Password changed successfully') {
          console.log('Password changed successfully')
          $(btn).before(`
          <div class="alert alert-success" role="alert" id="ChangePassword-alert">
              Пароль успешно изменен.
          </div>
          `);
        }
        else if(jsonEncode == 'Failed to get data'){
          console.log('Failed to get data')
          btn.before(`
          <div class="alert alert-danger" role="alert" id="ChangePassword-alert">
              Ошибка при отправке данных на сервер.
          </div>
          `);
        }
        else if(jsonEncode == 'Error connecting to database'){
          console.log('Error connecting to database')
          btn.before(`
          <div class="alert alert-danger" role="alert" id="ChangePassword-alert">
              Ошибка при подключении к базе данных.
          </div>
          `);
        }
        else{
          check('ChangePassword-inputNewPassword', jsonEncode['ChangePassword-inputNewPassword'])
          if (!$("#ChangePassword-inputNewPassword").hasClass('is-invalid')) {
            check('ChangePassword-inputNewPasswordСonfirm', jsonEncode['ChangePassword-inputNewPasswordСonfirm'])
            if (!$("#ChangePassword-inputNewPasswordСonfirm").hasClass('is-invalid')){
              check('ChangePassword-inputPassword', jsonEncode['ChangePassword-inputPassword'])
            }
          }
        }
      }
    });
    btn.removeClass('disabled');
    e.preventDefault();
  });