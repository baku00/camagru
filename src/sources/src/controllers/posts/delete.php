<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/posts.php';

function post() {
	$postId = intval(filter_input(INPUT_POST, 'postId', FILTER_SANITIZE_NUMBER_INT));
	$post = fetchPostById($postId);
	
	if (!$post) {
		$_SESSION['errors'][] = 'Post introuvable';
		header('Location: /');
		return;
	}
	
	$deleted = deletePost($postId);
	if (!$deleted) {
		$_SESSION['errors'][] = 'Impossible de supprimer le post';
		header('Location: /');
		return;
	}
	header('Location: /');
	return;
}