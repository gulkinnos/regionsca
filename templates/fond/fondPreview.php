<?php
var_dump($fondData);
$fondID = (isset($fondData['id']) && is_numeric($fondData['id'])) ? (integer) $fondData['id'] : 0;
if (isset($fondData['regNumber'], $fondData['name'], $fondData['sca'], $fondData['date'])) {
    ?>

    <tr class="fondRow">
    <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][fondID]" value='<?= $fondID ?>'/>
    <td>
        <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][regNumber]" value='<?= $fondData['regNumber'] ?>'/>
    <?= $fondData['regNumber'] ?>
    </td>
    <td>
        <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][name]" value='<?= $fondData['name'] ?>'/>
    <?= $fondData['name'] ?>
    </td>
    <td>
        <input name="parsedData[<?= $fondData['regNumber'] ?>][sca]" value="<?= $fondData['sca'] ?>"/>
    </td>
    <td>
        <input type="hidden" name="parsedData[<?= $fondData['regNumber'] ?>][date]" value='<?= $fondData['date'] ?>'/>
    <?= $fondData['date'] ?>
    </td>
    </tr>

<?php } ?>

