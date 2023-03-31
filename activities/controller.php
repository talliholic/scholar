<?php
session_start();
$root = $_SERVER["DOCUMENT_ROOT"];
require "$root/scholar/models/Activity.php";
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$activity = new Activity($user_id);
$post = (object) $_POST;


match ($post->activity) {
    "Unscramble the Word" => unscramble("Word", $user_id, $post),
    "Unscramble the Definition" => unscramble("Definition", $user_id, $post),
};

function unscramble($type, $user_id, $post)
{
    global $activity;
    $post->score = get_score($post);
    $post->duration = time() - $post->start_time;
    $post->results = $_SESSION["results"];
    $_SESSION["answer"] = $post->input;
    $_SESSION["score"] = $post->score;
    $_SESSION["duration"] = $post->duration;

    $_SESSION["message"] = $activity->save_result_php($user_id, $post);
    header("Location: /scholar/activities/?page=Unscramble the $type&id=$post->guide_id");
}

function get_score($post)
{
    $_SESSION["results"] = [];

    foreach ($post->target as $i => $target) {
        if ($target === trim($post->input[$i])) array_push($_SESSION["results"], 1);
        else array_push($_SESSION["results"], 0);
    }

    return round((array_sum($_SESSION["results"]) * 100) / count($post->target));
}
