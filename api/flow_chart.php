<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
session_start();
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

require "../models/Flow_chart.php";

$flow_chart = new Flow_chart($user_id);
$post = (object)$_POST;

match ($post->request) {
    "delete" => $flow_chart->delete($post)
};
