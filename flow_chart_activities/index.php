<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="style.css">
    <title>Flow Chart Activities</title>
</head>

<body>
    <header>
        <?php
        session_start();
        $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
        $id = isset($_GET["id"]) ? $_GET["id"] : null;
        $root = $_SERVER["DOCUMENT_ROOT"];
        require "$root/scholar/models/Flow_chart_activity.php";
        $flow_chart_activity = new Flow_chart_activity($user_id);
        $role = $flow_chart_activity->get_role_by_id($user_id);
        $page = isset($_GET["page"]) ? $_GET["page"] : null;
        match ($role) {
            "Teacher" =>  require "../account/components/teacher_menu.php",
            "Student" => require "../account/components/student_menu.php",
            default => require "../account/components/guest_menu.php"
        }

        ?>
        <aside>
            <?php require "./components/submenu.php" ?>
        </aside>
    </header>
    <main>
        <section>
            <?php
            match ($page) {
                "Activities" => require "./components/activities.php",
                "Firsts" => require "./components/firsts.php",
                "Lasts" => require "./components/lasts.php",
                "Match Descriptions" => require "./components/match_descriptions.php",
                "Match Images" => require "./components/match_images.php",
                "Match Image" => require "./components/match_image.php",
                default => require "./components/activities.php"
            }
            ?>
        </section>
    </main>
</body>

</html>