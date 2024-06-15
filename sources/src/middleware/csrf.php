<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/http.php';

if (strtolower($_SERVER['REQUEST_METHOD']) === 'post' && (!$_SESSION['csrf'] || $_SESSION['csrf'] !== $_POST['csrf'])) {
	send_http_error('Token csrf non valide', 400);
}
