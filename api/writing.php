<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
session_start();
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

require "../models/Writing.php";

$post = (object)$_POST;
$writing = new Writing($user_id, $post->id);

match ($post->request) {
    "create" => $writing->create($post),
    "update" => $writing->update($post),
    "like" => $writing->like($post)
};
