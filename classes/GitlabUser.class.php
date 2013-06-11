<?php

class GitlabUser extends GitlabBaseClass
{
    public $id;
    public $email;

    public function __construct()
    {
        $this -> query = "SELECT * FROM `users` WHERE `id` = :Id LIMIT 1";
        $this -> where = array(':Id' => $this -> id);

    }

    public function findUser()
    {
        global $pdo_gitlab;
        try {
            $stmt = $pdo_gitlab -> prepare("SELECT * FROM `users` WHERE `email` = :Email LIMIT 1");
            $stmt -> execute(array(':Email' => $this -> email));
            $stmt -> setFetchMode(MyPDO::FETCH_CLASS,  "GitlabUser");
            return $stmt -> fetch();

        } catch (MyPDOException $e) {
            return 'Error: ' . $e -> __toString();
        }

    }

}
?>