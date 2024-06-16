<?php

function fetchLikeById($id)
{
	global $pdo;
	$stmt = $pdo->prepare('SELECT likes.*, users.username as author FROM likes JOIN users ON likes.author_id = users.id WHERE likes.id = :id');
	$stmt->execute(['id' => intval($id)]);
	$row = $stmt->fetch();
	return $row;
}

function fetchLikesByPostId($id)
{
	global $pdo;
	$stmt = $pdo->prepare('SELECT likes.*, users.username FROM likes JOIN users ON likes.author_id = users.id WHERE post_id = :id');
	$stmt->execute(['id' => intval($id)]);
	$row = $stmt->rowCount();
	return $row;
}
function get_all_likes() {
	global $pdo;
	$stmt = $pdo->prepare('SELECT likes.*, users.username FROM likes JOIN users ON likes.author_id = users.id ORDER BY created_at ASC');
	$stmt->execute();
	return $stmt->fetchAll();
}

function userHasLikedPost($postId, $userId)
{
	global $pdo;
	$stmt = $pdo->prepare('SELECT * FROM likes WHERE author_id = :author_id AND post_id = :post_id');
	$stmt->execute([
		'author_id'=> $userId,
		'post_id'=> $postId,
	]);
	$row = $stmt->fetch();
	return $row;
}

function create_like($userId, $postId)
{
	if (empty($postId))
		send_http_error('Veuillez choisir un post', 400);
	global $pdo;
	if (userHasLikedPost($postId, $userId))
		send_http_error("Post déjà liké", 409);
	$stmt = $pdo->prepare('INSERT INTO likes (author_id, post_id) VALUES (:author_id, :post_id)');
	$stmt->execute([
		'author_id'=> $userId,
		'post_id'=> $postId,
	]);

	$totalLikes = fetchLikesByPostId($postId);
	$liked = userHasLikedPost($postId, $userId);

	echo json_encode([
		'total_likes' => $totalLikes,
		'liked' => $liked,
	]);
}
function delete_like($userId, $postId)
{
	if (empty($postId))
		send_http_error('Veuillez choisir un post', 400);
	global $pdo;
	if (!userHasLikedPost($postId, $userId))
		send_http_error("Post non liké", 404);
	$stmt = $pdo->prepare('DELETE FROM likes WHERE author_id=:author_id AND post_id=:post_id');
	$stmt->execute([
		'author_id'=> $userId,
		'post_id'=> $postId,
	]);

	$totalLikes = fetchLikesByPostId($postId);
	$liked = userHasLikedPost($postId, $userId);

	echo json_encode([
		'total_likes' => $totalLikes,
		'liked' => $liked,
	]);
}
