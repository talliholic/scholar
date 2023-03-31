<!-- <?php
        $guide_activity = isset($_GET["activity"]) ? $_GET["activity"] : "";
        $guide_id = isset($_GET["guide_id"]) ? $_GET["guide_id"] : "";
        $results = $activity->get_best_scores_by_guide($guide_id, $guide_activity);
        ?>
<fieldset class="grey">
    <legend>Best Results</legend>
    <table>
        <tr class="center-data">
            <th>Rank</th>
            <th>Student</th>
            <th>Link</th>
            <th>Grade</th>
            <th>Duration</th>
        </tr>
        <?php foreach ($results as $i => $result) : ?>
            <tr class="center-data">
                <td>
                    <?= $i + 1 ?>
                </td>
                <td>
                    <?= $result["student"] ?>
                </td>
                <td>
                    <a href="<?= $result["link"] ?>"> <?= $result["activity"] ?></a>
                </td>
                <td>
                    <?= $result["score"] ?>
                </td>
                <td>
                    <?= $result["duration"] ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</fieldset> -->