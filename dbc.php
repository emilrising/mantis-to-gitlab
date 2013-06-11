<?php

include ('classes/MyPDO.class.php');

// Initialize the global variable $pdo which will be used for all database
// communication.
global $pdo_mantis;

try {
    $pdo_mantis = new MyPDO('conf/mantis.ini');
    $pdo_mantis -> setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
    $pdo_mantis -> setAttribute(PDO::ATTR_EMULATE_PREPARES,  false);
} catch (MyPDOException $ex) {
    echo $ex;
    exit();
}

global $pdo_gitlab;

try {
    $pdo_gitlab = new MyPDO('conf/gitlab.ini');
    $pdo_gitlab -> setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
	$pdo_mantis -> setAttribute(PDO::ATTR_EMULATE_PREPARES,  false);
} catch (MyPDOException $ex) {
    echo $ex;
    exit();
}
?>