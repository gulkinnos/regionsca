<?php
$fondID = (isset($fondData['id']) && is_numeric($fondData['id'])) ? (integer) $fondData['id'] : 0;
if (isset($fondData['regNumber'], $fondData['name'], $fondData['dateOfCreate'], $fondData['enabled'], $fondData['fondDates'])) {
    ?>
<a href="/includes/viewFond.php?fondID=<?= $fondData['id'] ?>">Только просмотр</a>
<a href="/">На главную</a>
<a href="/includes/viewAllFonds.php">Все фонды</a>
    <p>Страница редактирования фонда</p>  
    <form>
    </form>    
    <table>
        <tr>
            <td>
                Регистрационный номер 
            </td>
            <td colspan="2">
                <?= $fondData['regNumber'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Название фонда 
            </td>
            <td colspan="2">
                <?= $fondData['name'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Дата заведения в БД 
            </td>
            <td colspan="2">
                <?= $fondData['dateOfCreate'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Активен
            </td>
            <td colspan="2">
                <?= ($fondData['enabled'] == 1) ? 'да' : 'нет' ?>
            </td>
        </tr>
        <?php
        if (!empty($fondData['fondDates'])) {
            ?>
            <tr>
                <th>дата</th>
                <th>СЧА</th>
                <th>изменить</th>
            </tr>
            <?php
            foreach ($fondData['fondDates'] as $date => $dateData) {
                ?>
                <tr>
                <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                    <input type="hidden" name="fondID" value="<?= $fondID ?>"/>
                    <td><?= $dateData['fd_date'] ?></td>
                    <input type="hidden" name="fd_id" value="<?= $dateData['fd_id'] ?>"/>
                    <td><input name="fd_sca" value="<?= $dateData['fd_sca'] ?>"/></td>
                    <td><input type="submit" name="updateSCA" value="Перезаписать"></td>
                </form>
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

