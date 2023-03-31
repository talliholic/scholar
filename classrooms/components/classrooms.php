<?php
$classrooms = $classroom->read_all();
?>
<?php if ($classrooms) : ?>
    <h1 style="width:100%;text-align:center;margin:0">Classrooms</h1>
<?php endif ?>

<?php foreach ($classrooms as $classroom) : ?>
    <div>
        <fieldset class="grey">
            <legend><?= $classroom["name"] ?></legend>
            <table>
                <tr>
                    <img class="medium-img center-img" src="/scholar/media/default_images/classroom.png" alt="Classroom">
                </tr>
                <tr>
                    <th>Grade Level: </th>
                    <td><?= $classroom["grade_level"] ?></td>
                </tr>
                <tr>
                    <td><a href="?page=edit-classroom&id=<?= $classroom["id"] ?>">Edit</a></td>
                    <td><a style="float:right" href="?page=students&id=<?= $classroom["id"] ?>">View</a></td>
                </tr>
                <tr>
                    <td><a href="?page=results&id=<?= $classroom["id"] ?>">Results</a></td>
                    <td><a style="color:red;float:right" class="delete-button" id="<?= $classroom["id"] ?>" href="#">Delete</a></td>
                </tr>
            </table>
        </fieldset>
    </div>
<?php endforeach ?>
<script type="module">
    import Classroom from "/scholar/scripts/classes/Classroom.js"
    const classroom = new Classroom()
    classroom.deleteHandler()
</script>