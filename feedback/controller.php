<?php
session_start();
$root = $_SERVER["DOCUMENT_ROOT"];
require "$root/scholar/models/Feedback.php";
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$feedback = new Feedback($user_id);
$post = (object) $_POST;

match ($post->request) {
    "score_paragraph" => score_paragraph($post)
};

function score_paragraph($post)
{
    $post->score = $post->grade * $post->num_definitions;
    global $feedback;

    if ($feedback->score_paragraph($post)) {
        header("Location: /scholar/feedback/?page=paragraphs");
    }
}
