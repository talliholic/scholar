<?php $activity = $flow_chart_activity->get_by_id($id); ?>
<div id="container">
    <fieldset id="first_set">
        <legend>Grab an image and place it over the correct description</legend>
        <?php foreach ($activity["sequences_a"] as $i => $sequence) : ?>
            <div id="fill_container">
                <img data="<?= $sequence["description"] ?>" class="fill" draggable="true" id="fill_<?= $i ?>" src="<?= $sequence["img_path"] ?>" alt="Sequence">
            </div>
            <p class="hidden_p"></p>
        <?php endforeach ?>
    </fieldset>
    <fieldset id="drop_set">
        <legend>Place the image over the correct description</legend>
        <form method="POST" action="controller.php">
            <input type="hidden" name="date_taken" value="<?= time() ?>">
            <input type="hidden" name="request" value="Match Images">
            <input type="hidden" name="flow_chart_id" value="<?= $id ?>">
            <input type="hidden" name="author" value="<?= $activity["author"] ?>">
            <input type="hidden" name="user_id" value="<?= $user_id ? $user_id : 0 ?>">
            <?php foreach ($activity["ordered_sequences"] as $sequence) : ?>
                <input type="hidden" name="target[]" value="<?= $sequence["description"] ?>">
                <div class="empty_container">
                    <input type="hidden" name="answers_imgs[]" value="<?= $sequence["img_path"] ?>">
                    <input type="hidden" name="input[]">
                    <img class="empty" src="/scholar/media/default_images/default.png" alt="Default">
                </div>
                <span class="check_feedback"></span>
                <p><?= $sequence["description"] ?></p>
            <?php endforeach ?>
            <?php if (!isset($_SESSION["score"])) : ?>
                <input style="float:right;font-size:18px" id="done" type="submit" value="Done">
            <?php endif ?>
            <a id="try_again" href="/scholar/flow_chart_activities/?page=Match Images&id=<?= $id ?>">Try again</a>
        </form>
        <?php unset($_SESSION["answers"]) ?>
        <?php unset($_SESSION["feedback"]) ?>
        <?php unset($_SESSION["score"]) ?>
    </fieldset>
</div>
<script src="./components/match_images.js"></script>