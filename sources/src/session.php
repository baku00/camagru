<?php

session_start();

if (!isset($_SESSION["csrf"]))
	$_SESSION['csrf'] = bin2hex(random_bytes(32));

$user = NULL;

if (isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
}
