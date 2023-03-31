<?php if ($guides) : ?>
    <h1 style="width:100%;text-align:center;margin:0">Study Guides</h1>
<?php endif ?>
<?php foreach ($guides as $study_guide) : ?>
    <?php $image = $study_guides->read_image($study_guide["id"]) ?>
    <div>
        <fieldset class="grey">
            <legend><?= $study_guide["title"] ?></legend>
            <table>
                <tr>
                    <img class="medium-img center-img" src="<?= $image ?>" alt="<?= $study_guide["title"] ?>">
                </tr>
                <tr>
                    <th>Grade Level: </th>
                    <td><?= $study_guide["grade_level"] ?></td>
                </tr>
                <tr>
                    <td><a href="?page=edit&id=<?= $study_guide["id"] ?>">Edit</a></td>
                    <td><a href="?page=definitions&id=<?= $study_guide["id"] ?>">View</a></td>
                </tr>
                <tr>
                    <td style="padding-top:5px;"><a href="#" class="delete-button" id="<?= $study_guide["id"] ?>">Delete</a></td>
                </tr>
            </table>
        </fieldset>
    </div>
<?php endforeach ?>
<script type="module">
    import StudyGuide from "/scholar/scripts/classes/StudyGuide.js"
    const studyGuide = new StudyGuide()
    studyGuide.delete_handler(document.querySelectorAll(".delete-button"))
</script>