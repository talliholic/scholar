<?php
require "Db.php";
class Activity extends Db
{
    private function calculate_long_ago($seconds)
    {
        if ($seconds > 2000000000) return "";

        $ago = time() - $seconds;
        $min = floor($ago / 60);
        $hours = floor($ago / 3600);
        $days = floor($ago / 86400);
        $months = floor($ago / 2592000);
        $years = floor($ago / 31536000);
        // $sec = fmod($seconds, 60);

        if ($ago === 1) return "$ago second ago";
        if ($ago < 60) return "$ago seconds ago";
        if ($ago >= 60 && $ago < 120) return "$min minute ago";
        if ($ago >= 120 && $ago < 3600) return "$min minutes ago";
        if ($ago >= 3600 && $ago < 7200) return "$hours hour ago";
        if ($ago >= 7200 && $ago < 86400) return "$hours hours ago";
        if ($ago >= 86400 && $ago < 172800) return "$days day ago";
        if ($ago >= 172800 && $ago < 2592000) return "$days days ago";
        if ($ago >= 2592000 && $ago < 5184000) return "$months month ago";
        if ($ago >= 5184000 && $ago < 31536000) return "$months months ago";
        if ($ago >= 31536000 && $ago < 63072000) return "$years year ago";
        if ($ago >= 63072000) return "$years years ago";
    }

    private function get_definitions($guide_id)
    {
        return $this->select_rows("*", "definitions", "WHERE study_guide = :guide_id", ["guide_id" => $guide_id]);
    }

    public function get_best_scores($user_id)
    {
        $activities = $this->select_rows("guide_id, activity, date_taken, score, duration", "activities", "WHERE taken_by = :taken_by ORDER BY score DESC, duration ASC LIMIT 10", [
            "taken_by" => $user_id
        ]);

        return $this->map_personal_activities($activities);
    }

    // public function get_best_scores_by_guide($id, $activity)
    // {
    //     $activities = $this->select_rows("taken_by, activity, guide_id, MAX(score) AS score, MIN(duration) AS duration", "activities", "WHERE guide_id = :guide_id AND activity = :activity GROUP BY taken_by ORDER BY score DESC, duration ASC LIMIT 10", [
    //         "guide_id" => $id,
    //         "activity" => $activity
    //     ]);

    //     return $this->map_activities($activities);
    // }

    public function get_latest_scores($user_id)
    {
        $activities = $this->select_rows("guide_id, activity, date_taken, score, duration", "activities", "WHERE taken_by = :taken_by ORDER BY date_taken DESC LIMIT 10", [
            "taken_by" => $user_id
        ]);

        return $this->map_personal_activities($activities);
    }

    public function get_role_by_id($id)
    {
        if ($user = $this->select_row("role", "users", "WHERE id = :id", ["id" => $id])) return $user["role"];
        else return "Guest";
    }

    public function get_scores_by_activity($user_id, $post)
    {
        $scores = $this->select_rows("date_taken, score, duration", "activities", "WHERE taken_by = :taken_by AND guide_id = :guide_id AND activity = :activity_name ORDER BY score DESC, duration ASC LIMIT 5", [
            "taken_by" => $user_id,
            "guide_id" => $post->guide_id,
            "activity_name" => $post->activity_name
        ]);

        $scores = array_map(function ($score) {
            return [
                ...$score,
                "date_taken" => $this->calculate_long_ago($score["date_taken"])
            ];
        }, $scores);

        if ($scores) echo json_encode(["success" => true, "scores" => $scores]);
        else $this->fail_msg();
    }

    public function get_subjects_by_grade_level($grade_level)
    {
        $study_guides = $this->select_rows("subject", "study_guides", "WHERE grade_level = :grade_level GROUP BY subject", ["grade_level" => $grade_level]);

        return array_map(function ($guide) {
            $img_path = "";
            match ($guide["subject"]) {
                "HISTORY" => $img_path = "/scholar/media/content/history.png",
                "ENGLISH" => $img_path = "/scholar/media/content/language.png",
                "SPANISH" => $img_path = "/scholar/media/content/spanish.png",
                "SOCIAL STUDIES" => $img_path = "/scholar/media/content/social_studies.png",
                "SCIENCE" => $img_path = "/scholar/media/content/science.png",
                "PE" => $img_path = "/scholar/media/content/pe.jpg",
                "MUSIC" => $img_path = "/scholar/media/content/music.jpg",
                "MATH" => $img_path = "/scholar/media/content/math.jpg",
                "ICT" => $img_path = "/scholar/media/content/ict.jpg",
                default => $img_path = ""
            };
            return ["subject" => $guide["subject"], "img_path" => $img_path];
        }, $study_guides);
    }

    public function get_performance($taken_by, $role)
    {
        if ($role === "Student") {
            $different_activities = $this->select_distinct("activities", "guide_id, activity", "WHERE taken_by = :taken_by", ["taken_by" => $taken_by]);

            if (count($different_activities) > 0) {
                $performance = $this->select_row("SUM(score) as total_points, ROUND(AVG(score), 0) as average, COUNT(score) as activities, SUM(duration) as total_duration", "activities", "WHERE taken_by = :taken_by", ["taken_by" => $taken_by]);

                $performance["total_hours"] = round($performance["total_duration"] / 3600, 1);
                $performance["different_activities"] = count($different_activities);
                $performance["average_duration"] = round($performance["total_duration"] / $performance["activities"], 0);

                return $performance;
            }
        }
    }

    public function get_general_standings($user_id, $sort_by)
    {
        $classmates = $this->get_student_classmates($user_id);
        $scores = [];

        foreach ($classmates as $classmate) {
            if ($result = $this->select_row("taken_by, SUM(score) as score, SUM(duration) as duration, COUNT(score) as activities", "activities", "WHERE taken_by = :taken_by", ["taken_by" => $classmate["id"]])) {
                array_push($scores, $result);
            }
        }

        $score = array_column($scores, "score");
        $activities = array_column($scores, "activities");

        match ($sort_by) {
            "score" => array_multisort($score, SORT_DESC, $scores),
            "activities" => array_multisort($activities, SORT_DESC, $scores)
        };

        return array_map(function ($student) {
            return [
                ...$student,
                "taken_by" => $this->read_student_name($student["taken_by"])
            ];
        }, $scores);
    }

    private function get_student_classmates($id)
    {
        $student = $this->select_row("classroom_id", "users", "WHERE id = :id", ["id" => $id]);

        return $this->select_rows("id", "users", "WHERE classroom_id = :classroom_id", ["classroom_id" => $student["classroom_id"]]);
    }

    //GETS STUDY GUIDE WITH ITS RELATED DEFINITIONS AND MAPPED DATA
    public function get_study_guide($id)
    {
        if ($study_guide = $this->select_row("title, subject, grade_level, author", "study_guides", "WHERE id = :id AND active = :active", ["id" => $id, "active" => 1]))
            return [
                ...$study_guide,
                "definitions" => $this->map_guide_definitions($id)
            ];
    }

    private function get_study_guide_title($id)
    {
        if ($guide = $this->select_row("title", "study_guides", "WHERE id = :id", [
            "id" => $id
        ])) return $guide["title"];
        else return null;
    }

    public function get_study_guides()
    {
        $study_guides = $this->select_rows("*", "study_guides", "WHERE active = :active", ["active" => 1]);
        return array_map(function ($guide) {
            $definitions = $this->get_definitions($guide["id"]);
            $random_num_for_definitions = rand(0, count($definitions) - 1);
            return [
                "complete" => count($definitions) > 3 ? true : false,
                "data" => $guide,
                "img" => count($definitions) > 3 ? $definitions[$random_num_for_definitions]["img_path"] : ""
            ];
        }, $study_guides);
    }

    public function get_study_guide_personal_performance($guide_id, $taken_by)
    {
        $distinct_activities = $this->select_rows("guide_id, activity, MAX(score) as score", "activities", "WHERE guide_id = :guide_id AND taken_by = :taken_by GROUP BY activity", ["guide_id" => $guide_id, "taken_by" => $taken_by]);

        $distinct_activities = array_map(function ($activity) {
            return [
                ...$activity,
                "link" => "?page=" . $activity["activity"] . "&id=" . $activity["guide_id"]
            ];
        }, $distinct_activities);

        return array_map(function ($activity) use ($distinct_activities) {
            return [
                "completed" => count($distinct_activities) . "/8",
                "best_results" => $activity
            ];
        }, $distinct_activities);
    }

    public function is_activity_done($taken_by, $type, $guide_id)
    {
        if ($this->select_row("*", "activities", "WHERE taken_by = :taken_by AND activity = :type AND guide_id = :guide_id", ["taken_by" => $taken_by, "type" => $type, "guide_id" => $guide_id])) return true;
        else return false;
    }

    public function is_paragraph_done($user_id, $guide_id)
    {
        if ($this->select_row("*", "writings", "WHERE author_id = :author_id AND study_guide_id = :guide_id", ["author_id" => $user_id, "guide_id" => $guide_id])) return true;
        else return false;
    }

    private function map_activities($activities)
    {
        return array_map(function ($activity) {
            $guide_id = $activity["guide_id"];
            $page = "";
            match ($activity["activity"]) {
                "Choose Image from Word" => $page = "choose_image_from_word",
                "Choose Image from Definition" => $page = "choose_image_from_definition",
                "Choose Word from Image" => $page = "choose_word_from_image",
                "Choose Word from Definition" => $page = "choose_word_from_definition",
                "Choose Definition from Word" => $page = "choose_definition_from_word",
                "Choose Definition from Image" => $page = "choose_definition_from_image",
                default => $page = ""
            };
            $activity_link = "/scholar/activities/?page=$page&id=$guide_id";
            return [
                ...$activity,
                "student" => $this->read_student_name($activity["taken_by"]),
                "guide_title" => $this->get_study_guide_title($activity["guide_id"]),
                "link" => $activity_link
            ];
        }, $activities);
    }

    private function map_personal_activities($activities)
    {
        return array_map(function ($activity) {
            $guide_id = $activity["guide_id"];
            $page = $activity["activity"];
            $activity_link = "/scholar/activities/?page=$page&id=$guide_id";
            return [
                ...$activity,
                "guide_title" => $this->get_study_guide_title($activity["guide_id"]),
                "link" => $activity_link,
                "date_taken" =>  $this->calculate_long_ago($activity["date_taken"])
            ];
        }, $activities);
    }
    private function scramble_array($type, $string)
    {
        if ($type === "Word") {
            $word = str_split($string);
            shuffle($word);
            return $word;
        }
        if ($type === "Definition") {
            $definition = explode(" ", $string);
            shuffle($definition);
            return $definition;
        }
    }

    //GETS ONLY A DEFINITIONS ARRAY WITH MAPPED DATA
    private function map_guide_definitions($guide_id)
    {
        $definitions = $this->get_definitions($guide_id);
        $mapped_definitions = array_map(function ($definition) {
            return [
                "word" => $definition["word"],
                "definition" => $definition["definition"],
                "img_path" => $definition["img_path"]
            ];
        }, $definitions);

        return array_map(function ($definition) use ($mapped_definitions) {
            //MAKE ARRAY TO DIFFERENTIATE
            $options = array_filter($mapped_definitions, function ($def) use ($definition) {
                return $def !== $definition;
            });
            shuffle($options);
            $mapped_options = [];
            //CUT ARRAY TO 4 OPTIONS INCLUDING ORIGINAL DEFINITION
            array_unshift($mapped_options, $definition);
            for ($i = 1; $i < 4; $i++) $mapped_options[$i] = $options[$i - 1];
            shuffle($mapped_options);
            return [
                "data" => [
                    "word" => $definition["word"],
                    "word_scramble" => $this->scramble_array("Word", $definition["word"]),
                    "definition" => $definition["definition"],
                    "definition_scramble" => $this->scramble_array("Definition", $definition["definition"]),
                    "img_path" => $definition["img_path"]
                ],
                "options" => $mapped_options
            ];
        }, $mapped_definitions);
    }

    public function read_student_name($id)
    {
        if ($user = $this->select_row("name", "users", "WHERE id = :id", ["id" => $id]))
            return $user["name"];
        else return null;;
    }

    public function save_result($user_id, $post)
    {
        date_default_timezone_set("America/Bogota");
        $user = $this->select_row("role, id, teacher_id", "users", "WHERE id = :id", ["id" => $user_id]);

        if ($user && $user["role"] === "Student") {
            $result = [
                "guide_id" => $post->guide_id,
                "activity" => $post->activity,
                "date_taken" => time(),
                "taken_by" => $user["id"],
                "teacher_id" => $user["teacher_id"],
                "author_id" => $post->author_id,
                "score" => $post->score,
                "duration" => $post->duration
            ];
            if ($this->insert_row("activities", "(guide_id, activity, date_taken, taken_by, teacher_id, author_id, score, duration)", "(:guide_id, :activity, :date_taken, :taken_by, :teacher_id, :author_id, :score, :duration)", $result))
                echo json_encode(["msg" => "Your result was saved.", "success" => true]);
            else  echo json_encode(["msg" => "I could not save your result. Log out and then log in.", "success" => false]);
        } else echo json_encode(["msg" => "Your result was not saved. Log in as a student or ask your teacher to sign you up."]);
    }

    public function save_result_php($user_id, $post)
    {
        date_default_timezone_set("America/Bogota");
        $user = $this->select_row("role, id, teacher_id", "users", "WHERE id = :id", ["id" => $user_id]);

        if ($user && $user["role"] === "Student") {
            $result = [
                "guide_id" => $post->guide_id,
                "activity" => $post->activity,
                "date_taken" => time(),
                "taken_by" => $user["id"],
                "teacher_id" => $user["teacher_id"],
                "author_id" => $post->author_id,
                "score" => $post->score,
                "duration" => $post->duration
            ];
            if ($this->insert_row("activities", "(guide_id, activity, date_taken, taken_by, teacher_id, author_id, score, duration)", "(:guide_id, :activity, :date_taken, :taken_by, :teacher_id, :author_id, :score, :duration)", $result))
                return "Your result was saved.";
            else  return "I could not save your result. Log out and then log in.";
        } else return "Your result was not saved. Log in as a student or ask your teacher to sign you up.";
    }
}
