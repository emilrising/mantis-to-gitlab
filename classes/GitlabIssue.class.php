<?php

class GitlabIssue extends GitlabBaseClass
{
    public $id;
    public $title;
    public $assignee_id;
    public $author_id;
    public $project_id;
    public $created_at;
    public $updated_at;
    public $position;
    public $branch_name;
    public $description;
    public $milestone_id;
    public $state;

    public function __construct()
    {
        $this -> query = "SELECT * FROM `issues` WHERE `id` = :Id LIMIT 1";
        $this -> where = array(':Id' => $this -> id);

    }

    public function getBugNotes()
    {
        global $pdo_gitlab;
        try {
            $stmt = $pdo_gitlab -> prepare("SELECT `id` FROM `notes` WHERE `noteable_id` = :Id AND `noteable_type` = 'issue'");
            $stmt -> execute(array(':Id' => $this -> id));
            $stmt -> setFetchMode(MyPDO::FETCH_CLASS,  "GitlabNote");
            return $stmt -> fetchAll();
        } catch (MyPDOException $e) {
            return 'Error: ' . $e -> __toString();
        }

    }

}
?>