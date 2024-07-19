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
		'moustache' => [
			'link' => $_ENV['BASE_URL'] . '/storage/moustache',
			'canvasWidth' => 2.5,
			'canvasHeight' => 2,
			'width' => 156,
			'height' => 130
		],
		'couronne' => [
			'link' => $_ENV['BASE_URL'] . '/storage/couronne',
			'canvasWidth' => 2.5,
			'canvasHeight' => 2,
			'width' => 156,
			'height' => 130
		],
	];

	$superposition_image = filter_input(INPUT_POST, 'superposition-image', FILTER_SANITIZE_SPECIAL_CHARS);
	if (!isset($authorized_pictures[$superposition_image])) {
		send_http_error('Image non autorisée', 400);
	}

	$picture = $authorized_pictures[$superposition_image];

	return create_post($picture, $_POST['source'] ?? '', $_SESSION['user']['id']);
}
