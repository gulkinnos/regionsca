<?php

/**
 * Класс отвечает за сущность "Фонд".
 * Хранит свойства объекта Fond и управляет ими.
 * Получает дополнительную информацию по фонду.
 * Проверяет корректность данных и логики.
 *
 * @author Aleksandr Golubev aka gulkinnos <gulkinnos@gmail.com>
 */
class Fond {

    public $id = null;
    public $name = null;
    public $regNumber = null;
    public $parsedSCA = 0;
    public $enabled = 0;
    public $date = 0;
    public $fondData = array();
    public $fondDates = array();
    public $datesSCA = array();
    public $existingFonds = array();

    function __construct() {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/App.php';
    }

    /**
     * 
     * @param type $regNumber
     * @param type $name
     * @param type $sca
     * @return boolean
     */
    function createNewFond($regNumber, $name, $sca) {
        $result = false;

        if (trim(strip_tags($regNumber)) == '') {
            return $result;
        }
        $regNumber = trim(strip_tags($regNumber));

        if (trim(strip_tags($name)) == '') {
            return $result;
        }
        $name = trim(strip_tags($name));

        $sca = trim(strip_tags($sca));
        if (trim(strip_tags($sca)) == '') {
            return $result;
        }
        $sca = trim(strip_tags($sca));

        $App = App::getInstance();
        $dbConnect = $App->getDB();

        if (!mysqli_errno($dbConnect)) {
            $sql = 'INSERT INTO
                        `fonds`
                            (`regNumber`,
                            `name`,
                            `dateOfCreate`,
                            `enabled`)
                        VALUES (
                            "' . $dbConnect->real_escape_string($regNumber) . '",
                            "' . $dbConnect->real_escape_string($name) . '",
                            CURRENT_TIMESTAMP,
                            1);';

            $dbr = $dbConnect->query($sql);
            $result = $dbConnect->insert_id;
            if ($result > 0) {
                $this->id = $result;
                $this->regNumber = $regNumber;
                $this->name = $name;
                $this->fondData = array();
                $this->fondDates = array();
            }
        }
        return $result;
    }

    /**
     * 
     * @param type $fondId
     * @return array
     */
    static function getFondDatesByID($fondId) {
        $result = [];
        if (!is_numeric($fondId) || $fondId < 1) {
            $fondId = (integer) $fondId;
        }
        if ($fondId == 0) {
            return $result;
        }
        $App = App::getInstance();
        $dbConnect = $App->getDB();
        if (!mysqli_errno($dbConnect)) {
            $sql = '
                    SELECT 
                        `fd_id`,
                        `fd_fond_id`,
                        `fd_date`,
                        `fd_sca`,
                        `fd_sca_change_time`
                    FROM 
                        `fonds_dates`
                    WHERE `fd_fond_id`=' . $fondId . '

                    LIMIT 10000;';
        }
        $dbResult = mysqli_query($dbConnect, $sql);
        if ($dbResult) {
            while ($row = mysqli_fetch_assoc($dbResult)) {
                $result[$row['fd_date']] = $row;
            }
        }
        return $result;
    }

    /**
     * 
     * @param type $fondID
     * @return \Fond
     */
    function loadFondById($fondID) {
        $result = null;
        if (!is_numeric($fondID) || $fondID < 1) {
            return $result;
        }
        if ($fondID == 0) {
            $fondID = (integer) $fondID;
        }
        $App = App::getInstance();
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
                    WHERE `id`=' . $fondID . '
                    LIMIT 1;';
        }
        $dbResult = mysqli_query($dbConnect, $sql);
        if ($dbResult) {
            $row = mysqli_fetch_assoc($dbResult);
            $this->id = $row['id'];
            $this->regNumber = $row['regNumber'];
            $this->name = $row['name'];
            $this->date = $row['dateOfCreate'];
            $this->enabled = $row['enabled'];
            $this->fondDates = $this->getFondDatesByID($fondID);
        }
        return $this;
    }

    /**
     * 
     * @param type $fond_data
     * @return array
     */
    function getFondDataArrayFromParser($fond_data) {
        $result = array();

        if (!is_object($fond_data) || count($fond_data) < 1) {
            return $result;
        }

        if (!isset($fond_data->РегНомерПиф)) {
            return $result;
        }
        $this->regNumber = trim(strip_tags($fond_data->РегНомерПиф));

        if (!isset($fond_data->ПаевойИнвестиционныйФонд)) {
            return $result;
        }
        $name = trim(strip_tags($fond_data->ПаевойИнвестиционныйФонд));
        $name = preg_replace('/^["]+/', '', $name);
        $this->name = $name;

        if (!isset($fond_data->СЧА)) {
            return $result;
        }
        $parsedSCA = trim(strip_tags($fond_data->СЧА));
        if (is_numeric($parsedSCA)) {
            $parsedSCA = number_format(floatval($parsedSCA), 2, '.', '');
        } else {
            return $result;
        }
        $this->parsedSCA = $parsedSCA;
        if (!isset($fond_data->Дата)) {
            return $result;
        }
        $this->date = trim(strip_tags($fond_data->Дата));

        $fondId = $this->getFondIDByRegNumber($this->regNumber);
        $this->id = $fondId;
        $this->fondDates = $this->getFondDatesByID($this->id);
        $result = array(
            'id' => $this->id,
            'regNumber' => $this->regNumber,
            'name' => $this->name,
            'parsedSCA' => $this->parsedSCA,
            'date' => $this->date,
            'fondDates' => $this->fondDates
        );
        return $result;
    }

    /**
     * 
     * @param type $fondID
     * @return type
     */
    public static function getFondArrayById($fondID) {
        $result = null;
        if (!is_numeric($fondID) || $fondID < 1) {
            return $result;
        }
        if ($fondID == 0) {
            $fondID = (integer) $fondID;
        }
        $App = App::getInstance();
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
                    WHERE `id`=' . $fondID . '
                    LIMIT 1;';
        }
        $dbResult = mysqli_query($dbConnect, $sql);
        if ($dbResult) {
            $row = mysqli_fetch_assoc($dbResult);
            $result = $row;
            if (count($row) > 0) {
                $result['fondDates'] = Fond::getFondDatesByID($fondID);
            }
        }
        return $result;
    }

    /**
     * 
     * @param type $fondID
     * @param type $date
     * @param type $sca
     * @return boolean
     */
    public function createFondDateSca($fondID, $date, $sca) {
        $result = false;
        if (!is_numeric($fondID) || $fondID < 1) {
            $fondID = (integer) $fondID;
        }
        if ($fondID == 0) {
            return $result;
        }
        if (trim(strip_tags($date)) == '') {
            return $result;
        }
        $date = trim(strip_tags($date));

        if (!is_numeric($sca)) {
            return $result;
        }
        $sca = number_format(floatval($sca), 2, '.', '');
        $App = App::getInstance();
        $dbConnect = $App->getDB();
        if (!mysqli_errno($dbConnect)) {
            $sql = 'INSERT INTO
                        `fonds_dates`
                            (`fd_fond_id`,
                            `fd_date`,
                            `fd_sca`,
                            `fd_sca_change_time`)
                        VALUES (
                            "' . $fondID . '",
                            "' . $dbConnect->real_escape_string($date) . '",
                            "' . $sca . '",
                            CURRENT_TIMESTAMP);';
            $dbr = $dbConnect->query($sql);
            $result = $dbConnect->insert_id;
        }
        return $result;
    }

    /**
     * 
     * @param type $fondID
     * @param type $date
     * @param type $sca
     * @return boolean
     */
    public function updateFondDateSca($fondID, $date, $sca) {
        $result = false;
        if (!is_numeric($fondID) || $fondID < 1) {
            $fondID = (integer) $fondID;
        }
        if ($fondID == 0) {
            return $result;
        }
        if (trim(strip_tags($date)) == '') {
            return $result;
        }
        $date = trim(strip_tags($date));

        if (!is_numeric($sca)) {
            return $result;
        }
        $sca = number_format(floatval($sca), 2, '.', '');
        $App = App::getInstance();
        $dbConnect = $App->getDB();
        if (!mysqli_errno($dbConnect)) {
            $sql = 'UPDATE
                        `fonds_dates`
                        SET
                            `fd_sca`=' . $sca . ',
                            `fd_sca_change_time`=CURRENT_TIMESTAMP
                        WHERE
                            `fd_fond_id`= ' . $fondID . '
                                AND
                            `fd_date`= "' . $dbConnect->real_escape_string($date) . '"
                                LIMIT 1;';
            $dbr = $dbConnect->query($sql);
            if ($dbConnect->affected_rows) {
                $result = $dbConnect->affected_rows;
            }
        }
        return $result;
    }

    /**
     * 
     * @param type $scaID
     * @param type $sca
     * @param type $fondID
     * @return boolean
     */
    public static function updateFondScaById($scaID, $sca, $fondID) {
        $result = false;
        if (!is_numeric($fondID) || $fondID < 1) {
            $fondID = (integer) $fondID;
        }
        if ($fondID == 0) {
            return $result;
        }
        if (!is_numeric($scaID) || $scaID < 1) {
            $scaID = (integer) $scaID;
        }
        if ($scaID == 0) {
            return $result;
        }
        if (!is_numeric($sca)) {
            return $result;
        }
        $sca = number_format(floatval($sca), 2, '.', '');
        $App = App::getInstance();
        $dbConnect = $App->getDB();
        if (!mysqli_errno($dbConnect)) {
            $sql = 'UPDATE
                        `fonds_dates`
                        SET
                            `fd_sca`=' . $sca
                    /* . ',
                      `fd_sca_change_time`=CURRENT_TIMESTAMP */
                    . 'WHERE
                            `fd_id`= "' . $scaID . '"
                                AND
                            `fd_fond_id`= ' . $fondID . '
                        LIMIT 1;';
            $dbr = $dbConnect->query($sql);
            $result = $dbConnect->affected_rows;
        }
        return $result;
    }

    /**
     * 
     * @param type $regNumber
     * @return int
     */
    function getFondIDByRegNumber($regNumber) {
        $result = 0;
        if (!is_array($this->existingFonds) || count($this->existingFonds) < 1) {
            return $result;
        }
        if (isset($this->existingFonds[$this->regNumber]['id'])) {
            $this->id = (integer) $this->existingFonds[$this->regNumber]['id'];
            return $this->id;
        }
        return $result;
    }

    /**
     * 
     * @param type $fondData
     * @return type
     */
    static function printFondData($fondData) {
        include $_SERVER['DOCUMENT_ROOT'] . ' / templates / fond / fondPreview . php';
        return;
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getRegNumber() {
        return $this->regNumber;
    }

    function getParsedSCA() {
        return $this->parsedSCA;
    }

    function getEnabled() {
        return $this->enabled;
    }

    function getDate() {
        return $this->date;
    }

    function getFondData() {
        return $this->fondData;
    }

    function getFondDates() {
        return $this->fondDates;
    }

    function getDatesSCA() {
        return $this->datesSCA;
    }

    function getExistingFonds() {
        return $this->existingFonds;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setRegNumber($regNumber) {
        $this->regNumber = $regNumber;
    }

    function setParsedSCA($parsedSCA) {
        $this->parsedSCA = $parsedSCA;
    }

    function setEnabled($enabled) {
        $this->enabled = $enabled;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function setFondData($fondData) {
        $this->fondData = $fondData;
    }

    function setFondDates($fondDates) {
        $this->fondDates = $fondDates;
    }

    function setDatesSCA($datesSCA) {
        $this->datesSCA = $datesSCA;
    }

    function setExistingFonds($existingFonds) {
        $this->existingFonds = $existingFonds;
    }

}
