<?php $performance = $activity->get_performance($user_id, $role) ?>
<?php if (isset($performance)) : ?>
    <table class="grey" style="font-size:12px;border-radius:10%;padding:3px">
        <tr>
            <th>Points: </th>
            <td style="border:1px dotted black;padding:0 4px;text-align:center"><?= $performance["total_points"] ?></td>
            <th>Average: </th>
            <td style="border:1px dotted black;padding:0 4px;text-align:center"><?= $performance["average"] ?></td>
            <th>Activities: </th>
            <td style="border:1px dotted black;padding:0 4px;text-align:center"><?= $performance["activities"] ?></td>
            <th>Unique Activities: </th>
            <td style="border:1px dotted black;padding:0 4px;text-align:center"><?= $performance["different_activities"] ?></td>
            <td><a id="refresh-button" style="background-color:lightgreen;color:black;margin-left:5px;font-size:12px" href="#">Update</a></td>
        </tr>
    </table>
    <script>
        document.getElementById("refresh-button").addEventListener("click", (e) => {
            e.preventDefault()
            location.reload()
        })
    </script>
<?php endif ?>