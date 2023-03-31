<?php
require "Db.php";
class Writing extends Db
{
    private $user_id;
    private $guide_id;

    public function create($post)
    {
        if ($this->insert_row("writings", "(author_id, study_guide_id, body)", "(:author_id, :study_guide_id, :body)", [
            "author_id" => $this->user_id,
            "study_guide_id" => $this->guide_id,
            "body" => $post->body
        ]) === 1) echo json_encode(["success" => true, "id" => $this->guide_id]);
        else $this->fail_msg();
    }

    function __construct($user_id, $guide_id)
    {
        parent::__construct();
        $this->user_id = $user_id;
        $this->guide_id = $guide_id;
        if (!$user_id) header("Location: /scholar/auth");
        if (!$guide_id) header("Location: /scholar/activities");
    }
    public function get_likes($object_id)
    {
        $likes = $this->select_rows("*", "likes", "WHERE object = :object AND object_id = :object_id", ["object" => "Writing", "object_id" => $object_id]);

        return count($likes);
    }

    public function get_role_by_id()
    {
        if ($user = $this->select_row("role", "users", "WHERE id = :id", ["id" => $this->user_id])) return $user["role"];
        else return "Guest";
    }

    public function get_definitions()
    {
        return $this->select_rows("word, definition, img_path", "definitions", "WHERE study_guide = :study_guide", ["study_guide" => $this->guide_id]);
    }

    private function get_study_guide_img($id)
    {
        if ($guides = $this->select_rows("*", "definitions", "WHERE study_guide = :study_guide", ["study_guide" => $id])) return $guides[rand(0, count($guides) - 1)]["img_path"];
        else return null;
    }

    public function like($post)
    {
        if (!$this->select_row("*", "likes", "WHERE object = :object AND object_id = :object_id AND liked_by = :liked_by", ["object" => "Writing", "object_id" => $post->object_id, "liked_by" => $this->user_id])) {
            if ($this->insert_row("likes", "(object, object_id, liked_by)", "(:object, :object_id, :liked_by)", ["object" => "Writing", "object_id" => $post->object_id, "liked_by" => $this->user_id]) === 1) $this->success_msg();
            else $this->fail_msg();
        } else {
            $this->fail_msg();
        }
    }

    public function read_by_guide_id()
    {
        $guides = $this->select_rows("*", "writings", "WHERE study_guide_id = :study_guide_id ORDER BY id DESC", ["study_guide_id" => $this->guide_id]);

        return array_map(function ($guide) {
            return [
                ...$guide,
                "author" => $this->read_student_name($guide["author_id"]),
                "img_path" => $this->get_study_guide_img($guide["study_guide_id"]),
                "is_owner" => $this->is_owner($guide["author_id"]),
            ];
        }, $guides);
    }

    private function is_owner($author_id)
    {
        if ($author_id === $this->user_id) return true;
        else return false;
    }

    public function read_by_id($id)
    {
        return $this->select_row("*", "writings", "WHERE id = :id", ["id" => $id]);
    }

    public function read_student_name($id)
    {
        if ($user = $this->select_row("name", "users", "WHERE id = :id", ["id" => $id]))
            return $user["name"];
        else return null;;
    }

    public function update($post)
    {
        if ($this->update_row("writings", "body = :body", "WHERE id = :id", ["body" => $post->body, "id" => $post->writing_id]) === 1) echo json_encode(["success" => true, "id" => $post->id]);
        else $this->fail_msg();
    }
}
