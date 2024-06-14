<?php

function send_http_error($message, $code) {
	http_response_code($code);
	echo json_encode(['error' => $message]);
	exit();
}