<?php

/**
 * Description of XtddParser
 *
 * @author Aleksandr Golubev aka gulkinnos <gulkinnos@gmail.com>
 */
class XtddParser {

    function __construct() {
        
    }

    /**
     * 
     * @param type $filename
     * @return string
     */
    function parseXTDD($filename) {
        $result = [];

        $content = file_get_contents($filename);
        $notValid1 = 'xmlns:av="http://www.it.ru/Schemas/Avior/УРКИ"';
        $content = str_replace($notValid1, '', $content);
        $notValid2 = '/av:/';
        $content = preg_replace($notValid2, '', $content);

        //создаем объект, используя готовую библиотеку simlexml
        $xml = simplexml_load_string($content);
        $fonds = $xml->КоллекцияОтчетностьРКИОтчет070->ОтчетностьРКИОтчет070->ОтчетностьРКИОтчет070_1;
        if (!is_object($fonds) || count($fonds) < 1) {
            return 'Нам не удалось разобрать файл корректно, в блоке ОтчетностьРКИОтчет070_1 нет вложенных элементов';
        }
        $fondObj = new Fond();
        $fondsObj = new Fonds();

        $existingFondAll = $fondsObj->getAllFondsDataCollectedByRegNumber();
        $fondObj->setExistingFonds($existingFondAll);

        foreach ($fonds as $fond_data) {
            $result [] = $fondObj->getFondDataArrayFromParser($fond_data);
        }
        return $result;
    }

    /**
     * 
     * @param type $fondsData
     * @return string
     */
    function printParsedPreview($fondsData) {
        $result = '';
        if (!is_array($fondsData) || !count($fondsData)) {
            return $result;
        }
        Fonds::printFondsPreview($fondsData);
        return $result;
    }

}
