<?php
header("Content-Type: application/json");

require "User.php";
$user = new User();
$post = (object)$_POST;
match ($post->request) {
    "get_users" => $user->get_users(),
    "insert" => $user->insert($post),
    "update" => $user->update($post),
    "delete" => $user->delete($post)
};
