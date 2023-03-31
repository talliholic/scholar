<?php $types = ["Skill", "Events", "Cycle"] ?>
<form method="POST" action="controller.php">
    <input type="hidden" name="request" value="create">
    <fieldset class="column mid-width grey">
        <legend>Create a Flow Chart</legend>
        <table>
            <tr>
                <input type="text" name="title" placeholder="Flow Chart Title" required>
            </tr>
            <tr>
                <th>Type: </th>
                <td>
                    <select name="type">
                        <?php foreach ($types as $type) : ?>
                            <option value="<?= $type ?>"><?= $type ?></option>
                        <?php endforeach ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Grade level: </th>
                <td>
                    <select name="grade_level" id="grade-level">
                        <option value="Transition">Transition</option>
                        <option value="First Grade">First Grade</option>
                        <option value="Second Grade">Second Grade</option>
                        <option value="Third Grade">Third Grade</option>
                        <option value="Fourth Grade">Fourth Grade</option>
                        <option value="Fifth Grade">Fifth Grade</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Subject: </th>
                <td>
                    <select name="subject" id="subject">
                        <option value="ICT">ICT</option>
                        <option value="ENGLISH">ENGLISH</option>
                        <option value="MATH">MATH</option>
                        <option value="SPANISH">SPANISH</option>
                        <option value="SCIENCE">SCIENCE</option>
                        <option value="HISTORY">HISTORY</option>
                    </select>
                </td>
            </tr>
        </table>
        <input type="submit" value="Make">
    </fieldset>
</form>