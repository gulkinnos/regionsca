<?php
/**
 * Отвечает за коллекции фондов.
 *
 * @author Aleksandr Golubev aka gulkinnos <gulkinnos@gmail.com>
 */
class Fonds {
    function __construct() {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/App.php';
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
        $result=[];
        if(!is_array($fondsData)||  count($fondsData)<1){
            $result['errors'][]='Нечего разбирать. Пришёл пустой массив. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3011';
            return $result;
        }
        foreach ($fondsData as $regNumber => $fondData) {
            die(var_dump($fondData));
        }
        
        return $result;
        
    }
    
    
}
