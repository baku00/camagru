<?php

function fetchPostById($id)
{
	global $pdo;
	$stmt = $pdo->prepare('SELECT posts.*, users.username, users.id as user_id FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = :id');
	$stmt->execute(['id' => intval($id)]);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row;
}
function get_all_posts() {
	global $pdo;
	$stmt = $pdo->prepare('SELECT posts.*, users.username, users.id as user_id FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at ASC');
	$stmt->execute();
	return $stmt->fetchAll();
}

function create_post($source, $userId)
{
	if (empty($source))
		send_http_error('La source est vide', 400);
	global $pdo;
	$token = bin2hex(random_bytes(32));
	$stmt = $pdo->prepare('INSERT INTO posts (user_id, path) VALUES (:user_id, :path)');
	$stmt->execute([
		'user_id'=> $userId,
		'path'=> $token,
	]);
	$base64_data = substr($source, strpos($source, ',') + 1);
	$image_binary = base64_decode($base64_data);
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/storage/' . $token . '.png', $image_binary);
	echo json_encode(fetchPostById($pdo->lastInsertId()));
}
