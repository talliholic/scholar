<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
session_start();
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

require "../models/Study_guide.php";

$study_guide = new Study_guide($user_id);
$post = (object)$_POST;

match ($post->request) {
    "create" => $study_guide->create($post),
    "update" => $study_guide->update($post, $_FILES["image"]),
    "update_definition" => $study_guide->update_definition($post, $_FILES["image"]),
    "field_exists" => $study_guide->definition_field_exists($post),
    "delete_definition" => $study_guide->delete_definition_api($post),
    "soft_delete" => $study_guide->soft_delete($post)
};
