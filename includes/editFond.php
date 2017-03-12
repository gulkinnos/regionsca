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
if (isset($_POST['updateSCA'])) {
    if (isset($_POST['fondID']) && is_numeric($_POST['fondID'])) {
        $updFondID = (integer) $_POST['fondID'];
    } else {
        echo '<p>Не могу обновить. Некорретный ID фонда</p>';
    }
    if (isset($_POST['fd_id']) && is_numeric($_POST['fd_id'])) {
        $updScaID = (integer) $_POST['fd_id'];
    } else {
        echo '<p>Не могу обновить. Некорретный ID СЧА</p>';
    }
    if (isset($_POST['fd_sca']) && is_numeric($_POST['fd_sca'])) {
        $updSca = number_format(floatval($_POST['fd_sca']), 2, '.', '');
    } else {
        echo '<p>Не могу обновить. Некорретная СЧА</p>';
    }
    if ($updFondID > 0 && $updScaID > 0 && $updSca > 0) {
        $updResult = Fond::updateFondScaById($updScaID, $updSca, $updFondID);
    }
    if ($updResult !== false) {
        if ($updResult > 0) {
            echo '<p>Сохранено успешно.</p>';
        }  else {
            echo '<p>Ничего не изменилось.</p>';
            
        }
       
    }
}

$fondData = Fond::getFondArrayById($fondID);
if (!is_array($fondData) || count($fondData) < 1) {
    die('Не удалось получить данные по фонду.<br><a href="/">Перейти на страницу импорта</a>');
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/fond/fondEdit.php';
die();

