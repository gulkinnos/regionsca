<?php

/**
 * Отвечает за коллекции фондов.
 *
 * @author Aleksandr Golubev aka gulkinnos <gulkinnos@gmail.com>
 */
class Fonds {

    function __construct() {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/App.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Fond.php';
    }

    public static function printFondsPreview($fondsData) {
        $result = '';
        if (!is_array($fondsData) || !count($fondsData)) {
            return $result;
        }
        include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/fonds/fondsPreview.php';
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
    public function getAllFondsDataCollectedByRegNumber() {
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

    public function analizeParsedFondsData($fondsData) {
        $result = [];
        if (!is_array($fondsData) || count($fondsData) < 1) {
            $result['errors'][] = 'Нечего разбирать. Пришёл пустой массив. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3011';
            return $result;
        }
        foreach ($fondsData as $regNumber => $fondData) {
            if (!is_array($fondData) || count($fondData) < 1) {
                // сообщить об ошибке
                continue;
            }
            if (!isset($fondData['fondID'], $fondData['regNumber'], $fondData['name'], $fondData['sca'], $fondData['date'])) {
                // сообщить об ошибке
                continue;
            }

            //проверим, что пришёл нормальный регномер.
            //@todo Вынести в метод.
            if (trim(strip_tags($fondData['regNumber'])) == '') {
                // сообщить об ошибке
                continue;
            } else {
                $fondRegNumber = trim(strip_tags($fondData['regNumber']));
            }

            //проверим, что пришло нормальное название.
            //@todo Вынести в метод.
            if (trim(strip_tags($fondData['name'])) == '') {
                // сообщить об ошибке
                continue;
            } else {
                $fondName = trim(strip_tags($fondData['name']));
            }

            //проверим, что пришла нормальная СЧА.
            //@todo Вынести в метод.
            if (trim(strip_tags($fondData['sca'])) == '') {
                // сообщить об ошибке
                continue;
            } else {
                $fondSCA = trim(strip_tags($fondData['sca']));
            }

            //проверим, что пришла нормальная дата.
            //@todo Вынести в метод.
            if (trim(strip_tags($fondData['date'])) == '') {
                // сообщить об ошибке
                continue;
            } else {
                $fondDate = trim(strip_tags($fondData['date']));
            }

            //проверим, что пришёл нормальный ID.
            //@todo Вынести в метод.
            if (!is_numeric(trim(strip_tags($fondData['fondID'])))) {
                // сообщить об ошибке
                continue;
            } else {
                $fondID = (integer) trim(strip_tags($fondData['fondID']));
            }

            $fondObj = new Fond();
            if ($fondID == 0) {
                //если фонда не нашлось на предыдущих этапах, то создаём новый
                $newFondID = $fondObj->createNewFond($fondRegNumber, $fondName, $fondSCA);
            }
//            die(var_dump($fondObj));
//            var_dump($fondID,$newFondID);

            /*
              if (!isset($fondsData['date']) || $fondsData['date'] == '') {
              //сообщить об ошибке
              continue;
              }
              $date = trim(strip_tags($fondsData['date']));

              if (!isset($fondsData['sca']) || !is_numeric($fondsData['sca'])) {
              //сообщить об ошибке
              continue;
              }





              //получим новую СЧА
              $newSCA = number_format(floatval($fondsData['sca']), 2, '.', '');

              //проверим, есть ли СЧА
              $savedSCA = $this->getSCAFromFondDatesByDate($fondData, $date);




              if ($savedSCA <= 0) {
              continue;
              }
              $fondID = isset($fondsData['fondID']) ? (integer) $fondsData['fondID'] : 0;

              if ($fondID == 0) {
              $newFondID = $fondObj->createNewFond($fondsData);
              $fondObj->createNewFondDate($newFondID, $date, $newSCA);
              continue;
              }

              if ($newSCA == $savedSCA) {
              continue;
              } else {

              $fondObj->setId($id);
              $fondObj->updateSCAbyDate($date, $newSCA);
              }




              die(var_dump($fondsData)); */
            unset($fondRegNumber, $fondName, $fondID, $fondSCA,$newFondID);
        }

        return $result;
    }

    public function getSCAFromFondDatesByDate($fondDates, $date) {
        $result = 0;
        if (!is_array($fondDates) || !count($fondDates)) {
            return $result;
        }
        $date = trim(strip_tags($date));
        if ($date == '') {
            return $result;
        }
        if (isset($fondDates[$date]['sca']) && is_numeric($fondDates[$date]['sca'])) {
            $result = number_format(floatval($fondDates[$date]['sca']), 2, '.', '');
        } else {
            return $result;
        }

        return $result;
    }

}
