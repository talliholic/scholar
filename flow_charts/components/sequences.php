<?php
$sequences = $flow_chart->read_sequences($id);
$flow_chart = $flow_chart->read_by_id($id);
if (!$flow_chart) header("Location: ?page=flow_charts");
?>
<h2 style="width:100%; background-color:lightyellow; text-align:center;margin:0 0 15px 0;"><i><?= $flow_chart["title"] ?></i></h2>
<?php foreach ($sequences as $sequence) : ?>
    <img src="/scholar/media/content/flow_arrow.jpg" height="100" alt="arrow">
    <a style="text-decoration:none;color:black" href="?page=update_sequence&id=<?= $sequence["id"] ?>">
        <fieldset style="background-color:lightyellow">
            <legend>
                <?= $sequence["position"] ?>
            </legend>
            <img src="<?= $sequence["img_path"] ?>" alt="sequence" height="150">
            <p style="text-align:center"><?= $sequence["description"] ?></p>
        </fieldset>
    </a>
<?php endforeach ?>