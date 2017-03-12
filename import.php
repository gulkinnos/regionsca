<head>
    <meta charset="utf-8">

</head>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/App.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/checkAuth.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/XtddParser.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Fond.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Fonds.php';
$xtddParser = new XtddParser();
$filename = $_FILES['xmlfile']['tmp_name'];
$preview = $xtddParser->parseXTDD($filename);

if (!is_array($preview)) {
    die('Не удалось разобрать файл. Возможно он пуст');
}
if (count($preview) < 1) {
    die('Не удалось разобрать файл. Возможно он пуст');
}

$xtddParser->printParsedPreview($preview);
