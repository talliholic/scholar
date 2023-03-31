<?php $activity_ = $activity->get_study_guide($id) ?>

<?php if ($activity_ && count($activity_["definitions"]) > 3) : ?>
    <form action="controller.php" method="POST">
        <input type="hidden" name="guide_id" value="<?= $id ?>">
        <input type="hidden" name="activity" value="Unscramble the Word">
        <input type="hidden" name="author_id" value="<?= $activity_["author"] ?>">
        <b class="worksheet-heading hidden">Name: _________________________ Class: _________________ Date: _______ </b>
        <h1 style="margin-top:0;text-align:center">Unscramble the Word</h1>
        <fieldset class="grey">
            <?php if (isset($_SESSION["score"])) : ?>
                <legend>Score</legend>
                <p style="background-color:white;font-weight:bold;font-size:14px;width:300px;padding:5px;text-align:justify;margin:auto;display:block">You just scored <?= $_SESSION["score"] ?> points in <?= $_SESSION["duration"] ?> seconds. <?= $_SESSION["message"] ?> See your answers below.</p>
            <?php endif ?>
        </fieldset>
        <fieldset class="grey">
            <legend><?= $activity_["title"] ?></legend>
            <ol>
                <h3>Instructions</h3>
                <li>Look at the image and word.</li>
                <li>Unscramble the Word.</li>
            </ol>
            <div class="items">
                <?php foreach ($activity_["definitions"] as $i => $definition) : ?>
                    <input class="input" type="hidden" name="target[]" value="<?= $definition["data"]["word"] ?>">
                    <input type="hidden" name="start_time" value="<?= time() ?>">
                    <div class="item">
                        <div class="question">
                            <img class="medium-img word" src="<?= $definition["data"]["img_path"] ?>" class="word" id="<?= $definition["data"]["word"] ?>" alt="<?= $definition["data"]["word"] ?>">
                        </div>
                        <div class="question" style="justify-content:center;column-gap:10px">
                            <div class="text-option">
                                <?= $definition["data"]["word"] ?>
                            </div>
                            <img style="cursor:pointer" class="speak" src="/scholar/media/content/speak.png" height="25" alt="Speak">
                        </div>
                        <div class="answer">
                            <?php if (isset($_SESSION["answer"])) : ?>
                                <input class="input" readonly style="display:block;margin:5px auto;text-align:center;font-size:16px" type="text" value="<?= $_SESSION["answer"][$i] ?>">
                            <?php else : ?>
                                <input readonly class="input" style="display:block;margin:5px auto;text-align:center;font-size:16px" type="text" name="input[]" required>
                            <?php endif ?>
                        </div>
                        <?php if (!isset($_SESSION["answer"])) : ?>
                            <div style="text-align:center;padding:10px" class="keyboard">
                                <?php foreach ($definition["data"]["word_scramble"] as $word_scramble) : ?>
                                    <span class="key" style="cursor:pointer;margin:0 5px;border:1px solid black;padding:6px;border-radius:40%;font-size:18px"><?= $word_scramble ?></span>
                                <?php endforeach ?>
                                <span class="back-arrow" style="font-size:25px;border:1px solid salmon;border-radius:40%;margin:0 5px;padding:0 6px;background-color:salmon;cursor:pointer;color:white">&#8592</span>
                            </div>
                        <?php endif ?>
                        <div class="feedback-div" style="text-align:center;margin:10px">
                            <?php if (isset($_SESSION["results"]) && $_SESSION["results"][$i] == 1) : ?>
                                <span class="check">&#10004;</span>
                            <?php endif ?>
                            <?php if (isset($_SESSION["results"]) && $_SESSION["results"][$i] == 0) : ?>
                                <span class="cross">&#x274C;</span>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <div class="buttons">
                <div style="margin-bottom:20px">
                    <?php if (isset($_SESSION["results"])) : ?>
                        <input type="submit" id="submit-button" value="Done" disabled>
                    <?php else : ?>
                        <input type="submit" id="submit-button" value="Done">
                    <?php endif ?>
                    <a href="?page=Unscramble the Word&id=<?= $id ?>" id="play-again-button"> Play again!</a>
                </div>
                <div style="margin-bottom:20px">
                    <a style="float:right" href="?page=Unscramble the Definition&id=<?= $id ?>">Next Activity</a>
                </div>
            </div>
        </fieldset>
    </form>
    <script type="module">
        const keys = document.querySelectorAll(".key")
        const backarrows = document.querySelectorAll(".back-arrow")
        const speak = document.querySelectorAll(".speak")

        keys.forEach((key) => key.addEventListener("click", type))
        backarrows.forEach((key) => key.addEventListener("click", undo))
        speak.forEach((div) => div.addEventListener("click", read_out_loud_handler))

        function type(e) {
            const input = e.target.parentElement.previousElementSibling.children[0]
            input.value += e.target.textContent
        }

        function undo(e) {
            const input = e.target.parentElement.previousElementSibling.children[0]
            if (input.value.length > 0) {
                input.value = input.value.substring(0, input.value.length - 1)
            }
        }

        function read_out_loud(text) {
            const synth = window.speechSynthesis
            const utterThis = new SpeechSynthesisUtterance(text)
            utterThis.pitch = 1
            utterThis.rate = 0.8
            utterThis.lang = 'en-US'

            synth.speak(utterThis)
        }

        function read_out_loud_handler(e) {
            const text = e.target.previousElementSibling.textContent
            read_out_loud(text)
        }
    </script>
<?php endif ?>
<?php unset($_SESSION["results"]) ?>
<?php unset($_SESSION["answer"]) ?>
<?php unset($_SESSION["score"]) ?>
<?php unset($_SESSION["duration"]) ?>
<?php unset($_SESSION["message"]) ?>