<?php $activity = $flow_chart_activity->get_by_id($id) ?>

<link rel="stylesheet" href="./components/match.css">
<fieldset id="col_a">
    <legend>Click on a description to select it.</legend>
    <?php foreach ($activity["sequences_a"] as $sequence) : ?>
        <div>
            <p class="description"><?= $sequence["description"] ?></p>
        </div>
    <?php endforeach ?>
</fieldset>

<fieldset id="col_b">
    <legend>Click on the image corresponding to the selected description.</legend>
    <form action="controller.php" method="POST">
        <?php foreach ($activity["ordered_sequences"] as $sequence) : ?>
            <div>
                <img class="image" src="<?= $sequence["img_path"] ?>" alt="Image" height="150">
            </div>
        <?php endforeach ?>
    </form>
    <button id="select">Select</button>
</fieldset>

<script src="./components/match.js"></script>

<?php
echo '<pre>';
print_r($activity);
echo '</pre>';
?>