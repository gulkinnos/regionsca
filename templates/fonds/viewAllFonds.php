<?php
if (is_array($uploadedFonds) && count($uploadedFonds)) {
    ?>
    <a href="/">На главную</a>
    <p class="tableDescription">Просмотр фондов.</p>
    <table id="fondsViewAll">
        <tr id="fondsPreviewHead">
            <th>РегНомерПиф</th>
            <th>ПаевойИнвестиционныйФонд</th>
            <th>Edit</th>
            <th>View</th>
            <th>Дата</th>
            <th>СЧА</th>
            <th>дата загрузки СЧА</th>
        </tr>
        <?php
        $trIndex = 0;
        foreach ($uploadedFonds as $index => $fondData) {
            $fondID = (isset($fondData['id']) && is_numeric($fondData['id'])) ? (integer) $fondData['id'] : 0;
            if (isset($fondData['regNumber'], $fondData['name'], $fondData['fondDates'])) {
                if (!empty($fondData['fondDates'])) {


                    // то чё?
                }
                ?>

                <?php
                $trIndex++;
                if (($trIndex % 2) > 0) {
                    $trColor = 'evenRow';
                } else {
                    $trColor = 'oddRow';
                }
                if (is_array($fondData['fondDates']) && count($fondData['fondDates'])) {
                    $rowspan = count($fondData['fondDates']) + 1;
                }
                ?>
                <tr class="fondRow <?= ' ' . $trColor ?>">
                    <td rowspan="<?= $rowspan ?>">
                        <?= $fondData['regNumber'] ?>
                    </td>
                    <td rowspan="<?= $rowspan ?>">
                        <?= $fondData['name'] ?>
                    </td>
                    <td rowspan="<?= $rowspan ?>">
                        <a href="/includes/editFond.php?fondID=<?= $fondData['id'] ?>" target="_blank">edit</a>
                    </td>
                    <td rowspan="<?= $rowspan ?>">
                        <a href="/includes/viewFond.php?fondID=<?= $fondData['id'] ?>" target="_blank">view</a>
                    </td>
                    <?php
                    if (is_array($fondData['fondDates']) && count($fondData['fondDates'])) {
                        foreach ($fondData['fondDates'] as $date => $datesValues) {
                            if (isset($datesValues['fd_id'], $datesValues['fd_fond_id'], $datesValues['fd_date'], $datesValues['fd_sca'], $datesValues['fd_sca_change_time'])) {
                                ?>
                            <tr class="<?= ' ' . $trColor ?>">
                                <td><?= $datesValues['fd_date'] ?></td>
                                <td class="align-right"><?= $datesValues['fd_sca'] ?></td>
                                <td><?= $datesValues['fd_sca_change_time'] ?></td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
            </tr>
            <?php
        } else {
            die('Не могу составить таблицу. Что-то не пришло. Слушайтесь Инночку и обратитесь к разработчику. Код ошибки: 4021');
        }
        unset($rowspan, $trColor);
    }
    ?>

    </table><br> 
    <?php
}
?>


