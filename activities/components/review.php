<?php $activity = $activity->get_study_guide($id) ?>

<?php if ($activity && count($activity["definitions"]) > 3) : ?>
    <form>
        <b class="worksheet-heading hidden">Name: _________________________ Class: _________________ Date: _______ </b>
        <h1 style="margin-top:0;text-align:center">Review the vocabulary</h1>
        <fieldset class="grey">
            <legend><?= $activity["title"] ?></legend>
            <ol>
                <h3>Instructions</h3>
                <li>Look at the image.</li>
                <li>Read the word and definition.</li>
                <li>Click on do the activities</li>
            </ol>
            <div class="items">
                <?php foreach ($activity["definitions"] as $i => $definition) : ?>
                    <div class="item">
                        <div class="question">
                            <img class="medium-img word" src="<?= $definition["data"]["img_path"] ?>" class="word" id="<?= $definition["data"]["word"] ?>" alt="<?= $definition["data"]["word"] ?>">
                        </div>
                        <div style="text-align:center" class="answers">
                            <div style="display:flex;flex-flow:row wrap;column-gap:10px;justify-content:center">
                                <b>
                                    <?= $definition["data"]["word"] ?>
                                </b>
                                <img style="cursor:pointer" class="speak" src="/scholar/media/content/speak.png" height="25" alt="Speak">
                            </div>
                            <div style="display:flex;flex-flow:row wrap;column-gap:10px;justify-content:center">
                                <div>
                                    <?= $definition["data"]["definition"] ?>
                                </div>
                                <img style="cursor:pointer" class="speak" src="/scholar/media/content/speak.png" height="25" alt="Speak">
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <div class="buttons">
                <a id="print_worksheet" href="#">Print worksheet</a>
                <a style="float:right" href="?page=Unscramble the Word&id=<?= $id ?>">Do Activities</a>
                <button class="hidden" id="play-again-button" disabled>Play again!</button>
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