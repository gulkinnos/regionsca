<head>
    <meta charset="UTF-8">

</head>
<?php
//получить имя файла

$filename = $_FILES['xmlfile']['tmp_name'];

//получить содержимое файла 

$content = file_get_contents($filename);
//добавить проверку на не пустоту контента

$notValid1 = 'xmlns:av="http://www.it.ru/Schemas/Avior/УРКИ"';
$content = str_replace($notValid1, '', $content);
$notValid2 = '/av:/';
$content = preg_replace($notValid2, '', $content);

//создаем объект, используя готовую библиотеку simlexml

$xml = simplexml_load_string($content);

//
$fonds = $xml->КоллекцияОтчетностьРКИОтчет070->ОтчетностьРКИОтчет070->ОтчетностьРКИОтчет070_1;

if (!is_object($fonds) || count($fonds) < 1) {
    die('Нам не удалось разобрать файл корректно, в блоке ОтчетностьРКИОтчет070_1 нет вложенных элементов');
}

//$config = getConfig();
//$dbConnect = getDBlink($config);
require_once './App.php';

//$allFonds = getAllFondsData();
$allFondsCollectedByRegNumber = getAllFondsDataCollectedByRegNumber();





echo '<pre>';
//var_dump($allFondsCollectedByRegNumber);
//var_dump($allFonds['1']['name']);
//die(var_dump(mb_detect_encoding($allFonds['1']['name'])));



foreach ($fonds as $fond_data) {
    $regNumber = (string) trim(strip_tags($fond_data->РегНомерПиф));
    if (count($allFondsCollectedByRegNumber) < 1 || !isset($allFondsCollectedByRegNumber[$regNumber])) {
        $addedNewFond = addNewFond($fond_data);
        if ($addedNewFond) {
            echo '<p>Добавлен новый фонд, так как не нашёлся в базе данных.</p>';
            printMyObject($fond_data);
        }
    }



//   var_dump($fond_data);  
}


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

        $dbr = mysqli_query($dbConnect, $sql);

        echo $sql;
        var_dump($dbr);
    }



    /*
      INSERT INTO `fonds` (`id`, `regNumber`, `name`, `date`, `sca`, `enabled`)
     *  VALUES (NULL, '1111-2222-333', 'тестовое ', '2017-02-20', '9000000000.99', '1');
     */
    echo '<pre>';
    var_dump($regNumber, $name, $sca);



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

/** Получает все фонды из базы данных. Собирает их по регистрационному номеру
 *  в качестве ключа.
 * Нужен, чтобы потом проверять иссетом каждый распарсенный фонд.
 *  
 * Говнометод. Переделать на вменяемый 
 * Возвращает массив со всеми данными по всем фондам с группировкой по 
 * регистрационному номеру фондов.
 * 
 * @param mysqli $dbConnect - соединение с базой данных
 * @return array
 */
function getAllFondsDataCollectedByRegNumber() {
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
                        `regNumber` ASC 
                    LIMIT 10000;                  
            ';
    }
    $dbResult = mysqli_query($dbConnect, $sql);
    if ($dbResult) {
        while ($row = mysqli_fetch_assoc($dbResult)) {
            $result[$row['regNumber']] = $row;
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
