<?php

ini_set('memory_limit', '800M');

require_once __DIR__ . '/vendor/autoload.php';

$options = [
    'username' => '',
    'password' => '',
    'dbname' => '',
    'table_prefix' => '',
    'host' => 'localhost',
    'dry' => true,
    'log_queries' => true,
];

$fixer = new \Vb5UrlFixer\Fixer(
    $options['username'],
    $options['password'],
    $options['dbname'],
    $options['table_prefix'],
    $options['host'],
    $options['dry'],
    $options['log_queries']
);
$fixer->fix();
