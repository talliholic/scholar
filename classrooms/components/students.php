<?php
$students = $classroom->read_students($id);
$classroom =  $classroom->read_by_id($id);
?>

<?php if ($classroom) : ?>
    <div class="block">
        <h2 style="margin:0"><?= $classroom["name"] ?></h2>
        <a class="lightlink" href=" ?page=edit-classroom&id=<?= $id ?>">Edit or add students</a>
    </div>
    <?php foreach ($students as $student) : ?>
        <fieldset class="grey">
            <legend><?= $student["name"] ?></legend>
            <table>
                <tr>
                    <img class="medium-img center-img" src="/scholar/media/default_images/student.jpg" alt="Student">
                </tr>
                <tr>
                    <th>Username: </th>
                    <td><?= $student["username"] ?></td>
                </tr>
                <tr>
                    <th>Password: </th>
                    <td><?= $student["password"] ?></td>
                </tr>
                <tr>
                    <td><a href="?page=edit-student&id=<?= $student["id"] ?>">Edit</a></td>
                    <td><a href="#" class="delete-button" id="<?= $student["id"] ?>">Delete</a></td>
                </tr>
            </table>
        </fieldset>
    <?php endforeach ?>
<?php endif ?>
<script type="module">
    import Classroom from "/scholar/scripts/classes/Classroom.js"
    const classroom = new Classroom()
    classroom.delete_student_handler(document.querySelectorAll(".delete-button"))
</script>