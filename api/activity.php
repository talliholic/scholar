<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
session_start();
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

require "../models/Activity.php";

$activity = new Activity();
$post = (object)$_POST;

match ($post->request) {
    "save-result" => $activity->save_result($user_id, $post),
    "get_scores_by_activity" => $activity->get_scores_by_activity($user_id, $post)
};
