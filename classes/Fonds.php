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

    /**
     * 
     * @param type $fondsData
     * @return string
     */
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
        $App = App::getInstance();
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

    /**
     * 
     * @return type
     */
    public function getAllFondsAndDatesCollRegNum() {
        $result = [];
        $App = App::getInstance();
        $fond = new Fond();
        $dbConnect = $App->getDB();
        if (!mysqli_errno($dbConnect)) {
            $sql = '
                SELECT
                    `id`,
                    `regNumber`,
                    `name`,
                    `dateOfCreate`,
                    `enabled`
                FROM
                    `fonds` 
                ORDER BY
                    `regNumber` ASC LIMIT 10000;';
        }
        $dbResult = mysqli_query($dbConnect, $sql);
        if ($dbResult) {
            while ($row = mysqli_fetch_assoc($dbResult)) {
                $result[$row['regNumber']] = $row;
                $fondID = $row['id'];
                $result[$row['regNumber']]['fondDates'] = $fond->getFondDatesByID($fondID);
            }
        }


        return $result;
    }

    /**
     * 
     * @param type $fondsData
     * @return string
     */
    public function analizeParsedFondsData($fondsData) {
        $result = [];
        $result['startTime'] = microtime(true);
        if (!is_array($fondsData) || count($fondsData) < 1) {
            $result['errors'][] = 'Нечего разбирать. Пришёл пустой массив. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3011';
            return $result;
        }
        foreach ($fondsData as $regNumber => $fondData) {
            $result['log'][$regNumber] = '';

            if (!is_array($fondData) || count($fondData) < 1) {
                // сообщить об ошибке
                $result['errors'][] = $regNumber . ' плохо разобрался. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3071';
                continue;
            }
            if (!isset($fondData['fondID'], $fondData['regNumber'], $fondData['name'], $fondData['sca'], $fondData['date'])) {
                // сообщить об ошибке
                $result['errors'][] = $regNumber . ' плохо разобрался. Не хватает какого-то из обязательных параметров. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3072';
                continue;
            }

            //проверим, что пришёл нормальный регномер.
            //@todo Вынести в метод.
            if (trim(strip_tags($fondData['regNumber'])) == '') {
                // сообщить об ошибке
                $result['errors'][] = $regNumber . ' плохо разобрался. Регистрационный номер пустой. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3073';
                continue;
            } else {
                $fondRegNumber = trim(strip_tags($fondData['regNumber']));
            }

            //проверим, что пришло нормальное название.
            //@todo Вынести в метод.
            if (trim(strip_tags($fondData['name'])) == '') {
                // сообщить об ошибке
                $result['errors'][] = $regNumber . ' плохо разобрался. Название пустое. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3074';
                continue;
            } else {
                $fondName = trim(strip_tags($fondData['name']));
            }

            //проверим, что пришла нормальная СЧА.
            //@todo Вынести в метод.
            if (trim(strip_tags($fondData['sca'])) == '') {
                // сообщить об ошибке
                $result['errors'][] = $regNumber . ' плохо разобрался. СЧА пустое. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3075';
                continue;
            } else {
                $fondSCA = number_format(floatval(trim(strip_tags($fondData['sca']))), 2, '.', '');
            }

            //проверим, что пришла нормальная дата.
            //@todo Вынести в метод.
            if (trim(strip_tags($fondData['date'])) == '') {
                // сообщить об ошибке
                $result['errors'][] = $regNumber . ' плохо разобрался. СЧА пустое. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3076';
                continue;
            } else {
                $fondDate = trim(strip_tags($fondData['date']));
            }

            //проверим, что пришёл нормальный ID.
            //@todo Вынести в метод.
            if (!is_numeric(trim(strip_tags($fondData['fondID'])))) {
                // сообщить об ошибке
                $result['errors'][] = $regNumber . ' плохо разобрался. ID фонда должно быть числом. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3077';
                continue;
            } else {
                $fondID = (integer) trim(strip_tags($fondData['fondID']));
            }
            $fondObj = new Fond();

            if ($fondID == 0) {
                $result['log'][$regNumber] .= 'Фонд: "' . $fondName . '" отсутствовал в базе данных. Попробуем создать..<br>';
                //если фонда не нашлось на предыдущих этапах, то создаём новый
                $fondID = $fondObj->createNewFond($fondRegNumber, $fondName, $fondSCA);
                if ($fondID !== false && $fondID > 0) {
                    $result['log'][$regNumber].= 'Создан успешно новый фонд с ID: ' . $fondID . '<br>';
                } else {
                    //сообщить об ошибке создания фонда.
                    $result['log'][$regNumber].= 'Создание фонда "' . $fondName . '" провалилось. Слушайтесь Инночку и сообщите разработчику. Код ошибки: 3051.<br>';
                    continue;
                }
            }
            $fondObj->loadFondById($fondID);
            //ищем дату и проверяем значение на дату
            if (isset($fondObj->fondDates[$fondDate]['fd_sca']) && is_numeric($fondObj->fondDates[$fondDate]['fd_sca'])) {
                $oldSCA = number_format(floatval($fondObj->fondDates[$fondDate]['fd_sca']), 2, '.', '');
                $result['log'][$regNumber].= 'У фонда уже есть запись об СЧА= ' . $oldSCA . ' на ' . $fondDate . '.<br>Сравним с новой СЧА =' . $fondSCA . '..<br>';
                $result['log'][$regNumber].= $oldSCA . ' vs ' . $fondSCA . '<br>';
                if ($fondSCA != $oldSCA) {
                    $dateUpdateRes = $fondObj->updateFondDateSca($fondID, $fondDate, $fondSCA);
                    if ($dateUpdateRes !== false && $dateUpdateRes > 0) {
                        $result['log'][$regNumber].= 'Обновлена успешно запись об СЧА ' . $fondSCA . ' на ' . $fondDate . '.<br>';
                    } else {
                        //сообщить об ошибке создания фонда.
                        $result['log'][$regNumber].= 'Обновление записи об СЧА ' . $fondSCA . ' на ' . $fondDate . ' провалилось. Слушайтесь Инночку и сообщите разработчику. Код ошибки: 3053.<br>';
                        continue;
                    }
                } else {
                    $result['log'][$regNumber].= '...все норм. Ничего не делаем<br>';
                }
            } else {
                //создаем значение на дату
                $result['log'][$regNumber].= 'У фонда еще нет значений СЧА на дату ' . $fondDate . ' Попробуем создать..<br>';
                $dateCreateID = $fondObj->createFondDateSca($fondID, $fondDate, $fondSCA);
                if ($dateCreateID !== false && $dateCreateID > 0) {
                    $result['log'][$regNumber].= 'Создана успешно запись об СЧА ' . $fondSCA . ' на ' . $fondDate . '.<br>';
                } else {
                    //сообщить об ошибке создания фонда.
                    $result['log'][$regNumber].= 'Создание записи об СЧА ' . $fondSCA . ' на ' . $fondDate . ' провалилось. Слушайтесь Инночку и сообщите разработчику. Код ошибки: 3052.<br>';
                    continue;
                }
            }
            $result['log'][$regNumber] .= '<hr>';
            unset($fondRegNumber, $fondName, $fondID, $fondSCA, $newFondID, $fondDate, $oldSCA, $dateCreateID, $dateUpdateRes);
        }
        $result['endTime'] = microtime(true);
        $result['timeNeed'] = $result['endTime'] - $result['startTime'];
        foreach ($result['log'] as $regNum => $value) {
            echo $value;
        }
        return $result;
    }

    /**
     * 
     * @param type $fondDates
     * @param type $date
     * @return int
     */
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
