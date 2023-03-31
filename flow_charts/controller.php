<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Flow Charts</title>
</head>

<body>
    <header>
        <?php
        require "../account/components/teacher_menu.php";
        ?>
        <!-- <aside>
            <?php require "./components/sidebar.php" ?>
        </aside> -->
    </header>
    <main>
        <section>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                session_start();
                $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
                require "../models/Flow_chart.php";
                $flow_chart = new Flow_chart($user_id);
                $post = (object)$_POST;

                match ($post->request) {
                    "create" => create($flow_chart, $post),
                    "create_sequence" => create_sequence($post, $_FILES["image"]),
                    "update_sequence" => update_sequence($post, $_FILES["image"]),
                    "update_flow_chart" => update_flow_chart($post)
                };
            } else {
                header("Location: /scholar/account");
            }

            function create($flow_chart, $post)
            {
                $id = $flow_chart->create($post);
                if ($id) {
                    $_SESSION["flow_charts"]["update_flow_chart"] = true;
                    header("Location: /scholar/flow_charts/?page=update_flow_chart&id=$id");
                }
            }

            function create_sequence($post, $file)
            {
                global $flow_chart;
                if ($flow_chart->create_sequence($post, $file)) header("Location: /scholar/flow_charts/?page=sequences&id=$post->id");
            }

            function update_sequence($post, $file)
            {
                global $flow_chart;
                if ($post->action === "Save") {
                    if ($flow_chart_id = $flow_chart->update_sequence($post, $file)) header("Location: /scholar/flow_charts/?page=sequences&id=$flow_chart_id");
                    else {
                        $_SESSION["flow_charts"]["update_sequence"] = true;
                        header("Location: /scholar/flow_charts/?page=update_sequence&id=$post->id");
                    }
                }
                if ($post->action === "Delete") if ($flow_chart_id = $flow_chart->delete_sequence($post)) header("Location: /scholar/flow_charts/?page=sequences&id=$flow_chart_id");
            }
            function update_flow_chart($post)
            {
                global $flow_chart;
                if ($flow_chart->update($post)) {
                    $_SESSION["flow_charts"] = "Flow Chart Updated";
                    header("Location: /scholar/flow_charts/?page=flow_charts");
                } else {
                    $_SESSION["flow_charts"] = "Flow Chart NOT Updated";
                    header("Location: /scholar/flow_charts/?page=edit_flow_chart&id=$post->id");
                }
            }
            ?>
        </section>
    </main>
</body>

</html>