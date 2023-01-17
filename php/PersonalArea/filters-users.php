<?
    require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
    // $_SERVER['HTTP_REFERER']
    parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $parameters);
    $parametersN = [];
    if (isset($query['page'])) {
        $parametersN['page'] = $query['page'];
    }
    if (!empty($_GET["filters-inputUsername"])) {
        $parametersN['username'] = $_GET["filters-inputUsername"];
    }
    if (!empty($_GET["filters-inputRole"]) && $_GET["filters-inputRole"] != "Все"){
        $parametersN['role'] = $_GET["filters-inputRole"];
    }
    if (!empty($_GET["filters-inputDate"])){
        $parametersN['date'] = $_GET["filters-inputDate"];
    }
    $request_url = ((strpos($_SERVER['HTTP_REFERER'], "?") != false) ? substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], "?")) : $_SERVER['HTTP_REFERER']).((count($parametersN) > 0) ? "?".http_build_query($parametersN) : "") ;
    // echo '<pre>';
    // var_dump($_SERVER);
    // var_dump($request_url);
    // echo '</pre>';
    header("Location: $request_url");
    exit();
?>