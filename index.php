<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="resources/css/main.css" >
</head>
<h1>Регион</h1>


<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/checkAuth.php';
?>
<p>Выберите файл и нажмите кнопку "Проверить XTDD"</p>
<form name="xmlForm" action="import.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="xmlfile" /> <br> <br>
    <input type="submit" value="Проверить XTDD" name="xmlsubmit" />
</form>
