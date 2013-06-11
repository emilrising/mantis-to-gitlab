<?php

class GitlabMilestone extends GitlabBaseClass
{
    public $id;
    public $title;
    public $project_id;
    public $description;
    public $due_date;
    public $created_at;
    public $updated_at;
    public $state;

    public function __construct()
    {
        $this -> query = "SELECT * FROM `milestones` WHERE `id` = :Id LIMIT 1";
        $this -> where = array(':Id' => $this -> id);

    }

}
?>