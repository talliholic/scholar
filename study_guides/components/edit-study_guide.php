<?php
$study_guide = $study_guides->read_by_id($id);
$grade_levels = ["Transition", "First Grade", "Second Grade", "Third Grade", "Fourth Grade", "Fifth Grade"];
$subjects = ["ICT", "ENGLISH", "MATH", "SPANISH", "SCIENCE", "HISTORY", "SOCIAL STUDIES", "SCIENCE", "PE"];
?>

<?php if ($study_guide) : ?>
    <!-- <h2 style="width:100%; text-align:center;margin:0;"><i>Study Guide: <?= $study_guide["title"] ?></i></h2> -->
    <form>
        <input type="hidden" name="id" value="<?= $id ?>">
        <fieldset class="column mid-width grey">
            <legend><b>Study Guide: </b><?= $study_guide["title"] ?></legend>
            <a href="?page=definitions&id=<?= $id ?>">Definitions in this guide</a>
            <a href="#" id="edit">Edit Guide</a>
            <table id="guide" class="hidden">
                <div id="study-guide" class="hidden">

                </div>
                <tr>
                    <th>Name: </th>
                    <td> <input type="text" name="title" value="<?= $study_guide["title"] ?>" required></td>
                </tr>
                <tr>
                    <th>Grade Level: </th>
                    <td>
                        <select name="grade_level">
                            <?php foreach ($grade_levels as $grade_level) : ?>
                                <?php if ($grade_level === $study_guide["grade_level"]) : ?>
                                    <option value="<?= $study_guide["grade_level"] ?>" selected><?= $study_guide["grade_level"] ?></option>
                                <?php else : ?>
                                    <option value="<?= $grade_level ?>"><?= $grade_level ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Subject: </th>
                    <td>
                        <select name="subject">
                            <?php foreach ($subjects as $subject) : ?>
                                <?php if ($subject === $study_guide["subject"]) : ?>
                                    <option value="<?= $study_guide["subject"] ?>" selected><?= $study_guide["subject"] ?></option>
                                <?php else : ?>
                                    <option value="<?= $subject ?>"><?= $subject ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
            </table>
            <a href="#" class="hidden" id="cancel-edition">Cancel</a>
            <table id="definitions">
                <tr>
                    <td><input type="text" name="word" placeholder="Add a word"></td>
                </tr>
                <tr>
                    <td><textarea name="definition" id="" cols="30" rows="3" placeholder="Add a definition"></textarea></td>
                </tr>
                <tr>
                    <td style="text-align:center;color:red;font-weight:bold">Upload an image:</td>
                </tr>
                <tr>
                    <td><input type="file" name="image"></td>
                </tr>
            </table>
            <input type="submit" value="Save">
        </fieldset>
    </form>
    <script type="module">
        import StudyGuide from "/scholar/scripts/classes/StudyGuide.js"
        const studyGuide = new StudyGuide()
        //This is also updateStudyGuideHandler()
        studyGuide.updateHandler(document.querySelector("form"))
    </script>
<?php endif ?>