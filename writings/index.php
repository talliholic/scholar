<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Writings</title>
</head>

<body>
    <header>
        <?php
        session_start();
        $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
        $id = isset($_GET["id"]) ? $_GET["id"] : null;

        $root = $_SERVER["DOCUMENT_ROOT"];
        require "$root/scholar/models/Writing.php";
        $writing = new Writing($user_id, $id);
        $page = isset($_GET["page"]) ? $_GET["page"] : null;
        $role = $writing->get_role_by_id();

        match ($role) {
            "Teacher" =>  require "../account/components/teacher_menu.php",
            "Student" => require "../account/components/student_menu.php",
            default => require "../account/components/guest_menu.php"
        }
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
                "update" => require "./components/update.php",
                "by_activity" => require "./components/by_activity.php",
                default => require "./components/by_activity.php"
            }
            ?>
        </section>
    </main>
</body>

</html>