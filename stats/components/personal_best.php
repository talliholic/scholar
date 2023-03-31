<?php $results = $activity->get_best_scores($user_id) ?>
<fieldset class="grey">
    <legend>Personal Best</legend>
    <table>
        <tr class="center-data">
            <th>Taken</th>
            <th>Study Guide</th>
            <th>Activity</th>
            <th>Grade</th>
            <th>Duration</th>
        </tr>
        <?php foreach ($results as $result) : ?>
            <tr class="center-data">
                <td>
                    <?= $result["date_taken"] ?>
                </td>
                <td>
                    <?= $result["guide_title"] ?>
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
</fieldset>