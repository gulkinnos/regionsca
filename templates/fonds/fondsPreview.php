<?php
if (is_array($fondsData) && count($fondsData)) {
    ?>
    <form name="parsedFondsData" action="/includes/applyParsing.php" method="POST" enctype="multipart/form-data">
        <table id="fondsPreview">
            <tr id="fondsPreviewHead">
                <th>РегНомерПиф</th>
                <th>ПаевойИнвестиционныйФонд</th>
                <th>СЧА</th>
                <th>Дата</th>
            </tr>
            <?php
            foreach ($fondsData as $index => $fondData) {
                include $_SERVER['DOCUMENT_ROOT'] . '/templates/fond/fondPreview.php';
            }
            ?>

        </table><br> 
        <input type="submit" value="Сохранить данные" name="saveXTDD"/>
        <input type="hidden" value="save" name="saveParsedData"/>
    </form>
    <?php
}
?>


