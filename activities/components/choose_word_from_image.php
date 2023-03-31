<?php $activity = $activity->get_study_guide($id) ?>

<?php if ($activity && count($activity["definitions"]) > 3) : ?>
    <form>
        <input type="hidden" name="guide_id" value="<?= $id ?>">
        <input type="hidden" name="activity" value="Choose Word from Image">
        <input type="hidden" name="author_id" value="<?= $activity["author"] ?>">
        <b class="worksheet-heading hidden">Name: _________________________ Class: _________________ Date: _______ </b>
        <h1 style="margin-top:0;text-align:center">Choose Word from Image</h1>
        <fieldset class="grey best_results" id="best_results"></fieldset>
        <fieldset class="grey">
            <legend><?= $activity["title"] ?></legend>
            <ol>
                <h3>Instructions</h3>
                <li>Look at the image.</li>
                <li>Select the word that BEST describes it.</li>
            </ol>
            <div class="items">
                <?php foreach ($activity["definitions"] as $i => $definition) : ?>
                    <div class="item">
                        <div class="question">
                            <img class="medium-img word" src="<?= $definition["data"]["img_path"] ?>" id="<?= $definition["data"]["word"] ?>" alt="<?= $definition["data"]["word"] ?>">
                            <span class="check hidden">&#10004;</span>
                            <span class="cross hidden">&#x274C;</span>
                        </div>
                        <div class="answers">
                            <?php foreach ($definition["options"] as $option) : ?>
                                <div class="option">
                                    <div class="feedback hidden">
                                        <span style='margin-left:15px;font-size:18px;'>&#9917;</span>
                                    </div>
                                    <input class="input radio" type="radio" name="question_<?= $i ?>" value="<?= $option["word"] ?>" required>
                                    <div class="text-option">
                                        <?= $option["word"] ?>
                                    </div>
                                    <img style="cursor:pointer;margin-left:10px" class="speak" src="/scholar/media/content/speak.png" height="25" alt="Speak">
                                </div>
                            <?php endforeach ?>
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
                    <!-- <a href="/scholar/stats/?page=best_by_activity&activity=Choose Word From Image&guide_id=<?= $id ?>">See best results</a> -->
                    <a style="float:right" href="?page=Choose Word from Definition&id=<?= $id ?>">Next Activity</a>
                </div>
                <a id="print_worksheet" href="#">Print worksheet</a>
            </div>
        </fieldset>
    </form>
    <script type="module">
        import MultipleChoiceActivity from "/scholar/scripts/classes/activities/MultipleChoiceActivity.js"
        const activity = new MultipleChoiceActivity()
        activity.getMultipleAnswers()
        activity.getPDFHandler()
    </script>
<?php endif ?>