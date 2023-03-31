<?php
$chart = $flow_chart->read_by_id($id);
$types = ["Skill", "Events", "Cycle"];
$grade_levels = ["Transition", "First Grade", "Second Grade", "Third Grade", "Fourth Grade", "Fifth Grade"];
$subjects = ["ICT", "ENGLISH", "MATH", "SPANISH", "SCIENCE", "HISTORY"];
?>
<?php if (isset($_SESSION["flow_charts"])) : ?>
    <script>
        alert("<?= $_SESSION["flow_charts"] ?>")
    </script>
<?php endif ?>
<?php unset($_SESSION["flow_charts"]) ?>
<h2 style="width:100%; text-align:center;margin:0;"><i>Editing Flow Chart</i></h2>
<fieldset style="background-color:lightgoldenrodyellow">
    <legend><?= $chart["title"] ?></legend>
    <form action="controller.php" method="POST">
        <input type="hidden" name="request" value="update_flow_chart">
        <input type="hidden" name="id" value="<?= $id ?>">
        <table>
            <tr>
                <td colspan="2">
                    <img height="150" style="display:block;margin:auto" src="<?= $chart["img_path"] ?>" alt="Flow Chart Image">
                </td>
            </tr>
            <tr>
                <th style="text-align:left">Title: </th>
                <td> <input type="text" name="title" value="<?= $chart["title"] ?>" required></td>
            </tr>
            <tr>
                <th style="text-align:left">Subject: </th>
                <td>
                    <select name="subject">
                        <?php foreach ($subjects as $subject) : ?>
                            <?php if ($subject === $chart["subject"]) : ?>
                                <option value="<?= $subject ?>" selected><?= $subject ?></option>
                            <?php else : ?>
                                <option value="<?= $subject ?>"><?= $subject ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th style="text-align:left">Grade Level: </th>
                <td>
                    <select name="grade_level">
                        <?php foreach ($grade_levels as $grade_level) : ?>
                            <?php if ($grade_level === $chart["grade_level"]) : ?>
                                <option value="<?= $grade_level ?>" selected><?= $grade_level ?></option>
                            <?php else : ?>
                                <option value="<?= $grade_level ?>"><?= $grade_level ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th style="text-align:left">Type: </th>
                <td>
                    <select name="type">
                        <?php foreach ($types as $type) : ?>
                            <?php if ($type === $chart["type"]) : ?>
                                <option value="<?= $type ?>" selected><?= $type ?></option>
                            <?php else : ?>
                                <option value="<?= $type ?>"><?= $type ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="padding-top:10px">
                    <input type="submit" value="Save">
                </td>
                <td style="float:right;padding-top:10px">
                    <input id="remove" style="background-color:salmon;color:white" type="submit" value="Remove">
                </td>
            </tr>
        </table>
    </form>
</fieldset>
<script>
    document.querySelector("#remove").addEventListener("click", remove)

    function remove(e) {
        e.preventDefault()
        const will_remove = confirm("Do you want to delete this flow chart? All sequences and activities will be removed. Student's results will be kept.")
        if (will_remove) {
            const body = new FormData()
            body.append("request", "delete")
            body.append("id", "<?= $id ?>")
            fetch("/scholar/api/flow_chart.php", {
                method: "POST",
                body
            }).then((res) => res.json()).then((res) => {
                if (res.success) {
                    alert("Flow chart deleted!")
                    window.location.replace("/scholar/flow_charts/?page=flow_charts")
                } else {
                    alert("I could not delete the flow chart. Try again")
                    location.reload()
                }
            })
        }
    }
</script>