<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

function post() {
	deleteUser($_SESSION['user']['id']);
	unset($_SESSION['user']);
	header('Location: /');
}