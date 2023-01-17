<?
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");

$query = "SELECT * FROM users WHERE id_user = 666";
$mysql_result = mysqli_query($connectDatabase, $query);
        if ($mysql_result === false)
            die(mysqli_error($connectDatabase)); 
        $result = sqlConvert($mysql_result);
        
echo '<pre>';
var_dump($result);
echo '</pre>';

echo count($result);
?>