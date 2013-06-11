<?php

class MantisBugNote extends MantisBaseClass{
  public $id;
  public $bug_id;
  public $reporter_id;
  public $bugnote_text_id;
  public $view_state;
  public $note_type;
  public $note_attr;
  public $time_tracking;
  public $last_modified;
  public $date;
  public $note;


	public function __construct(){
		$this -> query = "SELECT * FROM `mantis_bugnote_table`LEFT JOIN `mantis_bugnote_text_table` ON `mantis_bugnote_table`.`bugnote_text_id` = `mantis_bugnote_text_table`.`id` WHERE `mantis_bugnote_table`.`id` = :Id LIMIT 1";
		$this -> where = array(':Id' => ($this ->  id));
	}
	

	public function gitlabIfy(){
		
		$mantis_data = $this->getInstance();
		$gitlab_data = new GitlabNote();
		/*
		 * Translate Mantis note structure to gitlab note structure
		 */
		$note = $mantis_data -> note;
		//Gitlab markup for code strings.
		$note = preg_replace("'\<(?!\/)(.*?)\>'", '```<$1>', $note);
		$note = preg_replace("'\<\/(.*?)\>'", '<$1>```', $note);

		$gitlab_data -> note = $note;
		$gitlab_data -> noteable_type = "issue";

		//Find user ide in Gitlab
		$mantis_user = new MantisUser();
		$mantis_user  =  $mantis_user  -> setWhere(':Id',$mantis_data -> reporter_id);
		$mantis_user = $mantis_user -> getInstance();
		$gitlab_user = new GitlabUser();
		$gitlab_user -> email = $mantis_user -> email;
		$gitlab_user = $gitlab_user ->findUser();
		$gitlab_data -> author_id = $gitlab_user -> id;

		$gitlab_data -> created_at = date('Y-m-d H:i:s',$mantis_data -> date_submitted);
		$gitlab_data -> updated_at = date('Y-m-d H:i:s',$mantis_data -> last_modified);
		/*
		 * FIXME: Get the new project id in gitlab instance
		 */
		global $gitlab_project_id;
		$gitlab_data -> project_id = $gitlab_project_id ;
		$gitlab_data -> noteable_id = $mantis_data -> bug_id;
		
		return $gitlab_data;
		
	}

}
?>