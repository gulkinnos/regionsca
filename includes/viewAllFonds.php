<head>
    <meta charset="utf-8">
</head>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/App.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/checkAuth.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Fond.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Fonds.php';
$fonds = new Fonds();
$uploadedFonds = $fonds->getAllFondsAndDatesCollRegNum();
if (!is_array($uploadedFonds) || count($uploadedFonds) < 1) {
    die('Не нашлось сохраненных фондов.<br><a href="/">Перейти на страницу импорта</a>');
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/fonds/viewAllFonds.php';
die();

