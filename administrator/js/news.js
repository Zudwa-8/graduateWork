function check(inputName, err) {
    var elem = document.getElementById(inputName)
    if (typeof err !== 'undefined') {
        $(elem).addClass("is-invalid")
        $(elem).next().html(err)
    }
    else {
        $(elem).addClass("is-valid")
    }
}
function deleteField(id, name) {
    if (confirm("Вы уверены, что хотите удалить новость: \"" + name + "\"?")) {
        var formData = new FormData();
        formData.append("id_new", id);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/administrator/php/deleteNew.php", false);
        xhr.send(formData);
        location.reload();
    }
}
$('#image').on('change', function(){
    console.log("start script");
    
    if(this.files.length == 0) return;

    var data = new FormData();
	$.each(this.files, function( key, value ){
		data.append( key, value );
	});
	data.append( 'my_file_upload', 1 );
	data.append( 'folder', 'news' );

    $.ajax({
		url         : '/administrator/php/loadImg.php',
		type        : 'POST',
		data        : data,
		cache       : false,
		dataType    : 'json',
		// отключаем обработку передаваемых данных, пусть передаются как есть
		processData : false,
		// отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
		contentType : false,
		// функция успешного ответа сервера
		success     : function( respond, status, jqXHR ){

			// ОК
			if( typeof respond.error === 'undefined' ){
                console.log(respond.files);
                $("#img-preview").attr("alt", respond.files);
                $("#img-preview").attr("src", "/img/news/" + respond.files);
                $("#image_name").attr("value", respond.files);
                
			}
			// error
			else {
				console.log('ОШИБКА: ' + respond.error );
			}
		},
		// функция ошибки ответа сервера
		error: function( jqXHR, status, errorThrown ){
			console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
		}

	});
    
    console.log("end script");
});
var form = document.getElementById("AddNew-form")

form.addEventListener('submit', (e) => {
    if (typeof $("#Messeage-alert") !== 'undefined') {
        $("#Messeage-alert").remove();
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
    data['validation[image_name]'] = $("#image_name").attr("value");

    // делаем ajax-запрос методом POST на текущий адрес, в ответ ждем данные HTML
    $.ajax({
        type: 'POST',
        url: '/administrator/php/addNew.php',
        dataType: 'html',
        data: data,
        // до выполнения запроса
        beforeSend: function () {
        },
        // в случае удачного выполнения добавляем блок с сообщением
        success: function (errors) {
            var jsonEncode = jQuery.parseJSON(errors);
            if (jsonEncode == 'News added successfully') {
                console.log('News added successfully')
                $(btn).before(`
          <div class="alert alert-success" role="alert" id="Messeage-alert">
              Новость успешно добавлена.
          </div>
          `);
                document.getElementById('AddNew-inputTitle').value = "";
                $("#AddNew-inputText").htmlcode("");
                $("#AddNew-inputText").sync();
                console.log('alert-success added successfully')
            }
            else if (jsonEncode == 'Failed to get data') {
                console.log('Failed to get data')
                btn.before(`
          <div class="alert alert-danger" role="alert" id="Messeage-alert">
              Ошибка при отправке данных на сервер.
          </div>
          `);
            }
            else if (jsonEncode == 'Error connecting to database') {
                console.log('Error connecting to database')
                btn.before(`
          <div class="alert alert-danger" role="alert" id="Messeage-alert">
              Ошибка при подключении к базе данных.
          </div>
          `);
            }
            else if (jsonEncode == 'Fill in all the fields') {
                console.log('Error connecting to database')
                btn.before(`
          <div class="alert alert-danger" role="alert" id="Messeage-alert">
              Заполните все поля.
          </div>
          `);
            }
            else {
                check('AddNew-inputTitle', jsonEncode['AddNew-inputTitle'])
                check('AddNew-inputText', jsonEncode['AddNew-inputText'])
            }
            btn.removeClass('disabled');
        }
    });
    e.preventDefault();
});