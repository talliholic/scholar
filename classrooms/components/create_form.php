<form>
    <fieldset class="column mid-width">
        <legend>Create a classroom</legend>
        <table>
            <tr>
                <input type="text" name="name" placeholder="Classroom name" required>
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
        </table>
        <input type="submit" value="Create">
    </fieldset>
</form>

<script type="module">
    import Classroom from "/scholar/scripts/classes/Classroom.js"
    const classroom = new Classroom()
    classroom.createHandler(document.querySelector("form"))
</script>