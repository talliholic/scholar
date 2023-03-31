<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Feedback</title>
</head>

<body>
    <header>
        <?php
        session_start();
        $root = $_SERVER["DOCUMENT_ROOT"];
        require "$root/scholar/models/Feedback.php";
        $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
        $feedback = new Feedback($user_id);
        $role = $feedback->get_role_by_id($user_id);
        $id = isset($_GET["id"]) ? $_GET["id"] : null;
        $page = isset($_GET["page"]) ? $_GET["page"] : null;

        match ($role) {
            "Teacher" =>  require "../account/components/teacher_menu.php",
            "Student" => require "../account/components/student_menu.php",
            default => require "../account/components/guest_menu.php"
        }
        ?>
    </header>
    <main>
        <section>
            <?php
            match ($page) {
                default => require "./components/paragraphs.php"
            }
            ?>
        </section>
    </main>
</body>

</html>