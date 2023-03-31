<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Classrooms</title>
</head>

<body>
    <header>
        <?php
        session_start();
        $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
        $id = isset($_GET["id"]) ? $_GET["id"] : null;

        $root = $_SERVER["DOCUMENT_ROOT"];
        require "$root/scholar/models/Classroom.php";
        $classroom = new Classroom($user_id);
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
                "classrooms" => require "./components/classrooms.php",
                "create" =>  require "./components/create_form.php",
                "edit-classroom" => require "./components/edit-classroom.php",
                "students" => require "./components/students.php",
                "edit-student" => require "./components/edit-student.php",
                "results" => require "./components/results.php",
                default => require "./components/classrooms.php"
            }
            ?>
        </section>
    </main>
</body>

</html>