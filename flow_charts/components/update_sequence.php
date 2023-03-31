<?php $sequence = $flow_chart->read_sequence($id) ?>
<?php if (isset($_SESSION["flow_charts"]["update_sequence"])) : ?>
    <script>
        alert("Nothing was updated")
    </script>
<?php endif ?>
<?php unset($_SESSION["flow_charts"]["update_sequence"]) ?>

<fieldset style="background-color:lightyellow">
    <legend>
        <?= $sequence["position"] ?>
    </legend>
    <form action="controller.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="request" value="update_sequence">
        <table>
            <tr>
                <img style="display:block;margin:auto" src="<?= $sequence["img_path"] ?>" alt="sequence" height="150">
            </tr>
            <tr>
                <th style="color:red" colspan="2">Modify Image: </th>
            </tr>
            <tr>
                <td colspan="2"><input style="background-color:white" type="file" name="image"></td>
            </tr>
            <tr>
                <th>Position: </th>
                <td>
                    <select name="position">
                        <?php for ($i = 0; $i < $sequence["num_positions"]; $i++) {
                            if ($i + 1 === $sequence["position"]) echo '<option value="' . $i + 1 . '" selected>' . $i + 1 . '</option>';
                            else
                                echo '<option value="' . $i + 1 . '">' . $i + 1 . '</option>';
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Description: </th>
                <td>
                    <textarea name="description" cols="16" rows="3" required><?= $sequence["description"] ?>      </textarea>
                </td>
            </tr>

        </table>
        <input style="margin-top:7px" name="action" type="submit" value="Save">
        <input style="margin-top:7px;float:right;background-color:salmon;color:white" name="action" type="submit" value="Delete">
    </form>
</fieldset>