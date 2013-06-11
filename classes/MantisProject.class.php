<?php
class MantisProject extends MantisBaseClass
{

	public function __construct(){
		$this -> query = "SELECT * FROM `mantis_project_table` WHERE `id` = :Id LIMIT 1";
		$this -> where = array(':Id' => $this -> id);

	}
	
    public function getProject()
    {
        return MantisProject::getInstance($this);
    }
	
	
    public function getBugs()
    {
        global $pdo_mantis;
        try {
            $stmt = $pdo_mantis -> prepare("SELECT `id` FROM `mantis_bug_table` WHERE `project_id` = :Id");
            $stmt -> execute(array(':Id' => $this -> id));
            $stmt -> setFetchMode(MyPDO::FETCH_CLASS,  "MantisBug");
            $bugs = $stmt -> fetchAll();
            return $bugs;
        } catch (MyPDOException $e) {
            return 'Error: ' . $e -> __toString();
        }


    }

}
?>