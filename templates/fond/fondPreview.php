<?php
$fondID = (isset($fondData['id']) && is_numeric($fondData['id'])) ? (integer) $fondData['id'] : 0;
if (isset($fondData['regNumber'], $fondData['name'], $fondData['parsedSCA'], $fondData['date'], $fondData['fondDates'])) {
    if (!empty($fondData['fondDates'])) {
    }
    ?>

    <tr class="fondRow">
    <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][fondID]" value='<?= $fondID ?>'/>
    <?php
    if (is_array($fondData['fondDates']) && count($fondData['fondDates'])) {
        foreach ($fondData['fondDates'] as $date => $datesValues) {
            if (isset($datesValues['fd_id'], $datesValues['fd_fond_id'], $datesValues['fd_date'], $datesValues['fd_sca'], $datesValues['fd_sca_change_time'])) {
                ?>
                <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][fondDates][<?= $datesValues['fd_date'] ?>][fd_date]" value='<?= $datesValues['fd_date'] ?>'/>
                <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][fondDates][<?= $datesValues['fd_date'] ?>][fd_id]" value='<?= $datesValues['fd_id'] ?>'/>
                <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][fondDates][<?= $datesValues['fd_date'] ?>][fd_fond_id]" value='<?= $datesValues['fd_fond_id'] ?>'/>
                <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][fondDates][<?= $datesValues['fd_date'] ?>][fd_sca]" value='<?= $datesValues['fd_sca'] ?>'/>
                <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][fondDates][<?= $datesValues['fd_date'] ?>][fd_sca_change_time]" value='<?= $datesValues['fd_sca_change_time'] ?>'/>
                <?php
            }
        }
    }
    ?>
    <td>
        <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][regNumber]" value='<?= $fondData['regNumber'] ?>'/>
    <?= $fondData['regNumber'] ?>
    </td>
    <td>
        <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][name]" value='<?= $fondData['name'] ?>'/>
    <?= $fondData['name'] ?>
    </td>
    <td>
        <input class="align-right" name="parsedData[<?= $fondData['regNumber'] ?>][sca]" value="<?= $fondData['parsedSCA'] ?>"/>
    </td>
    <td>
        <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][date]" value='<?= $fondData['date'] ?>'/>
    <?= $fondData['date'] ?>
    </td>
    </tr>
    <?php
} else {
    die('Не могу составить таблицу. Что-то не пришло. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 3021');
}
?>

