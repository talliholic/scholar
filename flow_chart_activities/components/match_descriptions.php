<?php $activity = $flow_chart_activity->get_by_id($id) ?>
<?php
?>

<div style="display:flex; flex-flow:row wrap; justify-content:space-around">
    <?php if (!isset($_SESSION["score"])) : ?>
        <fieldset id="container_words" style="background-color:#E8E8E8;">
            <legend>Grab a description and put it under the correct image.</legend>
            <table>
                <?php foreach ($activity["sequences_a"] as $i => $sequence) : ?>
                    <tr>
                        <td style="height:50px"></td>
                    </tr>
                    <tr class="filling">
                        <td class="fill" draggable="true">
                            <textarea style="background-color:white;border:none; font-weight:bold;user-select:none;margin:0;padding:0 5px;width:289px;" readonly class="draggable" type="text" name="input[]"><?= $sequence["description"] ?></textarea>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>
        </fieldset>
    <?php endif ?>
    <fieldset id="container_fieldset" style="background-color:#E8E8E8;">
        <legend>Place a description under the correct image.</legend>
        <form action="controller.php" method="POST">
            <input type="hidden" name="request" value="Match Descriptions">
            <input type="hidden" name="flow_chart_id" value="<?= $id ?>">
            <input type="hidden" name="author" value="<?= $activity["author"] ?>">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <input type="hidden" name="date_taken" value="<?= time() ?>">
            <table>
                <tr>
                    <td style="font-weight:bold;color:green">
                        <?php if (isset($_SESSION["score"])) : ?>
                            You scored <?= $_SESSION["score"] ?> points.
                        <?php endif ?>
                    </td>
                </tr>
                <?php foreach ($activity["ordered_sequences"] as $i => $sequence) : ?>
                    <tr>
                        <td style="font-weight:bold;"><?= $i + 1 ?>.</td>
                        <td><img style="display:block;margin:auto" height="50" src="<?= $sequence["img_path"] ?>" alt="Sequence"></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="target[]" value="<?= $activity["ordered_sequences"][$i]["description"] ?>">
                        </td>
                    </tr>
                    <?php if (isset($_SESSION["answers"])) : ?>
                        <tr>
                            <td style="color:blue">You dragged...</td>
                        </tr>
                        <tr>
                            <td colspan="2"><textarea style="background-color:white;border:none; font-weight:bold;user-select:none;margin:0;padding:5px;width:289px;" readonly><?= $_SESSION["answers"][$i] ?></textarea></td>
                        </tr>
                        <tr>
                            <?php if (($_SESSION["feedback"][$i])) : ?>
                                <td><span class="check">&#10004;</span></td>
                            <?php else : ?>
                                <td> <span class="cross">&#x274C;</span></td>
                            <?php endif ?>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <td style="height:30px;width:300px" class="empty" colspan="2"> </td>
                        </tr>
                        <tr style="height:20px;">
                            <td>
                                <br>
                            </td>
                        </tr>

                    <?php endif ?>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <td>

                    </td>
                    <?php if (!isset($_SESSION["score"])) : ?>
                        <td> <input style="float:right;font-size:18px" id="done" type="submit" value="Done"></td>
                    <?php else : ?>
                        <td><a href="/scholar/flow_chart_activities/?page=Match Descriptions&id=<?= $id ?>">Play again</a></td>
                    <?php endif ?>
                </tr>
            </table>

        </form>
        <?php unset($_SESSION["answers"]) ?>
        <?php unset($_SESSION["feedback"]) ?>
        <?php unset($_SESSION["score"]) ?>
    </fieldset>
</div>

<script>
    const fill = document.querySelectorAll(".fill")
    const empties = document.querySelectorAll(".empty")
    const done = document.getElementById("done")

    empties.forEach(empty => {
        empty.addEventListener("dragover", drag_over)
        empty.addEventListener("dragenter", drag_enter)
        empty.addEventListener("dragleave", drag_leave)
        empty.addEventListener("drop", drop)
    })

    fill.forEach((div) => {
        div.addEventListener("dragstart", drag_start)
        div.addEventListener("dragend", drag_end)
    })

    done.addEventListener("click", done_handler)

    function drag_start() {
        this.className += " hold";
        setTimeout(() => (this.className = "invisible"), 0)
    }

    function drag_end() {
        this.className = "fill";
    }

    function drag_over(e) {
        e.preventDefault()
    }

    function drag_enter(e) {
        e.preventDefault()
        this.className = " hovered"
    }

    function drag_leave() {
        this.className = "empty"
    }

    function drop(e) {
        const fillings = document.querySelectorAll(".filling")
        if (this.children.length === 0) {
            this.className = "empty"
            this.append(document.querySelector(".invisible"))
        }
        const fillings_done = []

        fillings.forEach(filling => {
            if (filling.children.length === 0) fillings_done.push(true)
        })

        if (fillings_done.length === fillings.length) document.getElementById("container_words").classList.add("hidden")

    }

    function done_handler(e) {
        const num_empties = document.querySelectorAll("form .fill").length
        if (num_empties !== empties.length) {
            e.preventDefault()
            alert("You MUST drag all descriptions!")
        } else {
            document.getElementById("container_fieldset").classList.add("hidden")
        }
    }
</script>