<?php

class MantisBugNoteText extends MantisBaseClass {
	public $id;

	public function __construct(){
		$this -> query = "SELECT * FROM `mantis_bugnote_text_table` WHERE `id` = :Id LIMIT 1";
		$this -> where = array(':Id' => ($this -> id));
	}
	



}
?>