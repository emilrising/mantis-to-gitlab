<?php
require_once "dbc.php";

class GitlabBaseClass extends BaseClass
{

    protected $connect = "gitlab";

    public function insert()
    {
		/*
		 * Loop through object to build query 
		 */
        foreach ($this as $key => $value) {
        	//we don't want a couple of keys.
        	if(!preg_match("/(connect|query|where)/",$key)){
	            $column[] = "`" . $key . "`";
				/*
				 * Using keys to build :Key => 'value';
				 */
	            $values[":" . ucwords($key)] = "" . $value . "";
			}
        }
		//Implode columns and value keys to build prepare query
        $col = implode(', ',  $column);
        $val = implode(', ',  array_keys($values));
		//get table name from class name, remove Gitlab and parse to lowercase, add on columns and values
        $query = "REPLACE INTO " . strtolower(preg_replace("/^Gitlab/",  "",  get_class($this))) . "s (" . $col . ") VALUES (" . $val . ")";
		
		// initialize global connection variables, one to many variables, but follwing the structure of the rest of the code.
        global $pdo_gitlab;
        $connection = $pdo_gitlab;
		//Prepare
        $stmt = $connection -> prepare($query);

		//Execute
        try {
        	$stmt -> execute($values);
        } catch (MyPDOException $e) {
            return 'Error: ' . $e -> __toString();
        }

    }

}
?>