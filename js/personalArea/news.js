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
  
var form = document.getElementById("AddNew-form")
  
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
    data['validation[AddNew-inputTitle]'] = document.getElementById('AddNew-inputTitle').value;
    $("#AddNew-inputText").sync();
    data['validation[AddNew-inputText]'] = $("#AddNew-inputText").htmlcode();
    
    // делаем ajax-запрос методом POST на текущий адрес, в ответ ждем данные HTML
    $.ajax({
      type: 'POST',
      url: '/php/PersonalArea/AddNew.php',
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
        if (jsonEncode == 'News added successfully') {
          console.log('News added successfully')
          $(btn).before(`
          <div class="alert alert-success" role="alert" id="ChangePassword-alert">
              Новость успешно добавлена.
          </div>
          `);
          console.log('alert-success added successfully')
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
        else if(jsonEncode == 'Fill in all the fields'){
          console.log('Error connecting to database')
          btn.before(`
          <div class="alert alert-danger" role="alert" id="ChangePassword-alert">
              Заполните все поля.
          </div>
          `);
        }
        else{
          check('AddNew-inputTitle', jsonEncode['AddNew-inputTitle'])
          check('AddNew-inputText', jsonEncode['AddNew-inputText'])
        }
        btn.removeClass('disabled');
      }
    });
    e.preventDefault();
  });