<?php
$db = new PDO('mysql:host=localhost;dbname={databasename};charset=utf8', '{username}', '{password}');
include $_SERVER['DOCUMENT_ROOT'] . '/lib/phpmailer/class.phpmailer.php';
include  $_SERVER['DOCUMENT_ROOT'] . "/lib/func.php";
$currentKey = (int)$_COOKIE["key"];
if ($currentKey > 0)
    $user = p2a("SELECT * FROM users WHERE user_key=" . $currentKey, $db)[0];
?>