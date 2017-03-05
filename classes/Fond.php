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
    public $sca = 0;
    public $enabled = 0;
    public $date = 0;
    public $fondData = array();
    public $datesSCA = array();
    public $existingFonds = array();

    function __construct() {
        
    }

    function getFondDataByID($param) {
        
    }

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
        $name=  preg_replace('/^["]+/', '', $name);
        $this->name = $name;
        
        if (!isset($fond_data->СЧА)) {
            return $result;
        }
        $this->sca = trim(strip_tags($fond_data->СЧА));

        if (!isset($fond_data->Дата)) {
            return $result;
        }
        $this->date = trim(strip_tags($fond_data->Дата));

        $fondId = $this->getFondIDByRegNumber($this->regNumber);
        $this->id = $fondId;
        $result = array(
            'id' => $this->id,
            'regNumber' => $this->regNumber,
            'name' => $this->name,
            'sca' => $this->sca,
            'date' => $this->date
        );
        return $result;
    }

    function getFondDataByRegNumber($param) {
        
    }

    function getFondIDByRegNumber($regNumber) {
        $result = 0;
        if (!is_array($this->existingFonds) || count($this->existingFonds) < 1) {
            return $result;
        }
        if (isset($this->existingFonds[$this->regNumber]['id'])) {
            $this->id = (integer)$this->existingFonds[$this->regNumber]['id'];
            return $this->id;
        }
        return $result;
    }

    static function printFondData($fondData) {

        include $_SERVER['DOCUMENT_ROOT'] . '/templates/fond/fondPreview.php';
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

    function getEnabled() {
        return $this->enabled;
    }

    function getDatesSCA() {
        return $this->datesSCA;
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

    function setEnabled($enabled) {
        $this->enabled = $enabled;
    }

    function setDatesSCA($datesSCA) {
        $this->datesSCA = $datesSCA;
    }

    function getExistingFonds() {
        return $this->existingFonds;
    }

    function setExistingFonds($existingFonds) {
        $this->existingFonds = $existingFonds;
    }

}
