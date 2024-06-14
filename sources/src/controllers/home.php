<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/posts.php';

function get()
{
	$posts = get_all_posts();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/home.php';
}