<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require "../models/User.php";

$user = new User();
$post = (object)$_POST;

match ($post->request) {
    "create" => $user->create($post),
    "field-exists" => $user->field_exists($post),
    "login" => $user->login($post),
    "logout" => $user->logout()
};

// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");