<?php

if (isset($_POST['saveParsedData']) && $_POST['saveParsedData'] == 'save') {
    if (isset($_POST['parsedData']) && is_array($_POST['parsedData']) && count($_POST['parsedData']) > 0) {
        $parsedData = $_POST['parsedData'];
        include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Fond.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Fonds.php';
        $fonds = new Fonds();
        $fonds->analizeParsedFondsData($parsedData);
        die(var_dump($parsedData));
    } else {
        die('Пришли кривые данные. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3001');
    }
}


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

