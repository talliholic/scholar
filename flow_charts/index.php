<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Flow Charts</title>
</head>

<body>
    <header>
        <?php
        session_start();
        $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
        $id = isset($_GET["id"]) ? $_GET["id"] : null;

        $root = $_SERVER["DOCUMENT_ROOT"];
        require "$root/scholar/models/Flow_chart.php";
        $flow_chart = new Flow_chart($user_id);
        // $guides = $study_guides->read_all();
        $page = isset($_GET["page"]) ? $_GET["page"] : null;
        require "../account/components/teacher_menu.php";
        ?>
        <aside>
            <?php require "./components/sidebar.php" ?>
        </aside>
    </header>
    <main>
        <section>
            <?php
            match ($page) {
                "create" => require "./components/create.php",
                "update_flow_chart" => require "./components/update_flow_chart.php",
                "edit_flow_chart" => require "./components/edit_flow_chart.php",
                "sequences" => require "./components/sequences.php",
                "flow_charts" => require "./components/flow_charts.php",
                "update_sequence" => require "./components/update_sequence.php",
                default => require "./components/flow_charts.php"
            }
            ?>
        </section>
    </main>
</body>

</html>