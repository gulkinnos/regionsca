<?php
$fondID = (isset($fondData['id']) && is_numeric($fondData['id'])) ? (integer) $fondData['id'] : 0;
if (isset($fondData['regNumber'], $fondData['name'], $fondData['dateOfCreate'], $fondData['enabled'], $fondData['fondDates'])) {
    ?>
<a href="/includes/editFond.php?fondID=<?= $fondData['id'] ?>">Редактирование</a>
<a href="/">На главную</a>
<a href="/includes/viewAllFonds.php">Все фонды</a>
    <p>Страница просмотра фонда</p>
    <form>
    </form>    
    <table>
        <tr>
            <td>
                Регистрационный номер 
            </td>
            <td>
                <?= $fondData['regNumber'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Название фонда 
            </td>
            <td>
                <?= $fondData['name'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Дата заведения в БД 
            </td>
            <td>
                <?= $fondData['dateOfCreate'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Активен
            </td>
            <td>
                <?= ($fondData['enabled'] == 1) ? 'да' : 'нет' ?>
            </td>
        </tr>
        <?php
        if (!empty($fondData['fondDates'])) {
            ?>
            <tr>
                <th>дата</th>
                <th>СЧА</th>
            </tr>
            <?php
            foreach ($fondData['fondDates'] as $date => $dateData) {
                ?>
                <tr>
                    <td><?= $dateData['fd_date'] ?></td>
                    <td><?=  number_format(floatval($dateData['fd_sca']), 2, ',', '') ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <?php
} else {
    die('Не могу составить таблицу. Что-то не пришло. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3021');
}
?>

