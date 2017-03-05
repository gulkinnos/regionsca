<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of App
 *
 * @author Home
 */
class App {

    public $config = array();
    public $db = null;

    function __construct() {
        header('Content-Type: text/html; charset=utf-8');
    }

    public function getConfig() {
        if (empty($this->config)) {
            include $_SERVER['DOCUMENT_ROOT'] . '/config.php';
            $this->config =  $config;
        }
        return $this->config;
    }

    /** Подключается к базе данных.
     * Принимает массив с конфигом в качестве параметров.
     * Возвращает link на подклюение к базе данных.
     * @todo Заменить на нормальный метод, добавить проверок, что подключения ещё нет, что нет ошибок, что пришёл нормальный конфиг.
     * 
     * @param array $configArray -конфиг целиком.
     */
    function getDB() {
        $this->getConfig();
        $dbLink = new mysqli($this->config['db']['host'],
                $this->config['db']['user'],
                $this->config['db']['password'],
                $this->config['db']['database']);
        return $dbLink;
    }

}
