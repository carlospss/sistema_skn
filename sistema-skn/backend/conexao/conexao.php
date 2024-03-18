<?php

	$server = $_SERVER['SERVER_NAME'];
	$user = "root";//esse vai mudar
	$pass = "";//esse também
	$bd = "skn";
	
	$conn = new Mysqli($server, $user, $pass, $bd);

?>