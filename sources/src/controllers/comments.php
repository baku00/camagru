<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/posts.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/comments.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

function post() {
	$content = substr(filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS), 0, 255);
	$postId = intval(filter_input(INPUT_POST, 'postId', FILTER_SANITIZE_NUMBER_INT));
	$post = fetchPostById($postId);
	$_SESSION['errors'] = [];
	if (!$post) {
		send_http_error('Post introuvable', 400);
	}
	return create_comment($content ?? '', $_SESSION['user']['id'], $postId);
}
