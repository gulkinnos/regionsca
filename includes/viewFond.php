<head>
    <meta charset="utf-8">
</head>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/App.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/checkAuth.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Fond.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Fonds.php';
if (isset($_GET['fondID']) && is_numeric($_GET['fondID'])) {
    $fondID = (integer) $_GET['fondID'];
} else {
    die('Не удалось получить данные по фонду.<br><a href="/">Перейти на страницу импорта</a>');
}

$fondData = Fond::getFondArrayById($fondID);
if (!is_array($fondData) || count($fondData) < 1) {
    die('Не удалось получить данные по фонду.<br><a href="/">Перейти на страницу импорта</a>');
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/fond/fondView.php';
die();

