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

$('#image').on('change', function(){
    console.log("start script");
    
    if(this.files.length == 0) return;

    var data = new FormData();
	$.each(this.files, function( key, value ){
		data.append( key, value );
	});
	data.append( 'my_file_upload', 1 );
	data.append( 'folder', 'courses' );

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
                $("#img-preview").attr("src", "/img/courses/" + respond.files);
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

var form = document.getElementById("course-form")

$('#submit-add').on('click', function(e) {
    e.preventDefault();
    if (typeof $("#Messeage-alert") !== 'undefined') {
        $("#Messeage-alert").remove();
    }
    $(form).find("div input").removeClass('is-invalid')
    $(form).find("div input").removeClass('is-valid')
    var btn = $(form).closest('form').find(':submit');
    btn.closest('form').find(':submit').addClass('disabled');

    var data = {};

    // формируем массив для отправки на сервер 
    data['validation[name]'] = document.getElementById('name').value;
    $("#description").sync();
    data['validation[description]'] = $("#description").htmlcode();
    data['validation[price]'] = 0;
    data['validation[image_name]'] = document.getElementById('image_name').value;
    data['validation[level_of_knowledge]'] = document.getElementById('level_of_knowledge').value;
    
    // делаем ajax-запрос методом POST на текущий адрес, в ответ ждем данные HTML
    $.ajax({
        type: 'POST',
        url: '/administrator/php/addCourse.php',
        dataType: 'html',
        data: data,
        // до выполнения запроса
        beforeSend: function () {
        },
        // в случае удачного выполнения добавляем блок с сообщением
        success: function (msg) 
        {
            var jsonEncode = jQuery.parseJSON(msg);
            if (jsonEncode.msg == 'Added successfully') {
                alert('Курс успешно добавлен.');
                window.location.href = location.href + "?id_course=" + jsonEncode.id;
            }
            else if (jsonEncode.msg == 'Failed to get data') {
                btn.before(`
            <div class="alert alert-danger" role="alert" id="Messeage-alert">
                Ошибка при отправке данных на сервер.
            </div>
            `);
            }
            else if (jsonEncode.msg == 'Error connecting to database') {
                btn.before(`
            <div class="alert alert-danger" role="alert" id="Messeage-alert">
                Ошибка при подключении к базе данных.
            </div>
            `);
            }
            else if (jsonEncode.msg == 'Fill in all the fields') {
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

$('#submit-edit').on('click', function(e) {
    e.preventDefault();
    if (typeof $("#Messeage-alert") !== 'undefined') {
        $("#Messeage-alert").remove();
    }
    $(form).find("div input").removeClass('is-invalid')
    $(form).find("div input").removeClass('is-valid')
    var btn = $(form).closest('form').find(':submit');
    btn.closest('form').find(':submit').addClass('disabled');

    var data = {};

    // формируем массив для отправки на сервер 
    data['validation[id_course]'] = document.getElementById('id_course').value;
    data['validation[name]'] = document.getElementById('name').value;
    $("#description").sync();
    data['validation[description]'] = $("#description").htmlcode();
    data['validation[price]'] = 0;
    data['validation[image_name]'] = document.getElementById('image_name').value;
    data['validation[level_of_knowledge]'] = document.getElementById('level_of_knowledge').value;
    
    // делаем ajax-запрос методом POST на текущий адрес, в ответ ждем данные HTML
    $.ajax({
        type: 'POST',
        url: '/administrator/php/editCourse.php',
        dataType: 'html',
        data: data,
        // до выполнения запроса
        beforeSend: function () {
        },
        // в случае удачного выполнения добавляем блок с сообщением
        success: function (msg) 
        {
            var jsonEncode = jQuery.parseJSON(msg);
            if (jsonEncode.msg == 'Added successfully') {
                alert('Курс успешно изменен.');
            }
            else if (jsonEncode.msg == 'Failed to get data') {
                btn.before(`
            <div class="alert alert-danger" role="alert" id="Messeage-alert">
                Ошибка при отправке данных на сервер.
            </div>
            `);
            }
            else if (jsonEncode.msg == 'Error connecting to database') {
                btn.before(`
            <div class="alert alert-danger" role="alert" id="Messeage-alert">
                Ошибка при подключении к базе данных.
            </div>
            `);
            }
            else if (jsonEncode.msg == 'Fill in all the fields') {
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