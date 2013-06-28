<?php
    require_once dirname(__FILE__) . '/conf.inc.php';
    $root = $config->options['appRoot'];
    echo "<link type=\"text/css\" rel=\"stylesheet/less\" href=\"${root}style/def.less\" />";
    echo "<script type=\"text/javascript\" src=\"${root}js/less-1.3.3.min.js\"></script>";
