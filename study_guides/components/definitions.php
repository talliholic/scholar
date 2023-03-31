<?php
$definitions = $study_guides->read_definitions($id);
$study_guide = $study_guides->read_by_id($id);
?>

<h2 style="width:100%; text-align:center;margin:0 0 15px 0;"><i>Study Guide: <?= $study_guide["title"] ?></i></h2>

<?php foreach ($definitions as $definition) : ?>
    <div>
        <fieldset class="grey">
            <legend><?= $definition["word"] ?></legend>
            <table>
                <tr>
                    <th>Image: </th>
                    <td><img class="small-img" src="<?= $definition["img_path"] ?>" alt="<?= $definition["word"] ?>"></td>
                </tr>
                <tr>
                    <th>Definition: </th>
                    <td class="small-field"><?= $definition["definition"] ?></td>
                </tr>
                <tr>
                    <td><a href="?page=edit-definition&id=<?= $definition["id"] ?>">Edit</a></td>
                    <td><a style="color:red;float:right" class="delete-button" id="<?= $definition["id"] ?>" href="#">Delete</a></td>
            </table>
        </fieldset>
    </div>
<?php endforeach ?>
<script type="module">
    import StudyGuide from "/scholar/scripts/classes/StudyGuide.js"
    const studyGuide = new StudyGuide()
    studyGuide.delete_definition_handler(document.querySelectorAll(".delete-button"))
</script>