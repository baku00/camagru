<?php

function fetchById($id)
{
	global $pdo;
	$stmt = $pdo->prepare('SELECT comments.*, users.username as author FROM comments JOIN users ON comments.author_id = users.id WHERE comments.id = :id');
	$stmt->execute(['id' => intval($id)]);
	$row = $stmt->fetch();
	return $row;
}

function fetchCommentsByPostId($id)
{
	global $pdo;
	$stmt = $pdo->prepare('SELECT comments.*, users.username FROM comments JOIN users ON comments.author_id = users.id WHERE post_id = :id');
	$stmt->execute(['id' => intval($id)]);
	$row = $stmt->fetchAll();
	return $row;
}
function get_all_comments() {
	global $pdo;
	$stmt = $pdo->prepare('SELECT comments.*, users.username FROM comments JOIN users ON comments.author_id = users.id ORDER BY created_at ASC');
	$stmt->execute();
	return $stmt->fetchAll();
}

function create_comment($content, $userId, $postId)
{
	if (empty($content) || empty($postId))
		send_http_error('Veuillez saisir un commentaire et choisir un post', 400);
	global $pdo;
	$stmt = $pdo->prepare('INSERT INTO comments (author_id, content, post_id) VALUES (:author_id, :content, :post_id)');
	$stmt->execute([
		'author_id'=> $userId,
		'post_id'=> $postId,
		'content'=> $content,
	]);
	echo json_encode(fetchById($pdo->lastInsertId()));
}
