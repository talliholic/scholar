<?php $flow_charts = $flow_chart->read() ?>
<?php if (isset($_SESSION["flow_charts"])) : ?>
    <script>
        alert("<?= $_SESSION["flow_charts"] ?>")
    </script>
<?php endif ?>
<?php unset($_SESSION["flow_charts"]) ?>

<h2 style="width:100%; background-color:lightyellow; text-align:center;margin:0 0 15px 0;"><i>Flow Charts</i></h2>

<?php foreach ($flow_charts as $flow_chart) : ?>
    <a style="text-decoration:none;color:black" href="?page=sequences&id=<?= $flow_chart["id"] ?>">
        <fieldset style="background-color:lightyellow">
            <legend><?= $flow_chart["title"] ?></legend>
            <table>
                <tr>
                    <img style="display:block;margin:auto" height="150" src="<?= $flow_chart["img_path"] ?>" alt="<?= $flow_chart["title"] ?>">
                </tr>
                <tr>
                    <th>Subject: </th>
                    <td><?= $flow_chart["subject"] ?></td>
                </tr>
                <tr>
                    <th>Grade Level: </th>
                    <td><?= $flow_chart["grade_level"] ?></td>
                </tr>
                <tr>
                    <th>Type: </th>
                    <td><?= $flow_chart["type"] ?></td>
                </tr>
            </table>
        </fieldset>
    </a>
<?php endforeach ?>