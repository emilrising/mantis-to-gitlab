<?php
session_start();

require_once ('dbc.php');
require_once ('class_autoloader.php');

//FIXME: this is not the best implementation of getting the project in Gitlab.
$gitlab_project_id = 6;
$MantisProject = new MantisProject();
//FIXME: This is not the best way of getting the Mantis project ID.
$MantisProject -> id = 1;
$bugs = $MantisProject -> getBugs();
?>
<html>
	<head>

	</head>
	<body>
		<?php
        foreach ($bugs as $bug) {
            //var_dump($bug -> getInstance());
            $notes = $bug -> getBugNotes();
            $bug -> gitlabIfy() -> insert();
            foreach ($notes as $note) {
              $note -> gitlabIfy() -> insert();
            }
        }
		?>
	</body>
</html>