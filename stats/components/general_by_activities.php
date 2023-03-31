<?php $standings = $activity->get_general_standings($user_id, "activities") ?>
<fieldset class="grey">
    <legend>Points Leaders</legend>
    <table>
        <tr class="center-data">
            <th>Rank</th>
            <th>Student</th>
            <th>Activities</th>
        </tr>
        <?php foreach ($standings as $i => $result) : ?>
            <?php if ($result["taken_by"]) : ?>
                <tr class="center-data">
                    <td>
                        <?= $i + 1 ?>
                    </td>
                    <td style="text-align:left">
                        <?= $result["taken_by"] ?>
                    </td>
                    <td>
                        <?= $result["activities"] ?>
                    </td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
    </table>
</fieldset>