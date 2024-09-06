<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/http.php';

$isPostMethod = strtolower($_SERVER['REQUEST_METHOD']) === 'post';
$issetSessionCSRF = isset($_SESSION['csrf']);
$issetPostCSRF = isset($_POST['csrf']);
$sessionCSRF = $issetSessionCSRF ? $_SESSION['csrf'] : '';
$postCSRF = $issetPostCSRF ? $_POST['csrf'] : '';
$isEqualsCSRF = $sessionCSRF === $postCSRF;

if ($isPostMethod && !$issetSessionCSRF) {
	send_http_error('Token csrf non valide', 400);
}

else if ($isPostMethod && !$issetPostCSRF) {
	send_http_error('Token csrf non valide', 400);
}

else if ($isPostMethod && !$isEqualsCSRF) {
	send_http_error('Token csrf non valide', 400);
}

else if ($isPostMethod && !$isEqualsCSRF) {
	send_http_error('Token csrf non valide', 400);
}
