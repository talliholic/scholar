<?php
session_start();
require "../models/config.php";
date_default_timezone_set("America/Bogota");
$post = (object) $_POST;

match ($post->request) {
    "Match Descriptions" => match_($post, "Match Descriptions"),
    "Firsts" => firsts($post),
    "Lasts" => firsts($post),
    "Match Images" => match_($post, "Match Images")
};

function match_images($post)
{
    echo '<pre>';
    print_r($post);
    echo '</pre>';
}

function insert_activity($post)
{
    global $connection;
    $sql = "SELECT teacher_id FROM users WHERE id = '$post->user_id'";
    $user = $connection->query($sql)->fetch_assoc();
    $user = (object) $user;
    if ($user->teacher_id != 0) {
        $sql = "INSERT INTO activities (activity, date_taken, taken_by, teacher_id, author_id, score, duration, flow_chart_id) VALUES('$post->request', '$post->date_taken', '$post->user_id', '$user->teacher_id', '$post->author', '$post->score', '$post->duration', '$post->flow_chart_id')";
    }

    if ($connection->query($sql)) return true;
    return false;
}


function match_($post, $type)
{
    $post->score = 0;
    foreach ($post->target as $i => $target) {
        $_SESSION["answers"][$i] = $post->input[$i];
        if ($target === $post->input[$i]) {
            $post->score += 100;
            $_SESSION["feedback"][$i] = true;
        } else
            $_SESSION["feedback"][$i] = false;
    }

    $post->score = round($post->score / count($post->target));
    $_SESSION["score"] = $post->score;
    $post->duration = time() - $post->date_taken;
    $post = (object) $post;

    echo '<pre>';
    print_r($post);
    echo '</pre>';

    // insert_activity($post);
    // header("Location: /scholar/flow_chart_activities/?page=$type&id=$post->flow_chart_id");
}

function firsts($post)
{
    $post = (array) $post;
    $post["score"] = 0;
    foreach ($post["target"] as $i => $target) {
        if ($target == $post["question_$i"]) {
            $post["score"] += 100;
        }
    }
    $post["score"] = round($post["score"] / count($post["target"]));
    $_SESSION["score"] = $post["score"];
    $post["duration"] = time() - $post["date_taken"];
    $post = (object) $post;

    insert_activity($post);
    header("Location: /scholar/flow_chart_activities/?page=$post->request&id=$post->flow_chart_id");
}
