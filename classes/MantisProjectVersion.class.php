<?php

class MantisProjectVersion extends MantisBaseClass{

	public $version;

	public function __construct(){
		$this -> query = "SELECT * FROM `mantis_project_version_table` WHERE `version` = :Version LIMIT 1";
		$this -> where = array(':Version' => ($this -> version));
	}
	
	public function gitlabIfy()	{
		
		$gitlab_milestone = new GitlabMilestone();
		//id
		$gitlab_milestone -> id = $this -> id;
		$gitlab_milestone -> iid = $this -> id;
		//title
		$gitlab_milestone -> title = $this -> version;
		//FIXME project_id
		global $gitlab_project_id;
		$gitlab_milestone -> project_id = $gitlab_project_id;
		//description
		$gitlab_milestone -> description = $this -> description;
		//due_date
		$gitlab_milestone -> due_date = date('Y-m-d H:i:s',  $this -> date_order);
		//created_at note: no such field in Mantis.
		$gitlab_milestone -> created_at = date('Y-m-d H:i:s',  $this -> date_order);
		//updated_at note: no such field in Mantis.
		$gitlab_milestone -> updated_at = date('Y-m-d H:i:s',  $this -> date_order);
		//state
		$state_types = array(0 => 'active', 1 => 'closed');
		$gitlab_milestone -> state = $state_types[$this -> released];
		
		return $gitlab_milestone;
	}

}
?>
