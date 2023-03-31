<?php $results = $classroom->get_results($id) ?>
<fieldset class="grey">
    <legend><?= $classroom->read_by_id($id)["name"] ?></legend>

    <?php foreach ($results as $result) : ?>
        <details>
            <summary style="font-weight:bold"><?= $result["taken_by"] ?></summary>
            <?php foreach ($result["results"] as $result) : ?>
                <details style="text-indent:20px">
                    <summary style="text-decoration:underline"><?= $result[0]["study_guide_title"] ?></summary>
                    <?php foreach ($result as $score) : ?>
                        <div style="text-indent:40px;font-style:italic">
                            scored <?= $score["score"] ?> in <?= $score["duration"] ?> seconds
                            <?php if ($score["date_taken"] < 2000000000) : ?>
                                on <?= date("d/m/y", $score["date_taken"]) ?>
                            <?php endif ?>
                        </div>
                    <?php endforeach ?>
                </details>
            <?php endforeach ?>
        </details>
    <?php endforeach ?>

</fieldset>