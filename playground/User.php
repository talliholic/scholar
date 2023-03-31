<?php
class User
{
    //Connection Property
    private $connection;

    //Initialize the class
    function __construct()
    {
        $this->connection = new mysqli("localhost", "root", "", "scholar");
    }

    //Write Methods
    function get_users()
    {
        $sql = "SELECT * FROM users LIMIT 10";
        $users = $this->connection->query($sql);
        $users = $users->fetch_all(MYSQLI_ASSOC);
        // echo "<pre>";
        // print_r($users);
        // echo "</pre>";
        echo json_encode($users);
    }

    function insert($post)
    {
        $sql = "INSERT INTO users (name) VALUES ('$post->name')";
        $result = $this->connection->query($sql);

        if ($result) echo json_encode(["inserted" => true]);
        else echo json_encode(["inserted" => false]);
    }

    function update($post)
    {
        $sql = "UPDATE users SET name = '$post->name' WHERE id = '$post->id'";
        $result = $this->connection->query($sql);

        if ($result) echo json_encode(["updated" => true]);
        else echo json_encode(["updated" => false]);
    }

    function delete($post)
    {
        $sql = "DELETE from users WHERE id = '$post->id'";
        $result = $this->connection->query($sql);

        if ($result) echo json_encode(["deleted" => true]);
        else echo json_encode(["deleted" => false]);
    }
}
