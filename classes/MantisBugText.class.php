<?php

class MantisBugText extends MantisBaseClass
{
    public $id;
	public $description;
	
	public function __construct(){
		$this -> query = "SELECT * FROM `mantis_bug_text_table` WHERE `id` = :Id LIMIT 1";
		$this -> where = array(':Id' => ($this -> id));
	}


}
?>