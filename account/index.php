<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Your Account</title>
</head>

<body>
    <header>
        <?php
        session_start();
        $root = $_SERVER["DOCUMENT_ROOT"];
        require "$root/scholar/models/User.php";

        $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
        $user = new User();
        $role = $user->get_role_by_id($user_id);

        match ($role) {
            "Teacher" => require "./components/teacher_menu.php",
            "Student" => require "./components/student_menu.php",
            default => header("Location: /scholar/auth")
        }
        ?>
    </header>
    <section>
        <?php require "./components/card.php" ?>
    </section>
</body>

</html>