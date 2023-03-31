<?php
require "Db.php";
class Study_guide extends Db
{
    private $author_id;

    function __construct($user_id)
    {
        parent::__construct();
        //AUTH
        $this->author_id = $this->get_author_id($user_id);
    }

    public function create($post)
    {
        if ($user = $this->select_row("verified", "users", "WHERE id = :id", ["id" => $this->author_id])) {
            if ($user["verified"] === 1) {

                $array = ["title" => $post->title, "subject" => $post->subject, "author" => $this->author_id, "grade_level" => $post->grade_level];

                if ($id = $this->insert_row_id("study_guides", "(title, subject, author, grade_level)", "(:title, :subject, :author, :grade_level)", $array)) echo json_encode(["id" => $id, "success" => true, "msg" => "Study Guide created. Please add definitions.", "reason" => "Success"]);
                else $this->fail_msg();
            } else {
                echo json_encode(["reason" => "Not verified", "success" => false, "msg" => "User NOT verified. Write an email to ivan.perez@gcb.co asking for permission to upload guides."]);
            }
        };
    }

    private function create_definition($post, $guide_id, $img_path)
    {
        $array = [
            "study_guide" => $guide_id,
            "author" => $this->author_id,
            "word" => $post->word,
            "definition" => $post->definition,
            "img_path" => $img_path
        ];
        if ($this->insert_row("definitions", "(word, definition, img_path, study_guide, author)", "(:word, :definition, :img_path, :study_guide, :author)", $array)) return true;
        else return false;
    }

    public function delete($post)
    {
        $results = [];
        $definitions = $this->read_definitions($post->id);

        $array = [
            "id" => $post->id,
            "author" => $this->author_id
        ];

        foreach ($definitions as $definition) {
            if ($this->delete_definition((object)$definition)) array_push($results, true);
            else array_push($results, false);
        }

        $guide_deleted = $this->delete_row("study_guides", "WHERE id = :id AND author = :author", $array) === 1;

        if ($guide_deleted) {
            echo json_encode(["success" => true, "msg" => "Study guide and " . count($definitions) . " definitions DELETED"]);
        } else {
            echo json_encode(["success" => false, "msg" => "Study guide COULD NOT BE deleted"]);
        }
    }

    public function soft_delete($post)
    {
        if ($this->update_row("study_guides", "active = :active", "WHERE id = :id", ["id" => $post->id, "active" => 0]) === 1) $this->success_msg();
        else $this->fail_msg();
    }

    public function delete_definition_api($post)
    {
        if ($this->delete_definition($post)) $this->success_msg();
        else $this->fail_msg();
    }

    private function delete_definition($post)
    {
        $array = [
            "id" => $post->id,
            "author" => $this->author_id
        ];

        if ($this->delete_row("definitions", "WHERE id = :id AND author = :author", $array) === 1) {
            return true;
        } else {
            return false;
        }
    }

    public function definition_field_exists($post)
    {
        if ($this->select_row("word", "definitions", "WHERE $post->column = :field AND author = :author AND study_guide = :study_guide", [
            "field" => $post->field,
            "author" => $this->author_id,
            "study_guide" => $post->guide_id
        ])) $this->success_msg();
        else $this->fail_msg();
    }

    public function read_all()
    {
        return $this->select_rows("id, title, subject, grade_level", "study_guides", "WHERE author = :author_id and active = :active ORDER BY id DESC", ["author_id" => $this->author_id, "active" => 1]);
    }

    public function read_by_id($id)
    {
        if ($study_guide = $this->select_row("title, grade_level, subject", "study_guides", "WHERE author = :author_id AND id = :id", ["author_id" => $this->author_id, "id" => $id])) return $study_guide;
        else return null;
    }

    public function read_image($guide_id)
    {
        $definitions = $this->read_definitions($guide_id);
        shuffle($definitions);

        if (!$definitions) return "/scholar/media/default_images/default.png";
        return $definitions[0]["img_path"];
    }

    public function read_definition($id)
    {
        $array = [
            "author" => $this->author_id,
            "id" => $id,
        ];
        if ($definition = $this->select_row("id, word, definition, img_path, study_guide", "definitions", "WHERE author = :author AND id = :id", $array)) return $definition;
        else return null;
    }

    public function read_definitions($guide_id)
    {
        $array = [
            "author" => $this->author_id,
            "study_guide" => $guide_id,
        ];
        if ($definitions = $this->select_rows("id, word, definition, img_path", "definitions", "WHERE author = :author AND study_guide = :study_guide", $array)) return $definitions;
        else return [];
    }

    private function upload_image($file)
    {
        $raw_file_name = explode(".", $file["name"]);
        $extension = end($raw_file_name);
        $allowed_extensions = ["png", "jpeg", "jpg", "jfif"];

        if (in_array($extension, $allowed_extensions)) {
            if ($file["error"] === 0) {
                if ($file["size"] < 500000) {
                    $root = $_SERVER["DOCUMENT_ROOT"];
                    $new_name = $this->author_id . "_" . $raw_file_name[0] . "_ " . time();
                    $destination = "$root/scholar/media/uploads/images/definitions/" .  $new_name . "." . $extension;
                    move_uploaded_file($file["tmp_name"], $destination);
                    return "/scholar/media/uploads/images/definitions/" .  $new_name . "." . $extension;
                }
            }
        }
        return false;
    }

    public function update($post, $file = "")
    {
        $db_saves = 0;
        $array = [
            "id" => $post->id,
            "title" => $post->title,
            "grade_level" => $post->grade_level,
            "subject" => $post->subject,
            "author" => $this->author_id
        ];

        if ($this->update_row("study_guides", "id = :id, title = :title, grade_level = :grade_level, subject = :subject", "WHERE id = :id AND author = :author", $array) < 2) {
            $db_saves++;
            if ($img_path = $this->upload_image($file)) if ($this->create_definition($post, $array["id"], $img_path))  $db_saves++;
        }
        if ($db_saves === 0) echo json_encode(["success" => false, "msg" => "Update failed."]);
        if ($db_saves === 1) echo json_encode(["success" => true, "msg" => "Study guide saved but definition NOT added.", "id" => $array["id"]]);
        if ($db_saves === 2) echo json_encode(["success" => true, "msg" => "Study guide saved and definition added.", "id" => $array["id"]]);
    }

    public function update_definition($post, $file)
    {
        if (!$file["name"]) {
            if ($this->update_row(
                "definitions",
                "word = :word, definition = :definition",
                "WHERE id = :id AND author = :author_id",
                [
                    "word" => $post->word,
                    "definition" => $post->definition,
                    "id" => $post->definition_id,
                    "author_id" => $this->author_id
                ]
            )) echo json_encode([
                "success" => true,
                "id" => $post->guide_id,
                "msg" => "Definition updated without modifying its image"
            ]);
            else echo json_encode([
                "success" => false,
                "msg" => "Definition NOR image updated"
            ]);
        } else {
            if ($img_path = $this->upload_image($file)) {
                if ($this->update_row(
                    "definitions",
                    "word = :word, definition = :definition, img_path = :img_path",
                    "WHERE id = :id AND author = :author_id",
                    [
                        "img_path" => $img_path,
                        "word" => $post->word,
                        "definition" => $post->definition,
                        "id" => $post->definition_id,
                        "author_id" => $this->author_id
                    ]
                )) {
                    echo json_encode([
                        "success" => true,
                        "id" => $post->guide_id,
                        "msg" => "Definition and image updated"
                    ]);
                } else {
                    unlink($img_path);
                    echo json_encode([
                        "success" => false,
                        "msg" => "Update failed."
                    ]);
                }
            }
        }
    }
}
