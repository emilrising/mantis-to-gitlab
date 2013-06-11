<?php

class GitlabProject extends GitlabBaseClass
{
    public $id;

    public function __construct()
    {
        $this -> query = "SELECT * FROM `projects` WHERE `id` = :Id LIMIT 1";
        $this -> where = array(':Id' => $this -> id);

    }

    public function getProject()
    {
        return GitlabProject::getInstance($this);
    }

    public function getIssues()
    {
        global $pdo_gitlab;
        try {
            $stmt = $pdo_gitlab -> prepare("SELECT `id` FROM `issues` WHERE `project_id` = :Id");
            $stmt -> execute(array(':Id' => $this -> id));
            $stmt -> setFetchMode(MyPDO::FETCH_CLASS,  "GitlabIssue");
            $issues = $stmt -> fetchAll();
            return $issues;
        } catch (PDOException $e) {
            return 'Error: ' . $e -> __toString();
        }

    }

}
?>