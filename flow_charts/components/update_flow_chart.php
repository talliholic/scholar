<?php
$flow_chart = $flow_chart->read_by_id($id);
$grade_levels = ["Transition", "First Grade", "Second Grade", "Third Grade", "Fourth Grade", "Fifth Grade"];
$subjects = ["ICT", "ENGLISH", "MATH", "SPANISH", "SCIENCE", "HISTORY"];
?>

<?php if (isset($_SESSION["flow_charts"]["update_flow_chart"])) : ?>
    <script>
        alert("You have created a flow chart. Add the first sequence.")
    </script>
<?php endif ?>
<?php if (isset($_SESSION["flow_charts"]["update_flow_chart"])) unset($_SESSION["flow_charts"]["update_flow_chart"]) ?>

<?php if ($flow_chart) : ?>
    <h2 style="width:100%; text-align:center;margin:0;"><i>Update the flow chart</i></h2>
    <form method="POST" action="controller.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="request" value="create_sequence">
        <fieldset class="column mid-width grey">
            <legend><?= $flow_chart["title"] ?></legend>
            <a href="?page=sequences&id=<?= $id ?>">Sequences in this flow chart</a>
            <table id="definitions">
                <tr>
                    <td><textarea name="description" id="" cols="35" rows="2" placeholder="Add a sequence" required></textarea></td>
                </tr>
                <tr>
                    <td style="text-align:center;color:red;font-weight:bold">Image describing the sequence:</td>
                </tr>
                <tr>
                    <td><input type="file" name="image" required></td>
                </tr>
            </table>
            <input type="submit" value="Save">
        </fieldset>
    </form>

<?php endif ?>