<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/posts.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/likes.php';
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
	$liked = false;
	if (isset($_SESSION['user'])) {
		$liked = userHasLikedPost($_SESSION['user']['id'], $postId);
	}
	$total_likes = fetchLikesByPostId($postId);
	require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/posts.php';
}

function post() {
	$authorized_pictures = [
		'moustache' => $_ENV['BASE_URL'] . '/storage/moustache',
		'couronne' => $_ENV['BASE_URL'] . '/storage/couronne'
	];

	$superposition_image = filter_input(INPUT_POST, 'superposition-image', FILTER_SANITIZE_SPECIAL_CHARS);
	if (!empty($superposition_image) && isset($superposition_image) && !isset($authorized_pictures[$superposition_image])) {
		send_http_error('Image non autorisée', 400);
	}

	$picture = $superposition_image ? $authorized_pictures[$superposition_image] : '';

	return create_post($picture, $_POST['source'] ?? '', $_SESSION['user']['id']);
}
