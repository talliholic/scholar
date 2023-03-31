<?php
require "Db.php";
class Classroom extends Db
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
        $array = ["name" => $post->name, "author_id" => $this->author_id, "grade_level" => $post->grade_level];

        if ($id = $this->insert_row_id("classrooms", "(name, author_id, grade_level)", "(:name, :author_id, :grade_level)", $array)) echo json_encode(["id" => $id, "success" => true]);
        else $this->fail_msg();
    }

    private function create_students($post)
    {
        $studentsArr = explode("\r\n", $post->students);
        $studentsArr = array_filter($studentsArr, function ($student) {
            return $student !== "";
        });
        $num_students = count($studentsArr);
        $insert_results_arr = [];

        if ($num_students > 0) {
            foreach ($studentsArr as $student) {
                $student_name = trim($student);
                $new_student = ["name" => $student_name, "password" => rand(1000, 9999), "username" => $this->create_student_username($student_name), "classroom_id" => $post->id, "role" => "Student", "teacher_id" => $this->author_id];

                if ($this->insert_row("users", "(name, password, username, classroom_id, role, teacher_id)", "(:name, :password, :username, :classroom_id, :role, :teacher_id)", $new_student) === 1) array_push($insert_results_arr, true);
                else array_push($insert_results_arr, false);
            }
        }

        if (in_array(false, $insert_results_arr)) return false;
        else return true;
    }

    private function create_student_username($name)
    {
        $nameArr = explode(" ", $name);
        $username = $nameArr[0];
        $temp_username = $username;
        $duplicate_username = $this->select_row("username", "users", "WHERE username = :username", ["username" => $username]);
        $i = 0;

        while ($duplicate_username) {
            $temp_username = $username;
            $i++;
            $temp_username = $username . $i;
            $duplicate_username = $this->select_row("username", "users", "WHERE username = :username", ["username" => $temp_username]);
        }

        return $temp_username;
    }

    public function delete($post)
    {
        $user_ids = [];
        $users = $this->select_rows("id", "users", "WHERE classroom_id = :classroom_id", ["classroom_id" => $post->id]);

        foreach ($users as $user) {
            array_push($user_ids, $user["id"]);
        }

        if ($this->delete_users($users) > 0) {
            if ($users_deleted = $this->delete_row("classrooms", "WHERE id = :id", ["id" => $post->id]) === 1) {
                $activities_deleted = $this->delete_activities($user_ids);
                echo json_encode(["msg" => "$users_deleted student(s) and $activities_deleted activities were deleted", "success" => true]);
            } else echo json_encode(["success" => false, "msg" => "The students of this classroom could not be deleted."]);
        } else {
            if ($this->delete_row("classrooms", "WHERE id = :id", ["id" => $post->id]) === 1)
                echo json_encode(["success" => true, "msg" => "This classroom was deleted but did not have students."]);
        }
    }

    private function delete_activities($user_ids)
    {
        $result = [];
        foreach ($user_ids as $id) {
            $activities = $this->select_rows("id", "activities", "WHERE taken_by = :taken_by", ["taken_by" => $id]);

            foreach ($activities as $activity) {
                if ($this->delete_row("activities", "WHERE id = :id", ["id" => $activity["id"]])) array_push($result, true);
            }
        }
        if ($result) return count($result);
        else return false;
    }

    public function delete_user_activities($post)
    {
        $deleted = [];
        $activities = $this->select_rows("id", "activities", "WHERE taken_by = :taken_by AND teacher_id = :teacher_id", ["taken_by" => $post->id, "teacher_id" => $this->author_id]);
        if ($this->delete_user($post)) {
            foreach ($activities as $activity) {
                if ($this->delete_row("activities", "WHERE id = :id", ["id" => $activity["id"]]) === 1)
                    array_push($deleted, true);
                else array_push($deleted, false);
            }
        }
        if (in_array(false, $deleted)) $this->fail_msg();
        else $this->success_msg();
    }

    private function delete_user($post)
    {
        $array = [
            "id" => $post->id,
            "teacher_id" => $this->author_id
        ];
        if ($this->delete_row("users", "WHERE id = :id AND teacher_id = :teacher_id", $array) === 1) return true;
        else return false;
    }

    private function delete_users($users)
    {
        $results = [];
        foreach ($users as $user) {
            if ($this->delete_row("users", "WHERE id = :id", ["id" => $user["id"]]) === 1)
                array_push($results, true);
            else array_push($results, false);
        }
        return count($results);
    }

    private function get_study_guide_title($id)
    {
        if ($study_guide = $this->select_row("title", "study_guides", "WHERE id = :id", ["id" => $id])) return $study_guide["title"];
        else return null;
    }

    private function read_by_student_id($id)
    {
        if ($user = $this->select_row("classroom_id", "users", "WHERE id = :id AND teacher_id = :teacher_id", [
            "id" => $id,
            "teacher_id" => $this->author_id
        ])) return $user["classroom_id"];
        else return null;
    }

    public function read_by_id($id)
    {
        if ($classroom = $this->select_row("name, grade_level", "classrooms", "WHERE author_id = :author_id AND id = :id", ["author_id" => $this->author_id, "id" => $id])) return $classroom;
        else return null;
    }

    public function read_all()
    {
        return $this->select_rows("id, name, grade_level", "classrooms", "WHERE author_id = :author_id", ["author_id" => $this->author_id]);
    }

    public function read_students($classroom_id)
    {
        $array = ["author_id" => $this->author_id, "classroom_id" => $classroom_id];

        if ($students = $this->select_rows("id, name, username, password", "users", "WHERE teacher_id = :author_id AND classroom_id = :classroom_id", $array)) return $students;
        else return [];
    }

    public function read_student_by_id($id)
    {
        return $this->select_row("name, username, password, classroom_id", "users", "WHERE id = :id AND teacher_id = :teacher_id", ["id" => $id, "teacher_id" => $this->author_id]);
    }

    public function results()
    {
        $activities = $this->select_rows("*", "activities", "WHERE teacher_id = :teacher_id", [
            "teacher_id" => $this->author_id
        ]);

        $mapped_activities =  array_map(function ($activity) {
            return [
                ...$activity,
                "classroom_id" => $this->read_by_student_id($activity["taken_by"]),
                "taken_by" => $this->read_student_by_id($activity["taken_by"])["name"],
                "study_guide_title" => $this->get_study_guide_title($activity["guide_id"])
            ];
        }, $activities);

        $students = [];
        $mapped_result = [];

        foreach ($mapped_activities as $activity) {
            if (!in_array($activity["taken_by"], $students)) array_push($students, $activity["taken_by"]);
        }

        foreach ($students as $student) {
            $results = [];
            $filtered_results = array_values(array_filter($mapped_activities, function ($activity) use ($student) {
                return $activity["taken_by"] === $student;
            }));

            foreach ($filtered_results as $result) {
                $title = $result["study_guide_title"] . " " . $result["activity"];
                if (!in_array($title, $results)) {
                    array_push($results, $title);
                }
            }

            array_push($mapped_result, [
                "taken_by" => $student,
                "activities" => array_map(function ($result) {
                    return [
                        "title" => $result,
                        "scores" => []
                    ];
                }, $results),
                "results" => $filtered_results,
            ]);
        }

        return $mapped_result;
    }

    private function get_results_by_student($id)
    {
        return $this->select_rows("*", "activities", "WHERE taken_by = :taken_by AND teacher_id = :teacher_id", ["taken_by" => $id, "teacher_id" => $this->author_id]);
    }

    public function get_results($id)
    {
        $students = $this->select_distinct("users", "id", "WHERE classroom_id = :classroom_id AND teacher_id = :teacher_id", ["classroom_id" => $id, "teacher_id" => $this->author_id]);

        return array_map(function ($student) {
            $results = $this->get_results_by_student($student["id"]);
            $study_guide_titles = [];
            $filtered_results = [];

            foreach ($results as $result) {
                $title = $this->get_study_guide_title($result["guide_id"]) . " - " . $result["activity"];
                if (!in_array($title, $study_guide_titles)) array_push($study_guide_titles, $title);
            }

            foreach ($study_guide_titles as $title) {
                $filtered = array_values(array_filter($results, function ($result) use ($title) {
                    $title_filter = $this->get_study_guide_title($result["guide_id"]) . " - " . $result["activity"];
                    return $title_filter === $title;
                }));
                $mapped_filtered_results = array_map(function ($filter) {
                    return [
                        ...$filter,
                        "study_guide_title" => $this->get_study_guide_title($filter["guide_id"]) . " - " . $filter["activity"]
                    ];
                }, $filtered);
                array_push($filtered_results,  $mapped_filtered_results);
            }

            return [
                "taken_by" => $this->read_student_by_id($student["id"])["name"],
                "results" => $filtered_results
            ];
        }, $students);
    }

    public function update($post)
    {
        //NO ERROR HANDLING HERE
        $this->update_row("classrooms", "name = :name, grade_level = :grade_level", "WHERE id = :id", ["id" => $post->id, "name" => $post->name, "grade_level" => $post->grade_level]);

        if ($this->create_students($post)) echo json_encode(["id" => $post->id, "success" => true]);
        else $this->fail_msg();
    }

    public function update_student($post)
    {
        $update = [
            "teacher_id" => $this->author_id,
            "id" => $post->id,
            "name" => $post->name,
            "username" => $post->username,
            "password" => $post->password
        ];
        $classroom_id = $post->classroom_id;
        if ($this->update_row("users", "name = :name, username = :username, password = :password", "WHERE id = :id AND teacher_id = :teacher_id", $update) === 1) echo json_encode([
            "id" => $classroom_id,
            "success" => true,
            "msg" => "Student updated."
        ]);
        else $this->fail_msg();
    }
}
