<?php

function send_http_error($message, $code) {
	http_response_code($code);
	echo json_encode(['error' => $message]);
	exit();
}

function send_http_message($message, $code) {
	http_response_code($code);
	echo json_encode(['message' => $message]);
	exit();
}
