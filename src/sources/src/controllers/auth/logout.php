<?php

function post() {
	session_destroy();
	header('Location: /');
}