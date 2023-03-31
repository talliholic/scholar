<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Stats</title>
</head>

<body>
    <header>
        <?php

        session_start();
        $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
        // $id = isset($_GET["id"]) ? $_GET["id"] : null;

        $root = $_SERVER["DOCUMENT_ROOT"];
        require "$root/scholar/models/Activity.php";
        $activity = new activity();
        $role = $activity->get_role_by_id($user_id);

        $page = isset($_GET["page"]) ? $_GET["page"] : null;
        match ($role) {
            "Teacher" =>  require "../account/components/teacher_menu.php",
            "Student" => require "../account/components/student_menu.php",
            default => header("Location: /scholar/auth")
        };
        ?>
        <aside>
            <?php require "./components/menu.php" ?>
        </aside>
    </header>
    <main>
        <section>
            <?php
            match ($page) {
                "personal_latest" => require "./components/personal_latest.php",
                "personal_best" => require "./components/personal_best.php",
                "best_by_activity" => require "./components/best_by_activity.php",
                "general_by_points" => require "./components/general_by_points.php",
                "general_by_activities" => require "./components/general_by_activities.php",
                default => require "./components/personal_latest.php"
            }
            ?>
        </section>
    </main>
</body>

</html>