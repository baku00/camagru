<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/posts.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/comments.php';

function get() {
	$postId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
	$post = fetchPostById($postId);
	$comments = fetchCommentsByPostId($postId);
	if (!$post) {
		$_SESSION['errors'][] = 'Post introuvable';
		header('Location: /');
		return;
	}
	require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/posts.php';
}

function post() {
	return create_post($_POST['source'] ?? '', $_SESSION['user']['id']);
}
