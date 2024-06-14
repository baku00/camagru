<?php

session_start();

$user = NULL;

if (isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
}
