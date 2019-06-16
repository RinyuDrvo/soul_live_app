<?php
//データベース設定
$dbHeroku = parse_url($_SERVER['CLEARDB_DATABASE_URL']);
$dbHeroku['dbname'] = ltrim($dbHeroku['path'],'/');

$dbServer = $dbHeroku['host'];
$dbUser = $dbHeroku['user'];
$dbPass = $dbHeroku['pass'];
$dbName = $dbHeroku['dbname'];
?>