<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/posts.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/comments.php';

function post() {
	$content = substr(filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS), 0, 255);
	$postId = intval(filter_input(INPUT_POST, 'postId', FILTER_SANITIZE_NUMBER_INT));
	return create_comment($content ?? '', $_SESSION['user']['id'], $postId);
}
