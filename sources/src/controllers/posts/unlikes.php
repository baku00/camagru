<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/posts.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/likes.php';

function post() {
	$postId = intval(filter_input(INPUT_POST, 'postId', FILTER_SANITIZE_NUMBER_INT));
	$post = fetchPostById($postId);
	$_SESSION['errors'] = [];
	if (!$post) {
		send_http_error('Post introuvable', 404);
	}
	return delete_like($_SESSION['user']['id'], $postId);
}
