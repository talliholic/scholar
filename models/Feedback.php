<?php
require "config.php";
class Feedback
{
    private $conn;
    private $teacher_id;

    function __construct($teacher_id)
    {
        global $connection;
        $this->conn = $connection;
        $this->teacher_id = $teacher_id;
    }

    private function belongs_to_a_student($author_id)
    {
        $sql = "SELECT teacher_id FROM users WHERE id = '$author_id'";
        $user = $this->conn->query($sql)->fetch_assoc();

        if ($user && $user["teacher_id"] == $this->teacher_id) return true;
        return false;
    }

    public function get_role_by_id($id)
    {
        $sql = "SELECT role FROM users where id = '$id'";
        $user = $this->conn->query($sql)->fetch_assoc();

        if ($user) return $user["role"];
        return false;
    }

    private function get_student_name($id)
    {
        $sql = "SELECT name FROM users WHERE id = $id";
        $user = $this->conn->query($sql)->fetch_assoc();
        if ($user) return $user["name"];
        return false;
    }

    public function get_students_writings()
    {
        $sql = "SELECT * FROM writings WHERE checked = 0";
        $writings = $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);

        $writings = array_filter($writings, function ($writing) {
            return $this->belongs_to_a_student($writing["author_id"]);
        });

        return array_map(function ($writing) {
            $guide_id = $writing["study_guide_id"];
            return [
                ...$writing,
                "study_guide_title" => $this->get_study_guide_title($guide_id),
                "img_path" => $this->get_study_guide_image($guide_id),
                "taken_by" => $this->get_student_name($writing["author_id"]),
                "instructions" => "/scholar/writings/?page=create&id=$guide_id",
                "study_guide_author" => $this->get_study_guide_author($guide_id),
                "num_definitions" => $this->get_study_num_definitions($guide_id),
                "teacher_id" => $this->teacher_id
            ];
        }, $writings);
    }

    private function get_study_guide_author($id)
    {
        $sql = "SELECT author FROM study_guides WHERE id = '$id'";
        $study_guide = $this->conn->query($sql)->fetch_assoc();

        if ($study_guide) return $study_guide["author"];
        return false;
    }

    private function get_study_num_definitions($study_guide_id)
    {
        $sql = "SELECT id FROM definitions WHERE study_guide = '$study_guide_id'";
        $definitions = $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);

        if ($definitions) return count($definitions);
        return false;
    }

    private function get_study_guide_title($id)
    {
        $sql = "SELECT title FROM study_guides WHERE id = $id";
        $study_guide =  $this->conn->query($sql)->fetch_assoc();

        if ($study_guide) return $study_guide["title"];
        return false;
    }

    private function get_study_guide_image($id)
    {
        $sql = "SELECT img_path FROM definitions WHERE study_guide = $id";
        $definitions =  $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);

        if ($definitions) return $definitions[rand(0, count($definitions) - 1)]["img_path"];
        return false;
    }

    public function score_paragraph($post)
    {
        $date_taken = time();
        $sql = "INSERT INTO activities (guide_id, activity, date_taken, taken_by, teacher_id, author_id, score) VALUES ($post->study_guide_id, '$post->activity', $date_taken, $post->author_id, $post->teacher_id, $post->study_guide_author_id, $post->score)";

        if ($this->conn->query($sql)) {
            $insert_id = $this->conn->insert_id;
            $comment = trim($post->comment);

            $sql = "UPDATE writings SET checked = 1, comment = '$comment', grade = $post->grade, activity_id = $insert_id WHERE id = $post->id";
            if ($this->conn->query($sql)) return true;
        }

        return false;
    }
}
