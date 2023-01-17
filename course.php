<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
if (!$_GET['id']) {
    header('Location: ../courses.php');
}
require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
$query = "SELECT `id_course`, `courses`.`name` AS `course_name`, `description`, `price`, `image`, `level_of_knowledge`.`name` AS `level_name` FROM `courses`
        INNER JOIN `level_of_knowledge` ON `level_of_knowledge`.`id_level_of_knowledge` = `courses`.`level_of_knowledge_id_level_of_knowledge`
        WHERE `id_course` = ".$_GET['id'];
$mysql_result = mysqli_query($connectDatabase, $query);
if ($mysql_result === false)
    die(mysqli_error($connectDatabase)); 
$result = sqlConvert($mysql_result);
$id = $result[0]['id_course'];
$title = $result[0]['course_name'];
require_once($_SERVER['DOCUMENT_ROOT']."/php/comment.php");
require_once($_SERVER['DOCUMENT_ROOT']."/header.php");
?>
<div class="container">
    <div class="mb-5">
        <div class="card px-5 mb-5 mt-5 overflow-hidden shadow-lg border border-dark">
            <div class="card-header">
                <h1 class="text-header text-center"><? echo $result[0]['course_name'] ?></h1>
            </div>
            <div class="card-body">
                
                <!-- <hr class="mt-0"> -->
                <div class="row mb-3 p-4">
                    <div class="col-8">
                        <p><? echo $result[0]['description'] ?></p>
                    </div>
                    <div class="col-4">
                        <img class="img-fluid h-100" src="img/courses/<? echo $result[0]['image'] ?>" alt="img_course">
                    </div>
                </div>
            </div>
        </div>
        <h2 class="text-header text-center">Список тем курса</h2>
        <hr class="mt-0 mb-4">
        <div class="accordion mb-5" id="accordionTopics">
            <? 
                $query = "SELECT * FROM `topics`
                WHERE `courses_id_course` = ".$id;
                $mysql_result = mysqli_query($connectDatabase, $query);
                if ($mysql_result === false)
                    die(mysqli_error($connectDatabase)); 
                $result = sqlConvert($mysql_result);
                for ($i=0; $i < count($result); $i++) :
            ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="topic-heading<? echo $i ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#topic-collapse<? echo $i ?>" aria-expanded="false" aria-controls="topic-collapse<? echo $i ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-text" viewBox="0 0 16 16">
                            <path d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                            <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                            <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                        </svg>
                        <p class="m-0">&nbsp;&nbsp;<? echo $result[$i]['name'] ?></p>
                    </button>
                </h2>
                <div id="topic-collapse<? echo $i ?>" class="accordion-collapse collapse" aria-labelledby="topic-heading<? echo $i ?>" data-bs-parent="#accordionTopics">
                    <div class="accordion-body border-start border-info border-3">
                        <? echo $result[$i]['description'] ?>
                    </div>
                </div>
            </div>
            <? endfor ?>
        </div>
        <? if($_SESSION['user']): ?>
        <div class="text-center"><a class="btn btn-dark w-25" id="coursePurchased">Добавить курс</a></div>
        <script>
            document.getElementById('coursePurchased').addEventListener('click', (e) =>{
                $(document.getElementById('coursePurchased')).addClass('disabled');

                var data = {};
            
                data['id'] = <? echo $_GET['id']; ?>;
                
                $.ajax({
                type: 'POST',
                url: '/php/buyCourse.php',
                dataType: 'html',
                data: data,
                beforeSend: function()
                {
                },
                success: function(errors)
                {
                    if(errors == 'User is not found'){
                        alert("Пожалуйста, сначала авторизуетесь.")
                    }
                    if(errors == 'Course purchased'){
                        alert("Курс уже добавлен, зайдите в личный кабинет.")
                    }
                    if(errors == 'Course added'){
                        alert("Курс успешно добавлен.")
                    }
                }
                });
                $(document.getElementById('coursePurchased')).removeClass('disabled');
                e.preventDefault();
            })
        </script>
        <? else: ?>
        <div class="text-center"><a class="btn btn-dark w-25" data-bs-toggle="modal" data-bs-target="#staticmodalRegisterLogin">Добавить курс</a></div>
        <? endif; ?>
    </div>
    <div class="mb-5">
        <? output_comments('course', $id) ?>
    </div>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/footer.php"); ?>