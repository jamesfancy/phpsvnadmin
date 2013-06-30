<?php
require_once 'inc/common.inc.php';
?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>check system options</title>
        <style type="text/css">
            h1 {
                font-family: "Lucida Grande", Tahoma, sans-serif;
                font-size: 24px;
                font-weight: normal;
            }
            table#checkResult {
                width: 100%;
                table-layout: fixed;
                border-collapse: collapse;
                font-size: 14px;
                font-family: "Lucida Grande", Tahoma, sans-serif;
            }
            tr {
                height: 24px;
                line-height: 24px;
            }
            th, td {
                padding-left: 5px;
                padding-right: 5px;
                border: 1px solid #666;
            }
            tr.header {
                background: #67B021;
                text-align: left;
                color: white;
            }
            tr.ok {
                background: #DCF3CA;
                color: #312E25;
            }
            tr.err {
                background: #FFE0E0;
                color: #f00;
            }
        </style>
    </head>
    <body>
        <h1>Check System Options</h1>
        <table id="checkResult">
            <col width="160" />
            <col />
            <col width="240" />
            <tr class="header">
                <th>ITEM</th>
                <th>DESC</th>
                <th>RESULT</th>
            </tr>
        </table>
    </body>
    <script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript">
<?php

class CheckInfo {

    private $config;
    private $options;

    public function __construct($config) {
        $this->config = $config;
        $this->options = $config->options;
    }

    public function check() {
        $this->checkHtpasswd();
        $this->checkDatabase();
    }
    
    private function checkDatabase() {
        $this->database = [
            "Database driver",
            true,
            $this->options['database']
        ];
    }

    private function checkHtpasswd() {
        $this->checkFile('htpasswd', $this->options['htpasswd'], 'is_executable');

        $authUser = $this->options['authUserFile'];
        if (!$this->checkFile('auth_user_file', $authUser, 'is_writable') && !empty($authUser)) {
            $file = fopen($authUser, 'a');
            if ($file !== false) {
                fclose($file);
                $this->checkFile('auth_user_file', $authUser, 'is_writable');
            }
        }
    }

    private function checkFile($key, $file, $checker = 'file_exists') {
        $r = !empty($file) && $checker($file);
        $this->$key = [$file, $r, "$checker=" . ($r ? 'true' : 'false')];
        return $r;
    }
}

$info = new CheckInfo($config);

$info->check();
putJson('systemInfo', $info);
?>

        $(function() {
            var table = $("#checkResult");
            for (var key in systemInfo) {
                var info = systemInfo[key];
                if (!$.isArray(info) || info.length < 2) {
                    continue;
                }

                var tr = $("<tr>").addClass(info[1] ? "ok" : "err");
                $("<td>").text(key).appendTo(tr);
                $("<td>").text(info[0]).appendTo(tr);
                $("<td>").text(info[2] || "").appendTo(tr);

                tr.appendTo(table);
            }
        });
    </script>
</html>
