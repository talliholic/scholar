<?php
class Db
{
    private $conn;

    function __construct()
    {
        $this->conn = $this->connect();
    }

    private function connect()
    {
        $pdo = new PDO("mysql:host=localhost;dbname=scholar", "root", "");
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }

    protected function delete_row($table, $condition, $array)
    {
        $sql = "DELETE FROM $table $condition";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($array);
        return $stmt->rowCount();
    }


    //LIMITS THE ID TO ONES WHOSE OWNER IS A TEACHER
    protected function get_author_id($id)
    {
        if (!$id) {
            header("Location: /scholar/auth");
        } else {
            if ($author = $this->select_row("id", "users", "WHERE id = :id AND role = :role", ["id" => $id, "role" => "Teacher"])) {
                return $author["id"];
            } else header("Location: /scholar/account");
        }
    }

    protected function fail_msg()
    {
        echo json_encode([
            "success" => false
        ]);
    }

    protected function insert_row($table, $columns, $values, $array)
    {
        $sql = "INSERT INTO $table $columns VALUES $values";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($array);
        return $stmt->rowCount();
    }

    protected function insert_row_id($table, $columns, $values, $array)
    {
        $sql = "INSERT INTO $table $columns VALUES $values";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($array);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $this->conn->lastInsertId();
    }

    protected function select_all($columns, $table, $condition)
    {
        $sql = "SELECT $columns FROM $table $condition";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return [];
        }
        return $stmt->fetchAll();
    }

    protected function select_distinct($table, $columns, $condition, $array)
    {
        $sql = "SELECT DISTINCT $columns FROM $table $condition";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($array);
        if ($stmt->rowCount() === 0) {
            return [];
        }
        return $stmt->fetchAll();
    }

    protected function select_row($columns, $table, $condition, $values)
    {
        $sql = "SELECT $columns FROM $table $condition";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($values);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $stmt->fetch();
    }

    protected function select_rows($columns, $table, $condition, $values)
    {
        $sql = "SELECT $columns FROM $table $condition";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($values);
        if ($stmt->rowCount() === 0) {
            return [];
        }
        return $stmt->fetchAll();
    }

    protected function select_table($table)
    {
        $sql = "SELECT * FROM $table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return [];
        }
        return $stmt->fetchAll();
    }

    protected function success_msg()
    {
        echo json_encode([
            "success" => true
        ]);
    }

    protected function update_row($table, $columns, $condition, $values)
    {
        $sql = "UPDATE $table SET $columns $condition";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($values);
        return $stmt->rowCount();
    }
}
