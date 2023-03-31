<?php
require "Db.php";
class Flow_chart extends Db
{
    private $author_id;

    function __construct($user_id)
    {
        parent::__construct();
        $this->author_id = $this->get_author_id($user_id);
    }

    public function create($post)
    {
        if ($user = $this->select_row("verified", "users", "WHERE id = :id", ["id" => $this->author_id])) {
            if ($user["verified"] === 1) {

                $array = ["title" => trim($post->title), "subject" => $post->subject, "author" => $this->author_id, "grade_level" => $post->grade_level, "type" => $post->type];

                if ($id = $this->insert_row_id("flow_charts", "(title, subject, author, grade_level, type)", "(:title, :subject, :author, :grade_level, :type)", $array))
                    return $id;
            }
        };
        return false;
    }

    public function create_sequence($post, $file)
    {
        $img_path = $this->upload_image($file);

        $array = [
            "flow_chart_id" => $post->id,
            "teacher_id" => $this->author_id,
            "position" => $this->sequence_position($post->id) + 1,
            "description" => trim($post->description),
            "img_path" => $img_path
        ];

        if ($img_path) {
            if ($this->insert_row("sequences", "(flow_chart_id, teacher_id, img_path, position, description)", "(:flow_chart_id, :teacher_id, :img_path, :position, :description)", $array)) return true;
            else return false;
        }

        return false;
    }

    public function delete($post)
    {
        if ($this->update_row("flow_charts", "active = :active", "WHERE id = :id", ["active" => 0, "id" => $post->id])) $this->success_msg();
        else $this->fail_msg();
    }

    public function delete_sequence($post)
    {
        $flow_chart_id = $this->get_flow_chart_id($post->id);
        $num_sequences_in_chart = count($this->read_sequences($flow_chart_id));
        $sequence_position = $this->read_sequence($post->id)["position"];

        if ($this->delete_row("sequences", "WHERE id = :id", ["id" => $post->id])) {
            for ($i = 0; $i < $num_sequences_in_chart; $i++) {
                if ($i + 1 > $sequence_position) {
                    $this->update_row("sequences", "position = :new_position", "WHERE position = :position", [
                        "new_position" => $i,
                        "position" => $i + 1
                    ]);
                }
            }
            return $flow_chart_id;
        }
        return false;
    }

    private function get_flow_chart_id($sequence_id)
    {
        $sequence = $this->read_sequence($sequence_id);
        return $sequence["flow_chart_id"];
    }

    public function read()
    {
        $flow_charts = $this->select_rows("*", "flow_charts", "WHERE author = :author AND active = :active", ["author" => $this->author_id, "active" => 1]);

        return array_map(function ($chart) {
            $sequences = $this->read_sequences($chart["id"]);
            return [
                ...$chart,
                "img_path" => $sequences ? $sequences[rand(0, count($sequences) - 1)]["img_path"] : "/scholar/media/default_images/default.png"
            ];
        }, $flow_charts);
    }

    public function read_by_id($id)
    {
        $chart_sequences = $this->select_rows("*", "sequences", "WHERE flow_chart_id = :id", ["id" => $id]);
        if ($flow_chart = $this->select_row("*", "flow_charts", "WHERE author = :author_id AND id = :id", ["author_id" => $this->author_id, "id" => $id])) {
            $flow_chart["img_path"] =  $chart_sequences ? $chart_sequences[rand(0, count($chart_sequences) - 1)]["img_path"] :  "/scholar/media/default_images/default.png";
            return $flow_chart;
        } else return null;
    }

    public function read_sequence($id)
    {
        $sequence = $this->select_row("*", "sequences", "WHERE id = :id AND teacher_id = :teacher_id", ["id" => $id, "teacher_id" => $this->author_id]);

        $sequence["num_positions"] = count($this->read_sequences($sequence["flow_chart_id"]));

        return $sequence;
    }

    public function read_sequences($flow_chart_id)
    {
        if ($this->is_active($flow_chart_id)) return $this->select_rows("*", "sequences", "WHERE flow_chart_id = :flow_chart_id ORDER by position ASC", ["flow_chart_id" => $flow_chart_id]);
        else return [];
    }

    private function is_active($id)
    {
        if ($this->read_by_id($id)["active"] === 1) return true;
        else return false;
    }

    private function sequence_position($flow_chart_id)
    {
        $sequences = $this->select_rows("id", "sequences", "WHERE flow_chart_id = :flow_chart_id", ["flow_chart_id" => $flow_chart_id]);

        return count($sequences);
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
                    $new_name = $this->author_id . "_" . $raw_file_name[0] . "_" . time();
                    $destination = "$root/scholar/media/uploads/images/sequences/" .  $new_name . "." . $extension;
                    move_uploaded_file($file["tmp_name"], $destination);
                    return "/scholar/media/uploads/images/sequences/" .  $new_name . "." . $extension;
                }
            }
        }
        return false;
    }

    private function get_sequence_by_position($flow_chart_id, $position)
    {
        return $this->select_row("*", "sequences", "WHERE flow_chart_id = :flow_chart_id AND teacher_id = :teacher_id AND position = :position", [
            "flow_chart_id" => $flow_chart_id,
            "teacher_id" => $this->author_id,
            "position" => $position
        ]);
    }

    public function update($post)
    {
        if ($this->update_row("flow_charts", "title = :title, subject = :subject, grade_level = :grade_level, type = :type", "WHERE id = :id", ["title" => trim($post->title), "subject" => $post->subject, "grade_level" => $post->grade_level, "type" => $post->type, "id" => $post->id]) === 1) return true;
        else return false;
    }

    public function update_sequence($post, $file = "")
    {
        $sequence = $this->read_sequence($post->id);
        $flow_chart_id = $this->get_flow_chart_id($post->id);
        $updated = [false, false, false];

        // SWAP POSITIONS
        if ($sequence["position"] !== $post->position) {
            $sequence_b = $this->get_sequence_by_position($flow_chart_id, $post->position);
            if ($this->update_row("sequences", "position = :position", "WHERE id = :id", [
                "position" => $sequence["position"],
                "id" => $sequence_b["id"]
            ]) === 1) $updated[0] = true;
        }

        if ($img_path = $this->upload_image($file)) if ($this->update_row("sequences", "img_path = :img_path", "WHERE id = :id", [
            "img_path" => $img_path,
            "id" => $sequence["id"]
        ]) === 1) $updated[1] = true;

        if ($this->update_row("sequences", "position = :position, description = :description", "WHERE id = :id", [
            "position" => $post->position,
            "description" => trim($post->description),
            "id" => $sequence["id"]
        ]) === 1) $updated[2] = true;

        if (in_array(true, $updated)) return $flow_chart_id;
        else return false;
    }
}
