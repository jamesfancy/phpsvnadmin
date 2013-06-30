<?php

session_start();
require_once dirname(__FILE__) . '/conf.inc.php';

$db = $config->options['database'];
require_once dirname(__FILE__) . "/../db/$db.php";

function getParam($name, $default) {
    if (isset($_REQUEST[$name])) {
        return $_REQUEST[$name];
    } else {
        return $default;
    }
}

function getParamObject() {
    $names = func_get_args();
    if (is_array($names[0])) {
        $names = $names[0];
    }
    
    $obj = new stdClass();
    foreach ($names as $n) {
        $obj->$n = getParam($n, null);
    }
    return $obj;
}

function redirect($url) {
    header('Location:' . $url);
    exit();
}

function pageHeader($isLoading = false) {
    $dir = dirname(__FILE__);
    include $dir . "/header.inc.php";
    if ($isLoading) {
        include $dir . "/loading.inc.php";
    }
}

function currentUser() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

function putJson($varName, $obj) {
    if (!preg_match('/\./', $varName)) {
        $varName = 'var '. $varName;
    }

    $json = json_encode(json_encode($obj));
    echo <<<JS
        $varName = (function() {
            var t = $json;
            return $.parseJSON(t);
        })(jQuery);

JS;
}
