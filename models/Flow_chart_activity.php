<?php
require "config.php";
class Flow_chart_Activity
{
    private $conn;
    private $user_id;

    function __construct($user_id)
    {
        global $connection;
        $this->conn = $connection;
        $this->user_id = $user_id;
    }

    public function get_by_grade_and_subject($grade, $subject)
    {
        $sql = "SELECT * FROM flow_charts WHERE subject = '$subject' AND grade_level = '$grade'";
        $flow_charts = $this->conn->query($sql);
        $flow_charts = $flow_charts->fetch_all(MYSQLI_ASSOC);
        return array_map(function ($flow_chart) {
            return [
                ...$flow_chart,
                "sequences" => $this->get_sequences_by_flow_chart($flow_chart["id"])
            ];
        }, $flow_charts);
    }

    public function get_by_id($id)
    {
        $sql = "SELECT * FROM flow_charts WHERE id = $id AND active = 1";
        $flow_chart = $this->conn->query($sql);
        $flow_chart = $flow_chart->fetch_assoc();
        if ($flow_chart) {
            $flow_chart["ordered_sequences"] = $this->get_sequences_by_flow_chart($id);
            $flow_chart["sequences_a"] = $this->get_shuffled_sequences($id);
            $flow_chart["sequences_b"] = $this->get_shuffled_sequences($id);
            $flow_chart["sequences"] = $this->get_mapped_sequences($flow_chart["id"]);
            shuffle($flow_chart["sequences"]);
            return $flow_chart;
        }
        return null;
    }

    private function get_shuffled_sequences($id)
    {
        $sequences =   $this->get_sequences_by_flow_chart($id);
        shuffle($sequences);
        return $sequences;
    }

    public function get_role_by_id($id)
    {
        $sql = "SELECT role FROM users where id = '$id'";
        $user = $this->conn->query($sql)->fetch_assoc();

        if ($user) return $user["role"];
        return false;
    }

    public function get_sequences_by_flow_chart($id)
    {
        $sql = "SELECT * FROM sequences WHERE flow_chart_id = '$id' ORDER BY position ASC";
        $sequences = $this->conn->query($sql);
        return $sequences->fetch_all(MYSQLI_ASSOC);
    }

    private function get_options($item, $key, $group)
    {
        $other = $this->get_other($item, $key, $group);
        shuffle($other);

        $options = [
            $other[0],
            $item,
        ];
        shuffle($options);

        return $options;
    }

    private function get_options_with_values($item, $key, $group)
    {
        $other = $this->get_other_with_values($item, $key, $group);
        shuffle($other);

        $options = [
            $other[0],
            [
                "position" => $item["position"],
                "description" => $item["description"],
                "img_path" => $item["img_path"]
            ],
        ];
        shuffle($options);

        return $options;
    }

    private function get_other($item, $key, $group)
    {
        $group_array = [];

        foreach ($group as $element) array_push($group_array, $element["$key"]);

        return array_filter($group_array, function ($elem) use ($item) {
            return $elem !== $item;
        });
    }

    private function get_other_with_values($item, $key, $group)
    {
        $group_array = [];

        foreach ($group as $element) array_push($group_array, $element);

        $group_array =  array_filter($group_array, function ($elem) use ($item) {
            return $elem !== $item;
        });

        return array_map(function ($elem) {
            return [
                "position" => $elem["position"],
                "description" => $elem["description"],
                "img_path" => $elem["img_path"]
            ];
        }, $group_array);
    }

    private function get_questions($id)
    {
        $skill_questions = ["First" => "What should you do first?", "Last" => "What should you do last?"];
        $event_questions = ["First" => "What happened first?", "Last" => "What happened last?"];
        $cycle_questions = ["First" => "What happens first?", "Last" => "What happens last?"];

        $sql = "SELECT type FROM flow_charts WHERE id = $id";
        $flow_chart = $this->conn->query($sql)->fetch_assoc();

        if ($flow_chart) {
            $type =  $flow_chart["type"];
            if ($type == "Skill") return  $skill_questions;
            if ($type == "Events") return  $event_questions;
            if ($type == "Cycle") return  $cycle_questions;
        }
        return null;
    }

    public function get_mapped_sequences($id)
    {
        $sequences =  $this->get_sequences_by_flow_chart($id);

        return array_map(function ($sequence) use ($sequences, $id) {
            $options_with_values = $this->get_options_with_values($sequence, "position", $sequences);

            $sorted_options = $options_with_values;
            usort($sorted_options, fn ($a, $b) => $a["position"] <=> $b["position"]);

            return [
                ...$sequence,
                "description_options" => $this->get_options($sequence["description"], "description", $sequences),
                "img_path_options" => $this->get_options($sequence["img_path"], "img_path", $sequences),
                "position_options" => $options_with_values,
                "first" => [...$sorted_options[0], "question" => $this->get_questions($id)["First"]],
                "last" => [...$sorted_options[1], "question" => $this->get_questions($id)["Last"]]
            ];
        }, $sequences);
    }
}
