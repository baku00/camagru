<?php

function secure_path($url) {
	return str_replace('..', '', $url);
}

function error404() {
	echo "404";
}

function get_path($url, $method)
{
	$url = secure_path($url);
	if ($url == '') {
		$url = 'home';
	}
	if (!file_exists("src/controllers/$url.php")) {
		return error404();
	}

	require_once "src/controllers/$url.php";
	if (!function_exists($method))
		error404();

	$method();
}
