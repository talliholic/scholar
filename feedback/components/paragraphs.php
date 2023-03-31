<?php $writings = $feedback->get_students_writings();
// echo '<pre>';
// print_r($writings);
// echo '</pre>';
?>
<?php if (!$writings) : ?>
    <h2 style="text-align:center;width:100%;background-color:lightgoldenrodyellow">There are no paragraphs to grade</h2>
<?php endif ?>

<?php foreach ($writings as $writing) : ?>
    <fieldset style="width:300px;background-color:white">
        <legend><?= $writing["study_guide_title"] ?></legend>
        <div>
            <img style="display:block;margin:auto" src="<?= $writing["img_path"] ?>" height="100" alt="<?= $writing["study_guide_title"] ?>">
        </div>
        <div>
            <a style="display:block;margin:auto;margin-top:10px" target="_blank" href="<?= $writing["instructions"] ?>">Instructions</a>
        </div>

        <p style="align-text:justify;font-size:14px"><?= $writing["body"] ?></p>
        <form action="controller.php" method="POST">
            <input type="hidden" name="id" value="<?= $writing["id"] ?>">
            <input type="hidden" name="author_id" value="<?= $writing["author_id"] ?>">
            <input type="hidden" name="study_guide_id" value="<?= $writing["study_guide_id"] ?>">
            <input type="hidden" name="study_guide_author_id" value="<?= $writing["study_guide_author"] ?>">
            <input type="hidden" name="activity" value="Paragraph">
            <input type="hidden" name="request" value="score_paragraph">
            <input type="hidden" name="teacher_id" value="<?= $writing["teacher_id"] ?>">
            <input type="hidden" name="num_definitions" value="<?= $writing["num_definitions"] ?>">
            <textarea placeholder="Write your feedback comment" name="comment" cols="34" rows="4"></textarea>
            <table>
                <tr>
                    <td colspan="3" style="align-text:justify;font-size:12px;font-style:italic">*<?= $writing["taken_by"] ?>'s score for this writing activity will be <?= $writing["num_definitions"] ?> times the grade you give. For example, if the grade you give is 100, her/his activity score will be <?= $writing["num_definitions"] * 100 ?>.</td>
                </tr>
                <tr>
                    <th>Grade <span style="font-size:14px">(0-100)</span>: </th>
                    <td> <input style="height:25px;text-align:center;font-size:18px" type="number" min="0" max="100" name="grade"></td>
                    <td> <input type="submit" value="Save"></td>
                </tr>
            </table>
        </form>
    </fieldset>
<?php endforeach ?>