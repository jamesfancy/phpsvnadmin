<?php
    require_once dirname(__FILE__) . '/conf.inc.php';
    $root = $config->options['appRoot'];
    echo "<script type=\"text/javascript\" src=\"${root}js/jquery-1.10.1.min.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"${root}js/app.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"${root}js/app/index.js\"></script>";
