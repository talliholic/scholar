<?php
require "Db.php";
class User extends Db
{
    public function create($post)
    {
        $user = [
            "name" => $post->name,
            "email" => $post->email,
            "password" => md5($post->password),
            "username" => $post->username,
            "role" => "Teacher",
            "vkey" => md5(time() . "Iluvrosa"),
        ];

        if ($this->insert_row("users", "(name, email, password, username, role,  vkey)", "(:name, :email, :password, :username, :role,  :vkey)", $user) === 1) $this->success_msg();
        else $this->fail_msg();
    }

    public function get_role_by_id($id)
    {
        if ($user = $this->select_row("role", "users", "WHERE id = :id", ["id" => $id])) return $user["role"];
        else return null;
    }

    public function field_exists($post)
    {
        if ($this->select_row($post->column, "users", "WHERE $post->column = :$post->column", ["$post->column" => $post->field])) $this->success_msg();
        else $this->fail_msg();
    }

    public function login($post)
    {
        if (str_contains($post->email, "@")) {
            $column = "email";
            $array = [
                "email" => $post->email,
                "password" => md5($post->password)
            ];
        } else {
            $column = "username";
            $array = [
                "username" => $post->email,
                "password" => $post->password
            ];
        }

        if ($user = $this->select_row("id, email, username, password", "users", "WHERE $column = :$column AND password = :password", $array)) {
            $vkey = md5(time() . "Iluvrosa");
            session_start();
            $_SESSION["user_id"] = $user["id"];
            if ($this->update_row("users", "vkey = :vkey", "WHERE id = :id", ["vkey" => $vkey, "id" => $user["id"]]) === 1) $_SESSION["vkey"] = $vkey;
            $this->success_msg();
        } else
            $this->fail_msg();
    }

    public function logout()
    {
        session_start();
        if (isset($_SESSION["user_id"])) {
            unset($_SESSION["user_id"]);
            unset($_SESSION["vkey"]);
            $this->success_msg();
        } else  $this->fail_msg();
    }

    public function read($id)
    {
        return $this->select_row("name, email, username, password", "users", "WHERE id = :id", ["id" => $id]);
    }
}
