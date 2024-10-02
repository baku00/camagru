<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/posts.php';

function get()
{
	$pageNumber = intval(filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT)) ?? 1;
	if ($pageNumber < 1)
		$pageNumber = 1;
	$details_posts = get_all_posts($pageNumber);
	$posts = $details_posts['posts'];
	$total_pages = $details_posts['total_pages'];
	require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/home.php';
}
