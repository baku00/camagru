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
		if ($method === 'post')
			send_http_error('Page introuvable', 404);
		else
			return error404();
	}

	require_once "src/controllers/$url.php";
	if (!function_exists($method))
	{
		if ($method === 'post')
			send_http_error('Page introuvable', 404);
		else
			return error404();
	}

	$method();
}
