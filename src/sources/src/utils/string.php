<?php

function startsWith($haystack, $needle) {
	return preg_match('/^' . preg_quote($needle, '/') . '/', $haystack) === 1;
}