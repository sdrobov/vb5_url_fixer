<?php

require_once __DIR__ . '/vendor/autoload.php';

$options = [
    'username' => '',
    'password' => '',
    'dbname' => '',
    'table_prefix' => '',
    'host' => 'localhost',
    'dry' => true,
];

$fixer = new \Vb5UrlFixer\Fixer(
    $options['username'],
    $options['password'],
    $options['dbname'],
    $options['table_prefix'],
    $options['host'],
    $options['dry']
);
$fixer->fix();
