<?php
$classroom = $classroom->read_by_id($id);
$grade_levels = ["Transition", "First Grade", "Second Grade", "Third Grade", "Fourth Grade", "Fifth Grade"];
?>

<?php if ($classroom) : ?>
    <form>
        <input type="hidden" name="id" value="<?= $id ?>">
        <fieldset class="column mid-width grey">
            <legend><?= $classroom["name"] ?></legend>
            <table>
                <tr>
                    <th>Name: </th>
                    <td> <input type="text" name="name" value="<?= $classroom["name"] ?>" required></td>
                </tr>
                <tr>
                    <th>Grade Level: </th>
                    <td>
                        <select name="grade_level">
                            <?php foreach ($grade_levels as $grade_level) : ?>
                                <?php if ($grade_level === $classroom["grade_level"]) : ?>
                                    <option value="<?= $classroom["grade_level"] ?>" selected><?= $classroom["grade_level"] ?></option>
                                <?php else : ?>
                                    <option value="<?= $grade_level ?>"><?= $grade_level ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Add Students: </th>
                    <td><textarea name="students" cols="30" rows="10"></textarea></td>
                </tr>
            </table>
            <input type="submit" value="Save">
        </fieldset>
    </form>
    <script type="module">
        import Classroom from "/scholar/scripts/classes/Classroom.js"
        const classroom = new Classroom()
        //This is also updateClassroomHandler()
        classroom.createStudentsHandler(document.querySelector("form"))
    </script>
<?php endif ?>