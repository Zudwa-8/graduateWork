<?
    require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
    
    $headers .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>';
    $headers .= '<script src="/js/jquery.wysibb.min.js"></script>';
    $headers .= '<script src="/js/lang/ru.js"></script>';
    $headers .= '<link rel="stylesheet" href="/css/wbbtheme.css"/>';
    $headers .= '<script src="/js/replyBtn.js"></script>';

    function output_comments($pageComment, $id_post, $id_parent = null){
        echo '<h3 class="mt-5">Комментарии</h3>   
            <hr class="m-0 mb-4">
            <div id="comment-form" class="mt-2 mb-4">
                <div class="mb-3">
                    <label for="comment-inputText" class="form-label"><h5 class="mb-0">Оставить комментарий</h5></label>
                        <script>
                            $(document).ready(function() {
                                var wbbOpt = {
                                    lang: "ru",
                                    imgupload:			false,
                                    buttons:            "bold,italic,underline"
                                }
                            $("#comment-inputText").wysibb(wbbOpt);
                            });
                        </script>
                        <textarea class="form-control" id="comment-inputText" rows="2" maxlength="65000"></textarea>
                </div>
                <div class="invalid-feedback" id="comment-form-msg-alert"></div>
                <div class="d-flex justify-content-end">';
        if ($_SESSION['user']){
            echo '<button type="submit" class="btn btn-dark" onclick="addComment()">Оставить комментарий</button>';
        }
        else{
            echo '<button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticmodalRegisterLogin">Оставить комментарий</button>';
        }
        echo '</div> 
            </div>
            <div>';
        get_comments($pageComment, $id_post, $id_parent);
        echo '</div>';
    }

    function get_comments($pageComment, $id_post, $id_parent = null){
        global $connectDatabase, $month_name;
        if ($id_parent == null) {
            switch ($pageComment) {
                case 'new':
                    $query = "SELECT `id_comments_news`, `text`, `username`, `date` FROM `comments_news`
                    INNER JOIN `users` ON `users`.`id_user` = `comments_news`.`users_id_user`
                    WHERE `news_id_new` = ".$id_post." AND `id_parent` IS NULL 
                    ORDER BY `date`";
                    break;
                case 'course':
                    $query = "SELECT `id_comments_courses`, `text`, `username`, `date` FROM `comments_courses`
                    INNER JOIN `users` ON `users`.`id_user` = `comments_courses`.`users_id_user`
                    WHERE `courses_id_course` = ".$id_post." AND `id_parent` IS NULL 
                    ORDER BY `date`";
                    break;
                case 'lesson':
                    $query = "SELECT `id_comments_lessons`, `text`, `username`, `date` FROM `comments_lessons`
                    INNER JOIN `users` ON `users`.`id_user` = `comments_lessons`.`users_id_user`
                    WHERE `lessons_id_lesson` = ".$id_post." AND `id_parent` IS NULL 
                    ORDER BY `date`";
                    break;
                default:
                    die('<p class="mt-1 text-center">Комментариев пока нет. Будьте первым и напишите что-нибудь!</p>');
                    break;
            }
            $mysql_result = mysqli_query($connectDatabase, $query);
            if ($mysql_result === false)
                die('Error: '.mysqli_error($connectDatabase));
            $result = sqlConvert($mysql_result);
            if (count($result) == 0){
                echo '<p class="mt-1 text-center">Комментариев пока нет. Будьте первым и напишите что-нибудь!</p>';
            }
            else{
                for ($i=0; $i < count($result); $i++){
                    echo '<div class="mt-4 mb-2">
                    <h6 class="ms-3">'.$result[$i]['username'].', '.date("d", strtotime($result[$i]['date']))." ".$month_name[date('n', strtotime($result[$i]['date']))-1]." ".date("Y", strtotime($result[$i]['date'])).", ".date("h:i", strtotime($result[$i]['date'])).'</h6>
                    <p class="border border-2 rounded p-2 mb-1">'.$result[$i]['text'].'</p>
                    <div class="d-flex justify-content-end">';
                    if ($_SESSION['user']){
                        switch ($pageComment) {
                            case 'new':
                                echo 
                                '<button class="btn btn-dark btn-sm me-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnswers'.$i.'" aria-expanded="false" aria-controls="collapseExample">
                                    Показать ответы
                                </button>
                                <button class="btn btn-dark btn-sm" onclick="openForm(this, '.$result[$i]['id_comments_news'].')">Ответить</button>
                                </div>';
                                break;
                            case 'course':
                                echo 
                                '<button class="btn btn-dark btn-sm me-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnswers'.$i.'" aria-expanded="false" aria-controls="collapseExample">
                                    Показать ответы
                                </button>
                                <button class="btn btn-dark btn-sm" onclick="openForm(this, '.$result[$i]['id_comments_courses'].')">Ответить</button>
                                </div>';
                                break;
                            case 'lesson':
                                echo 
                                '<button class="btn btn-dark btn-sm me-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnswers'.$i.'" aria-expanded="false" aria-controls="collapseExample">
                                    Показать ответы
                                </button>
                                <button class="btn btn-dark btn-sm" onclick="openForm(this, '.$result[$i]['id_comments_lessons'].')">Ответить</button>
                                </div>';
                                break;
                            default:
                                break;
                        }
                        echo '</div>';
                    }else{
                        echo 
                        '<button class="btn btn-dark btn-sm me-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnswers'.$i.'" aria-expanded="false" aria-controls="collapseExample">
                            Показать ответы
                        </button>
                        <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#staticmodalRegisterLogin">Ответить</button>
                        </div>';
                    }
                    switch ($pageComment) {
                        case 'new':
                            echo '<div class="collapse" id="collapseAnswers'.$i.'">';
                            get_comments($pageComment, $id_post, $result[$i]['id_comments_news']);
                            echo '</div>';
                            break;
                        case 'course':
                            echo '<div class="collapse" id="collapseAnswers'.$i.'">';
                            get_comments($pageComment, $id_post, $result[$i]["id_comments_courses"]);
                            echo '</div>';
                            break;
                        case 'lesson':
                            echo '<div class="collapse" id="collapseAnswers'.$i.'">';
                            get_comments($pageComment, $id_post, $result[$i]["id_comments_lessons"]);
                            echo '</div>';
                            break;
                        default:
                            break;
                    }
                    echo '</div>';
                }
            }
        }
        else{
            switch ($pageComment) {
                case 'new':
                    $query = "SELECT `id_comments_news`, `text`, `username`, `date` FROM `comments_news`
                    INNER JOIN `users` ON `users`.`id_user` = `comments_news`.`users_id_user`
                    WHERE `news_id_new` = ".$id_post." AND `id_parent` = $id_parent
                    ORDER BY `date`";   
                    break;
                case 'course':
                    $query = "SELECT `id_comments_courses`, `text`, `username`, `date` FROM `comments_courses`
                    INNER JOIN `users` ON `users`.`id_user` = `comments_courses`.`users_id_user`
                    WHERE `courses_id_course` = ".$id_post." AND `id_parent` = $id_parent
                    ORDER BY `date`";   
                    break;
                case 'lesson':
                    $query = "SELECT `id_comments_lessons`, `text`, `username`, `date` FROM `comments_lessons`
                    INNER JOIN `users` ON `users`.`id_user` = `comments_lessons`.`users_id_user`
                    WHERE `lessons_id_lesson` = ".$id_post." AND `id_parent` = $id_parent
                    ORDER BY `date`";   
                    break;
            }
            
            $mysql_result = mysqli_query($connectDatabase, $query);
            if ($mysql_result === false)
                die('Error connecting to server');
            $result = sqlConvert($mysql_result);
            for ($i=0; $i < count($result); $i++) { 
                echo '
                <div class="mt-1 ms-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up float-start me-2" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"/>
                </svg>
                <h6 class="ms-3">'.$result[$i]['username'].', '.date("d", strtotime($result[$i]['date']))." ".$month_name[date('n', strtotime($result[$i]['date']))-1]." ".date("Y", strtotime($result[$i]['date'])).", ".date("h:i", strtotime($result[$i]['date'])).'</h6>
                <p class="border border-2 rounded p-2 mb-1">'.$result[$i]['text'].'</p>
                <div class="d-flex justify-content-end">';
                if ($_SESSION['user']){
                    echo '<button class="btn btn-dark btn-sm btn-sm" onclick="openForm(this, '.$result[$i]['id_comments_news'].')">Ответить</button>
                    </div>';
                }else{
                    echo '<button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#staticmodalRegisterLogin">Ответить</button>
                    </div>';
                }
                switch ($pageComment) {
                    case 'new':
                        get_comments($pageComment, $id_post, $result[$i]['id_comments_news']);
                        break;
                    case 'course':
                        get_comments($pageComment, $id_post, $result[$i]['id_comments_courses']);
                        break;
                    case 'lesson':
                        get_comments($pageComment, $id_post, $result[$i]['id_comments_lessons']);
                        break;
                    default:
                        break;
                }
                echo '</div>';
            }
        }
    }
?>