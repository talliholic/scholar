<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
session_start();
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

require "../models/Classroom.php";

$classroom = new Classroom($user_id);
$post = (object)$_POST;

match ($post->request) {
    "create" => $classroom->create($post),
    "update" => $classroom->update($post),
    "delete" => $classroom->delete($post),
    "update_student" => $classroom->update_student($post),
    "delete_student" => $classroom->delete_user_activities($post)
};
