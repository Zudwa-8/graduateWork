<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
if (!$_SESSION['user'])
  header('Location: ../index.php');
$title = "Пользователи";
require_once($_SERVER['DOCUMENT_ROOT']."/header.php"); 
?>
<div class="container">
  <div class="row">
    <div class="col-md-2">
      <?php 
        $liActive = "users";
        require_once($_SERVER['DOCUMENT_ROOT']."/personalArea/menu.php"); 
      ?>
    </div>
    <div class="col-md-10">
        <h3>Пользователи</h3>   
        <hr>
        <h4>Фильтры</h4>   
        <form id="filters-form" class="row row-cols-lg-auto g-3 align-items-center" action="/php/PersonalArea/filters-users.php" method="GET">
            <div class="mb-3 col-12">
                <label for="filters-inputUsername" class="form-label">Имя пользователя</label>
                <input type="text" class="form-control" id="filters-inputUsername" name="filters-inputUsername" <? if (isset($_GET['username'])) echo "value=\"".$_GET['username']."\""; ?>>
            </div>
            <div class="mb-3 col-12">
                <label for="filters-inputRole" class="form-label">Роль</label>
                <select class="form-select" id="filters-inputRole" name="filters-inputRole">
                    <option <? if (!isset($_GET['role'])) echo "selected"; ?>>Все</option>
                    <?
                        $query = "SELECT `id_role`, `role_name`  FROM `roles`";
                        $mysql_result = mysqli_query($connectDatabase, $query);
                        if ($mysql_result === false){
                            echo json_encode('Error connecting to database');
                            die();
                        }
                        $result = sqlConvert($mysql_result);
                        for ($i=0; $i < count($result); $i++) :
                    ?>
                    <option value="<? echo $result[$i]['id_role']; ?>" <? if (isset($_GET['role']) && $_GET['role'] == $result[$i]['id_role']) echo "selected"; ?>><? echo $result[$i]['role_name']; ?></option>
                    <? endfor; ?>
                </select>
            </div>      
            <div class="mb-3 col-12">
                <label for="filters-inputDate" class="form-label">Дата</label>
                <input type="date" class="form-control" id="filters-inputDate" name="filters-inputDate" <? if (isset($_GET['date'])) echo "value=\"".$_GET['date']."\""; ?>>
            </div>
            <div class="d-grid col-12">
                <button type="submit" class="btn btn-dark">Поиск</button>
            </div>
        </form>
        <hr>
        <?
            $page = (isset($_GET['page'])) ? $_GET['page'] - 1 : 0 ;
            unset($parameters['page']);
            parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $parameters);
            $where = "WHERE ";
            $whereB = false;
            if (count($parameters)>0)
            {
                $whereB = true;
                if (isset($parameters['username'])) {
                    $where .= "`username` LIKE '%".str_replace("_", "!_", $parameters['username'])."%' ESCAPE '!' AND ";
                }
                if (isset($parameters['date'])) {
                    $where .= "STR_TO_DATE(`date_register`,'%Y-%m-%d') > STR_TO_DATE('".$parameters['date']."','%Y-%m-%d') AND ";
                }
                if (isset($parameters['role'])) {
                    $where .=  "`id_role` = ".$parameters['role']." AND ";
                }
                $where = substr($where, 0, strrpos($where, " AND "));
            }
            $query = "SELECT `id_user`, `username`, `date_register`, `role_name`, `priority`  FROM `users`
                        INNER JOIN `roles` ON `roles`.`id_role` = `users`.`role_id_role`".(($whereB) ? $where : "");
            $mysql_result = mysqli_query($connectDatabase, $query);
            if ($mysql_result === false) :
                echo "<p>Записей по текущему фильтру не существует</p>";
                echo "<a class=\"btn btn-dark\" href=\"".(($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"])."\">Обнулить</a>";
            else :
                $result = sqlConvert($mysql_result);
        ?>
        <table class="table">
            <thead class="table-white">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Имя пользователя</th>
                    <th scope="col">Дата регистрации</th>
                    <th scope="col">Роль</th>
                    <th scope="col">Изменить</th>
                </tr>
            </thead>
            <tbody>
                <? for ($i=$page*20; $i < count($result); $i++) :  ?>
                <tr>
                    <th scope="row"><? echo $i+1; ?></th>
                    <td><? echo $result[$i]['username']; ?></td>
                    <td><? echo $result[$i]['date_register']; ?></td>
                    <td><? echo $result[$i]['role_name']; ?></td>
                    <td><a class="btn btn-dark <? echo ($result[$i]['priority'] <= 1) ? "disabled" : '" href="/PersonalArea/editUser.php?id_user='.$result[$i]['id_user']; ?>">Изменить</a></td>
                </tr>
                <? endfor; ?>
            </tbody>
        </table>
            <? endif; ?>   
                                
            <div class="d-flex justify-content-center mt-4">
            <div class="btn-group"> 
            <?php
                $n_pages = ceil(count($result) / 20);
                $parameters = (count($parameters)>0) ? "&".http_build_query($parameters) : "" ;
                $page++;
                if ($page <= 5):
                    for ($i = $page * 10; $i < $page * 10 + 10 && $i < count($result); $i++) :
            ?>
                <a class="btn btn-dark" href="<?echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?page=".$i.$parameters?>"><?echo $i?></a>
            <?php
                    endfor;
                else : 
            ?>
                <a class="btn btn-dark" href="<?echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?page=1"?>">1</a>
                <button  class="btn btn-dark disabled">...</button >
                <a class="btn btn-dark" href="<?echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?page=".($page-2).$parameters?>"><?echo $page-2?></a>
                <a class="btn btn-dark" href="<?echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?page=".($page-1).$parameters?>"><?echo $page-1?></a>
            <?php
                endif;
            ?>
                <button  class="btn btn-dark active"><?echo $page?></button >
            <?php
                if ($page + 5 > $n_pages):
                    for ($i=$page + 1; $i <= $n_pages; $i++) :
            ?>
                <a class="btn btn-dark" href="<?echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?page=".$i.$parameters?>"><?echo $i?></a>
            <?php
                    endfor;
                else : 
            ?>
                <a class="btn btn-dark" href="<?echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?page=".($page+1).$parameters?>"><?echo $page+1?></a>
                <a class="btn btn-dark" href="<?echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?page=".($page+2).$parameters?>"><?echo $page+2?></a>
                <button class="btn btn-dark disabled">...</button>
                <a class="btn btn-dark" href="<?echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?page=".$n_pages.$parameters?>"><?echo $n_pages?></a>
            <?php
                endif;
            ?>

            </div>
        </div>
    </div>
  </div>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/footer.php"); ?>