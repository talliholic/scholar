<?php $student = $classroom->read_student_by_id($id) ?>

<form>
    <fieldset class="column mid-width grey">
        <input type="hidden" name="id" , value="<?= $id ?>">
        <input type="hidden" name="classroom_id" , value="<?= $student["classroom_id"] ?>">
        <legend><?= $student["name"] ?></legend>
        <table>
            <tr>
                <th>Name: </th>
                <td><input type="text" name="name" value="<?= $student["name"] ?>" required></td>
            </tr>
            <tr>
                <th>Username: </th>
                <td><input type="text" name="username" value="<?= $student["username"] ?>" required></td>
            </tr>
            <tr>
                <th>Password: </th>
                <td><input type="text" name="password" value="<?= $student["password"] ?>" required></td>
            </tr>
        </table>
        <input type="submit" value="Save">
    </fieldset>
</form>

<script type="module">
    import Classroom from "/scholar/scripts/classes/Classroom.js"
    const classroom = new Classroom()
    classroom.update_student_handler(document.querySelector("form"))
</script>