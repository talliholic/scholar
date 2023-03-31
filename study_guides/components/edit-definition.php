<?php $definition = $study_guides->read_definition($id) ?>

<form>
    <input type="hidden" name="definition_id" value="<?= $definition["id"] ?>">
    <input type="hidden" name="guide_id" value="<?= $definition["study_guide"] ?>">
    <fieldset class="mid-width column grey">
        <legend><?= $definition["word"] ?></legend>
        <table>
            <tr>
                <th>Image: </th>
                <td><img class="medium-img" src="<?= $definition["img_path"] ?>" alt="<?= $definition["word"] ?>"></td>
            </tr>
            <tr>
                <th>Change Image: </th>
                <td><input type="file" name="image"></td>
            </tr>
            <tr>
                <th>Word: </th>
                <td><input type="text" name="word" value="<?= $definition["word"] ?>" required></td>
            </tr>
            <tr>
                <th>Definition: </th>
                <td><textarea name="definition" cols="30" rows="3" required><?= $definition["definition"] ?></textarea></td>
            </tr>
        </table>
        <input type="submit" value="Save">
    </fieldset>
</form>

<script type="module">
    import StudyGuide from "/scholar/scripts/classes/StudyGuide.js"
    const studyGuide = new StudyGuide()
    studyGuide.update_definition_handler(document.querySelector("form"))
</script>