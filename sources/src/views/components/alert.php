<?php

if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])) {
	foreach ($_SESSION['errors'] as $error) {
		echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</div>';
	}
	$_SESSION['errors'] = [];
}

if (isset($_SESSION['messages']) && is_array($_SESSION['messages'])) {
	foreach ($_SESSION['messages'] as $message) {
		echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($message, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</div>';
	}
	$_SESSION['messages'] = [];
}
