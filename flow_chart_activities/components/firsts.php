<?php $activity = $flow_chart_activity->get_by_id($id) ?>
<?php if ($activity) : ?>
    <fieldset style="background-color:aliceblue">
        <legend><?= $activity["title"] ?></legend>
        <form action="controller.php" method="POST">
            <input type="hidden" name="request" value="Firsts">
            <input type="hidden" name="flow_chart_id" value="<?= $id ?>">
            <input type="hidden" name="author" value="<?= $activity["author"] ?>">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <input type="hidden" name="date_taken" value="<?= time() ?>">
            <table>
                <?php if (isset($_SESSION["score"])) : ?>
                    <script>
                        alert("You scored <?= $_SESSION["score"] ?> points.")
                    </script>
                <?php endif ?>
                <?php foreach ($activity["sequences"] as $i => $sequence) : ?>
                    <tr>
                        <th style="padding:20px 0" colspan="2"><?= $i + 1 ?>. <?= $sequence["first"]["question"] ?></th>
                    </tr>
                    <input type="hidden" name="target[]" value="<?= $sequence["first"]["position"] ?>">
                    <?php foreach ($sequence["position_options"] as $option) : ?>
                        <tr>
                            <td colspan="2"><img style="display:block;margin:auto" height="100" src="<?= $option["img_path"] ?>" alt="Option"></td>
                        </tr>
                        <tr>
                            <td><input type="radio" name="question_<?= $i ?>" value="<?= $option["position"] ?>" required></td>
                            <td><?= $option["description"] ?></td>
                        </tr>
                    <?php endforeach ?>
                    <tr>
                        <td colspan="2" style="height:20px;border-bottom:1px solid black;width:100%"></td>
                    </tr>
                <?php endforeach ?>
            </table>
            <input style="margin-top:20px" type="submit" value="Done">
        </form>
        <?php unset($_SESSION["score"]) ?>
    </fieldset>
<?php endif ?>