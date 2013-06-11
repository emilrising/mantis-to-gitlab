<?php

class MantisBug extends MantisBaseClass
{
    public $id;
    public $project_id;
    public $reporter_id;
    public $handler_id;
    public $duplicate_id;
    public $priority;
    public $severity;
    public $reproducibility;
    public $status;
    public $resolution;
    public $projection;
    public $eta;
    public $bug_text_id;
    public $os;
    public $os_build;
    public $platform;
    public $version;
    public $fixed_in_version;
    public $build;
    public $profile_id;
    public $view_state;
    public $summary;
    public $sponsorship_total;
    public $sticky;
    public $target_version;
    public $category_id;
    public $date_submitted;
    public $due_date;
    public $last_updated;

	public function __construct(){
		$this -> query = "SELECT * FROM `mantis_bug_table` WHERE `id` = :Id LIMIT 1";
		$this -> where = array(':Id' => ($this -> id));
	}
	

    public function getBugNotes()
    {
        global $pdo_mantis;
        try {
            $stmt = $pdo_mantis -> prepare("SELECT `id` FROM `mantis_bugnote_table` WHERE `bug_id` = :Id");
            $stmt -> execute(array(':Id' => $this -> id));
            $stmt -> setFetchMode(MyPDO::FETCH_CLASS,  "MantisBugNote");
            return $stmt -> fetchAll();

        } catch (MyPDOException $e) {
            return 'Error: ' . $e -> __toString();
        }

    }

    public function gitlabIfy()
    {
        global $gitlab_project_id;
        $mantis_data = $this -> getInstance();

        $gitlab_data = new GitlabIssue();
        /*
         * Translate Mantis note structure to gitlab note structure
         */
        //Id
        $gitlab_data -> id = $mantis_data -> id;
        //Title
        $gitlab_data -> title = $mantis_data -> summary;

        //assignee_id
        $mantis_user = new MantisUser();
        $mantis_user -> id = $mantis_data -> handler_id;
        $mantis_user = $mantis_user -> getInstance();
		if(is_object($mantis_user)) {
	        $gitlab_user = new GitlabUser();		
	        $gitlab_user -> email = $mantis_user -> email;
	        $gitlab_user = $gitlab_user -> findUser();
	        $gitlab_data -> assignee_id = $gitlab_user -> id;
		} else {
			$gitlab_data -> assignee_id = NULL;
		}
        //author_id
        $mantis_user = new MantisUser();
		$mantis_user  =  $mantis_user  -> setWhere(':Id',$mantis_data -> reporter_id);
        $mantis_user = $mantis_user -> getInstance();
        $gitlab_user = new GitlabUser();
        $gitlab_user -> email = $mantis_user -> email;
        $gitlab_user = $gitlab_user -> findUser();
        $gitlab_data -> author_id = $gitlab_user -> id;
        //project_id
        $gitlab_data -> project_id = $gitlab_project_id;
        //created_at
        $gitlab_data -> created_at = date('Y-m-d H:i:s',  $mantis_data -> date_submitted);
        //updated_at
        $gitlab_data -> updated_at = date('Y-m-d H:i:s',  $mantis_data -> last_updated);
        //position
        //TODO what is giltab position?
        $gitlab_data -> position = 0;
        //XXX branch_name there is no equivalent in Mantis.
        //
        //description
        $mantis_bug_text = new MantisBugText();
		$mantis_bug_text  =  $mantis_bug_text  -> setWhere(':Id',$mantis_data -> bug_text_id);
        $mantis_bug_text = $mantis_bug_text -> getInstance();
        $bug_note = $mantis_bug_text -> description;
        $bug_note .= "\n" . $mantis_bug_text -> steps_to_reproduce;
        $bug_note .= "\n" . $mantis_bug_text -> additional_information;
		//Gitlab markup for code strings.
		$bug_note = str_replace('<', '```<', $bug_note);
		$bug_note = str_replace('>', '>```', $bug_note);
        $gitlab_data -> description = $bug_note;
        //milestone_id
        if(strlen($mantis_data -> target_version)){
	        $mantis_version = new MantisProjectVersion();
			$mantis_version = $mantis_version -> setWhere(':Version',$mantis_data -> target_version);
	        $mantis_version = $mantis_version -> getInstance();
	        $mantis_version = $mantis_version -> gitlabIfy();
	        $gitlab_data -> milestone_id = $mantis_version -> id;
			//save this milestone to Gitlab
			$mantis_version -> insert();
		} else {
			$gitlab_data -> milestone_id = NULL;	
		}
        /*
         * State
         * MANTIS:
         * $g_status_enum_workflow[NEW_]=
         * '10:new, => opened
         * 20:feedback, => opened
         * 30:acknowledged, => opened
         * 40:confirmed, => opened
         * 50:assigned, => opened
         * 80:resolved'; => closed
         * Seems to be consensus even when defining custom status to have
         * variations on closed 80+
         * http://www.mantisbt.org/manual/manual.customizing.mantis.customizing.status.values.php
         * Translation below:
         */
        //state
        if ($mantis_data -> status < 80) {
            $gitlab_data -> state = 'opened';
        } else {
            $gitlab_data -> state = 'closed';
        }

        return $gitlab_data;

    }

}
?>