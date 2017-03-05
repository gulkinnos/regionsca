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


die();
//die(var_dump($preview));
//$config = getConfig();
//$dbConnect = getDBlink($config);
require_once './App.php';

//$allFonds = getAllFondsData();
$allFondsCollectedByRegNumber = getAllFondsDataCollectedByRegNumber();





echo '<pre>';
//var_dump($allFondsCollectedByRegNumber);
//var_dump($allFonds['1']['name']);
//die(var_dump(mb_detect_encoding($allFonds['1']['name'])));






die('aga');

function addNewFond($fond_data) {
    $result = false;
    if (!is_object($fond_data) || count($fond_data) < 1) {
        return $result;
    }

    if (!isset($fond_data->РегНомерПиф)) {
        return $result;
    }
    $regNumber = trim(strip_tags($fond_data->РегНомерПиф));

    if (!isset($fond_data->ПаевойИнвестиционныйФонд)) {
        return $result;
    }
    $name = trim(strip_tags($fond_data->ПаевойИнвестиционныйФонд));

    if (!isset($fond_data->СЧА)) {
        return $result;
    }
    $sca = trim(strip_tags($fond_data->СЧА));
    if (!isset($fond_data->Дата)) {
        return $result;
    }
    $date = trim(strip_tags($fond_data->Дата));

    $App = new App();
    $dbConnect = $App->getDB();

    if (!mysqli_errno($dbConnect)) {

        $sql = 'INSERT INTO
            `fonds`
                (`regNumber`,
                `name`,
                `date`,
                `sca`,
                `enabled`)
            VALUES (
            "' . $dbConnect->real_escape_string($regNumber) . '",
                "' . $dbConnect->real_escape_string($name) . '",
                "' . $dbConnect->real_escape_string($date) . '",
                "' . $dbConnect->real_escape_string($sca) . '",
                1);';
        var_dump($sql);

        $dbr = $dbConnect->query($sql);

//        echo $sql;
//        die();
    }



    /*
      INSERT INTO `fonds` (`id`, `regNumber`, `name`, `date`, `sca`, `enabled`)
     *  VALUES (NULL, '1111-2222-333', 'тестовое ', '2017-02-20', '9000000000.99', '1');
     */
    echo '<pre>';
//    var_dump($regNumber, $name, $sca);



    return $result;
}

function printMyObject($fond_data) {
    echo 'Рег. номер: ' . $fond_data->РегНомерПиф . '<br>';
    echo 'Название: ' . $fond_data->ПаевойИнвестиционныйФонд . '<br>';
    echo 'Дата: ' . $fond_data->Дата . '<br>';
    echo 'СЧА: ' . $fond_data->СЧА . '<br>';
    echo 'СЧА изменения: ' . $fond_data->СЧАИзменение . '<br>';

    echo '<hr>';
}

/** Получает все фонды из базы данных.
 * Говнометод. Переделать на вменяемый 
 * Возвращает массив со всеми данными по всем фондам с группировкой по ID фондов.
 * 
 * @param mysqli $dbConnect - соединение с базой данных
 * @return array
 */
function getAllFondsData() {
    $result = [];
    $App = new App();
    $dbConnect = $App->getDB();
    if (!mysqli_errno($dbConnect)) {
        $sql = '
            SELECT 
               *
                FROM
                    `fonds`
                    ORDER BY 
                        `id` ASC 
                    LIMIT 10000;                  
            ';
    }
    $dbResult = mysqli_query($dbConnect, $sql);
    if ($dbResult) {
        while ($row = mysqli_fetch_assoc($dbResult)) {
            $result[$row['id']] = $row;
        }
    }
    return $result;
}

/** Получает обший конфиг.
 * Также является говнометодотом.
 * 
 * 
 * @return array $config - массив с данными конфигурации.
 */
/*
   function getConfig() {
    $result = require_once './config.php';
    return $result;
}
 /**/

/*
 * 
 * это запрос для добавлени одного новго фонда
 * 
 *  INSERT INTO `fonds` (`id`, `regNumber`, `name`, `date`, `sca`, `enabled`)
 *  VALUES (NULL, '1111-2222-333', 'тестовое ', '2017-02-20', '9000000000.99', '1');
 */
