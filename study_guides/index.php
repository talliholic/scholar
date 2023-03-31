<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Study Guides</title>
</head>

<body>
    <header>
        <?php
        session_start();
        $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
        $id = isset($_GET["id"]) ? $_GET["id"] : null;

        $root = $_SERVER["DOCUMENT_ROOT"];
        require "$root/scholar/models/Study_guide.php";
        $study_guides = new Study_guide($user_id);
        $guides = $study_guides->read_all();
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
                "make" => require "./components/create_form.php",
                "edit" => require "./components/edit-study_guide.php",
                "definitions" => require "./components/definitions.php",
                "edit-definition" => require "./components/edit-definition.php",
                default => require "./components/study_guides.php"
            }
            ?>
        </section>
    </main>
</body>

</html>