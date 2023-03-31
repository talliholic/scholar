<?php
session_start();
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
if (!$user_id) header("Location: /scholar/auth")
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../style.css">
    <title>User</title>
</head>

<body>
    <!-- menu here -->
    <header></header>
    <main class="hidden">
        <section>
            <fieldset>
                <legend></legend>
                <table id="info_div">
                    <tr>
                        <th>Name: </th>
                        <td id="name_div"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button id="password_button">Change your password</button></td>
                    </tr>
                </table>
                <form class="hidden">
                    <input type="hidden" name="role" id="role_input">
                    <input type="hidden" name="id" value="<?= $user_id ?>">
                    <table>
                        <tr>
                            <th>Password: </th>
                            <td><input type="password" name="password" placeholder="Change your password" minlength="4" required></td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button id="cancel_button">Cancel</button>
                            </td>
                            <td>
                                <input type="submit" value="Update">
                            </td>
                        </tr>
                    </table>
                </form>
            </fieldset>
        </section>
    </main>
    <script>
        const body = new FormData();
        body.append("id", "<?= $user_id ?>");
    </script>
    <script src="script.js">
    </script>
</body>

</html>