<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Activities</title>
</head>

<body>
    <header>
        <?php
        session_start();
        $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
        $id = isset($_GET["id"]) ? $_GET["id"] : null;

        $root = $_SERVER["DOCUMENT_ROOT"];
        require "$root/scholar/models/Activity.php";
        $activity = new Activity();
        $role = $activity->get_role_by_id($user_id);
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
        <aside>
            <?php require "./components/summary.php" ?>
        </aside>
    </header>
    <main>
        <section>
            <?php
            match ($page) {
                "Choose Image from Word" => require "./components/choose_image_from_word.php",
                "Choose Image from Definition" => require "./components/choose_image_from_definition.php",
                "Choose Word from Image" => require "./components/choose_word_from_image.php",
                "Choose Word from Definition" => require "./components/choose_word_from_definition.php",
                "Choose Definition from Image" => require "./components/choose_definition_from_image.php",
                "Choose Definition from Word" => require "./components/choose_definition_from_word.php",
                "Review" => require "./components/review.php",
                "Type the Word" => require "./prompt/type_the_word.php",
                "Unscramble the Word" => require "./prompt/unscramble_the_word.php",
                "Unscramble the Definition" => require "./prompt/unscramble_the_definition.php",
                "Type the Definition" => require "./prompt/type_the_definition.php",
                "Paragraph" => require "./components/paragraph.php",
                default => require "./components/activities.php"
            }
            ?>
        </section>
    </main>
</body>

</html>