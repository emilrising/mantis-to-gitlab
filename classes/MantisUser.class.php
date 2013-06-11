<?php

class MantisUser extends MantisBaseClass
{
    public $id;
    public $email;

	public function __construct(){
		$this -> query = "SELECT * FROM `mantis_user_table` WHERE `id` = :Id LIMIT 1";
		$this -> where = array(':Id' => ($this -> id));
	}


}
?>