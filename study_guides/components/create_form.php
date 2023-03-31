<form>
    <fieldset class="column mid-width grey">
        <legend>Make a study guide</legend>
        <table>
            <tr>
                <input type="text" name="title" placeholder="Study guide title" required>
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
                        <option value="SOCIAL STUDIES">SOCIAL STUDIES</option>
                        <option value="SCIENCE">SCIENCE</option>
                        <option value="PE">PE</option>
                    </select>
                </td>
            </tr>
        </table>
        <input type="submit" value="Make">
    </fieldset>
</form>

<script type="module">
    import StudyGuide from "/scholar/scripts/classes/StudyGuide.js"
    const studyGuide = new StudyGuide()
    studyGuide.createHandler(document.querySelector("form"))
</script>