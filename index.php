<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="resources/css/main.css" >
</head>
<h2>Регион. Загрузка СЧА из .xtdd</h2>


<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/checkAuth.php';
?>
<div><a href="/includes/viewAllFonds.php" target="_blank">Просмотр фондов</a></div>
<p>Выберите файл и нажмите кнопку "Проверить XTDD"</p>
<form name="xmlForm" action="import.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="xmlfile" /> <br> <br>
    <input type="submit" value="Проверить XTDD" name="xmlsubmit" />
</form>
