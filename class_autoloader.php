<?php
function __autoload($class) {
	try {
		require_once 'classes/' . $class . '.class.php';
	} catch (Exception $e) {
		echo $e -> getMessage(), "\n";
	}
}
?>