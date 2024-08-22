<?php

function fetchPostById($id)
{
	global $pdo;
	$stmt = $pdo->prepare('SELECT posts.*, users.username, users.id as user_id FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = :id');
	$stmt->execute(['id' => intval($id)]);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row;
}
function get_all_posts($pageNumber) {
	global $pdo;
	$PAGINATION = 5;

	$stmt = $pdo->prepare('SELECT posts.*, users.username, users.id as user_id FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at ASC LIMIT :limit OFFSET :offset');
	$stmt->execute([
		'limit' => $PAGINATION,
		'offset' => ($pageNumber - 1) * $PAGINATION,
	]);
	$total_posts = $pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();
	$total_pages = ceil($total_posts / $PAGINATION);

	return [
		'posts' => $stmt->fetchAll(),
		'total_pages' => $total_pages,
	];
}

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
	// Créer une ressource de découpe
	$cut = imagecreatetruecolor($src_w, $src_h);

	// Copier la section pertinente de l'arrière-plan vers la ressource de découpe
	imagecopy($cut, $dst_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);

	// Copier la section pertinente du filigrane vers la ressource de découpe
	imagecopy($cut, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);

	// Insérer la ressource de découpe dans l'image de destination
	imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}

function create_post($superposable, $source, $userId) {
	$base64_data = substr($source, strpos($source, ',') + 1);
	$image_binary = base64_decode($base64_data);
	$baseImage = imagecreatefromstring($image_binary);

    $baseWidth = imagesx($baseImage);
    $baseHeight = imagesy($baseImage);
    
    $filterImage = imagecreatefrompng($superposable);
    $filterWidth = imagesx($filterImage);
    $filterHeight = imagesy($filterImage);

    $filterAspectRatio = $filterWidth / $filterHeight;
    $newFilterHeight = $baseHeight;
    $newFilterWidth = round($newFilterHeight * $filterAspectRatio);

    $resizedFilter = imagecreatetruecolor($newFilterWidth, $newFilterHeight);
    imagealphablending($resizedFilter, false);
    imagesavealpha($resizedFilter, true);
    imagecopyresampled($resizedFilter, $filterImage, 0, 0, 0, 0, $newFilterWidth, $newFilterHeight, $filterWidth, $filterHeight);

    $positionX = round(($baseWidth - $newFilterWidth) / 2);
    $positionY = round(($baseHeight - $newFilterHeight) / 2);

    imagesavealpha($baseImage, true);
    imagecopy($baseImage, $resizedFilter, $positionX, $positionY, 0, 0, $newFilterWidth, $newFilterHeight);

	$token = bin2hex(random_bytes(32));
	
	// header('Content-type: image/png');
	imagepng($baseImage, $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $token . '.png');
	// imagepng($baseImage);
	// imagedestroy($baseImage);
    // switch ($imageType) {
    //     case "jpeg":
    //         imagejpeg($baseImage, $imagePath);
    //         break;
    //     case "png":
    //         imagepng($baseImage, $imagePath);
    //         break;
    // }

    imagedestroy($baseImage);
    imagedestroy($filterImage);
    imagedestroy($resizedFilter);

	// exit();
	global $pdo;
	$stmt = $pdo->prepare('INSERT INTO posts (user_id, path) VALUES (:user_id, :path)');
	$stmt->execute([
		'user_id'=> $userId,
		'path'=> $token,
	]);
	
	echo json_encode(fetchPostById($pdo->lastInsertId()));
}

// function create_post($superposable, $source, $userId)
// {
// 	if (empty($source))
// 		send_http_error('La source est vide', 400);

// 	$base64_data = substr($source, strpos($source, ',') + 1);
// 	$image_binary = base64_decode($base64_data);
// 	$img = imagecreatefromstring($image_binary);
// 	$img_superposable = imagecreatefrompng($superposable);

// 	$img_x = imagesx($img);
// 	$img_y = imagesy($img);
// 	$superposable_x = imagesx($img_superposable);
// 	$superposable_y = imagesy($img_superposable);
// 	$destination_x = 125;
// 	$destination_y =  135;
// 	imagecopy($img, $img_superposable, $destination_x, $destination_y, 0, 0, imagesx($img_superposable), imagesy($img_superposable));
// 	imagealphablending($img, false);
// 	imagesavealpha($img, true);
// 	$color = imagecolorallocatealpha($img, 0, 0, 0, 127);
// 	imagefill($img, 0, 0, $color);
// 	$token = bin2hex(random_bytes(32));
	
// 	header('Content-type: image/png');
// 	// imagepng($img, $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $token . '.png');
// 	imagepng($img);
// 	imagedestroy($img);
// 	exit();

// 	global $pdo;
// 	$stmt = $pdo->prepare('INSERT INTO posts (user_id, path) VALUES (:user_id, :path)');
// 	$stmt->execute([
// 		'user_id'=> $userId,
// 		'path'=> $token,
// 	]);
	
// 	echo json_encode(fetchPostById($pdo->lastInsertId()));
// }

function deletePost($postId) {
	global $pdo;
	$post = fetchPostById($postId);
	if ($post['user_id'] !== $_SESSION['user']['id']) {
		send_http_error("Vous n'êtes pas autorisé à supprimer ce post", 403);
	}

	$stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
	$stmt->execute(['id' => $postId]);
	$path = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $post['path'] . '.png';
	if (file_exists($path))
		unlink($path);
	return $stmt->rowCount() > 0;
}
