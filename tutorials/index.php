<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Tutorials</title>
</head>

<body>
    <header>
        <?php
        session_start();
        $root = $_SERVER["DOCUMENT_ROOT"];
        require "$root/scholar/models/User.php";

        $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
        $page = isset($_GET["page"]) ? $_GET["page"] : null;
        $user = new User();
        $role = $user->get_role_by_id($user_id);

        match ($role) {
            "Teacher" => require "../account/components/teacher_menu.php",
            "Student" => require "../account/components/student_menu.php",
            default => header("Location: /scholar/auth")
        }
        ?>
    </header>
    <section>
        <?php
        match ($page) {
            "teachers" => require "./components/teachers.php",
            "students" => require "./components/students.php",
            default => require "./components/teachers.php"
        }
        ?>
    </section>
</body>

</html>