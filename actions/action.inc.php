<?php

require_once '../inc/common.inc.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($keyName)) {
    $keyName = 'id';
}

function noMethod($method) {
    throw new BadMethodCallException("not defined $method", -1);
}

function handle() {
    global $keyName;
    global $method;
    global $key;

    $method = strtolower(trim(getParam('method', 'query')));
    $key = getParam($keyName, null);

    switch ($method) {
        case 'query':
            if ($key == null) {
                $page = intval(getParam('page', 0));
                return onQueryList($page);
            } else {
                return onQuery($key);
            }
        case 'create':
            return onCreate();
        case 'update':
            if ($key == null) {
                return null;    // TODO 应该抛出异常
            }
            return onUpdate($key);
        case 'delete':
            if ($key == null) {
                return null;    // TODO 应该抛出异常
            }
            return onDelete($key);
        default:
            if (function_exists('onMethod')) {
                return onMethod($method);
            } else {
                noMethod($method);
            }
    }
}

$result = new stdClass();
try {
    $result->data = handle();
    $result->code = $result->data == null ? -1 : 0;
    $result->message = '';
} catch (Exception $e) {
    $result->code = $e->getCode();
    $result->message = $e->getMessage();
}
echo json_encode($result);