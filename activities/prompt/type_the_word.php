<?php $activity_ = $activity->get_study_guide($id) ?>

<?php if ($activity_ && count($activity_["definitions"]) > 3) : ?>
    <form>
        <input type="hidden" name="guide_id" value="<?= $id ?>">
        <input type="hidden" name="activity" value="Type the Word">
        <input type="hidden" name="author_id" value="<?= $activity_["author"] ?>">
        <b class="worksheet-heading hidden">Name: _________________________ Class: _________________ Date: _______ </b>
        <h1 style="margin-top:0;text-align:center">Type the Word</h1>
        <fieldset class="grey best_results" id="best_results"></fieldset>
        <fieldset class="grey">
            <legend><?= $activity_["title"] ?></legend>
            <ol>
                <h3>Instructions</h3>
                <li>Look at the image and word.</li>
                <li>Type the word.</li>
            </ol>
            <div class="items">
                <?php foreach ($activity_["definitions"] as $i => $definition) : ?>
                    <div class="item">
                        <div class="question">
                            <img class="medium-img word" src="<?= $definition["data"]["img_path"] ?>" class="word" id="<?= $definition["data"]["word"] ?>" alt="<?= $definition["data"]["word"] ?>">
                        </div>
                        <div style="display:flex;flex-flow:row wrap;column-gap:10px;justify-content:center">
                            <div>
                                <?= $definition["data"]["definition"] ?>
                            </div>
                            <img style="cursor:pointer" class="speak" src="/scholar/media/content/speak.png" height="25" alt="Speak">
                        </div>
                        <div class="question" style="justify-content:center;column-gap:10px">
                            <div class="text-option">
                                <?= $definition["data"]["word"] ?>
                            </div>
                            <img style="cursor:pointer" class="speak" src="/scholar/media/content/speak.png" height="25" alt="Speak">
                        </div>
                        <div class="answer">
                            <input class="input" style="display:block;margin:5px auto;text-align:center;font-size:16px" type="text" name="question_<?= $i ?>" required>
                        </div>
                        <div class="feedback-div hidden" style="text-align:center">
                            <div class="feedback"></div>
                            <span class="check hidden">&#10004;</span>
                            <span class="cross hidden">&#x274C;</span>
                        </div>

                    </div>
                <?php endforeach ?>
            </div>
            <div class="buttons">
                <div style="margin-bottom:20px">
                    <input type="submit" id="submit-button" value="Done">
                    <button id="play-again-button" disabled>Play again!</button>
                </div>
                <div style="margin-bottom:20px">
                    <!-- <a href="/scholar/stats/?page=best_by_activity&activity=Type the Word&guide_id=<?= $id ?>">See best results</a> -->
                    <a style="float:right" href="?page=Type the Definition&id=<?= $id ?>">Next Activity</a>
                </div>
                <a id="print_worksheet" href="#">Print worksheet</a>
            </div>
        </fieldset>
    </form>
    <script type="module">
        import PromptActivity from "/scholar/scripts/classes/activities/PromptActivity.js"
        const activity = new PromptActivity()
        activity.getMultipleAnswers()
        activity.getPDFHandler()
    </script>
<?php endif ?>