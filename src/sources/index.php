<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_error_handler(function($errno, $errstr) {
	throw new ErrorException($errstr, 0, $errno);
});
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

require_once 'vendor/autoload.php';
require_once 'src/env.php';
require_once 'src/postgres.php';
require_once 'src/utils/utils.php';
require_once 'src/router.php';
require_once 'src/session.php';
require_once 'src/mail.php';
require_once 'src/http.php';

$url = strtolower(substr(filter_input(INPUT_GET, 'url', FILTER_SANITIZE_SPECIAL_CHARS) ?? '', 0, 255));
if (!isset($url))
	$url = 'home';

require_once 'src/middleware/auth.php';
require_once 'src/middleware/csrf.php';

$method = strtolower($_SERVER['REQUEST_METHOD']);

checkPath($url, $method);

get_path($url, $method);