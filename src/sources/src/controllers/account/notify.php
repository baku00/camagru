<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/user.php';

function post() {
	$notify = intval(filter_input(INPUT_POST, 'notify', FILTER_SANITIZE_NUMBER_INT));
	notifyUser($notify, $_SESSION['user']['id']);
}
