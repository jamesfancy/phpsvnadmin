<?php

class SvnAdminConfig {

    var $configs = [
        'options' => 'options.php',
        'mailer' => 'mailer.php'
    ];

    function addOption($name, $options) {
        $this->$name = $options;
    }

    function import($name) {
        if (!array_key_exists($name, $this->configs)) {
            return;
        }

        $filename = $this->configs[$name];
        $filename = dirname(dirname(__FILE__)) . '/conf/' . $filename;
        $debugFilename = $filename . '.debug';

        if (is_readable($debugFilename)) {
            $filename = $debugFilename;
        } else if (!is_readable($filename)) {
            return;
        }

        global $config;
        include_once( $filename);
    }

}

$config = new SvnAdminConfig();
$config->import('options');

if (isset($config->options['appRoot'])) {
    $appRoot = $config->options['appRoot'];
    if (!$appRoot) {
        $appRoot = '/';
    } else if (!preg_match('/\\/$/', $appRoot)) {
        $appRoot = $appRoot . '/';
    }
} else {
    $appRoot = '/';
}
