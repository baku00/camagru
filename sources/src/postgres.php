<?php

function connect($host, $port, $dbname, $user, $password) {
	try {
		$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];

		$pdo = new PDO($dsn, $user, $password, $options);
		return $pdo;
	}
	catch (PDOException $e) {
		echo "Erreur de connexion : " . $e->getMessage();
		return NULL;
	}
}

global $pdo;

$pdo = connect(
	$_ENV['POSTGRES_HOST'],
	$_ENV['POSTGRES_PORT'],
	$_ENV['POSTGRES_DATABASE'],
	$_ENV['POSTGRES_USERNAME'],
	$_ENV['POSTGRES_PASSWORD']
);

// Create users table
$pdo->exec('CREATE TABLE IF NOT EXISTS users (
	id SERIAL PRIMARY KEY,
	username VARCHAR(255) NOT NULL UNIQUE,
	email VARCHAR(255) NOT NULL UNIQUE,
	token_validation VARCHAR(255) NOT NULL UNIQUE,
	token_reset_password VARCHAR(255) NULL,
	validated_at TIMESTAMP DEFAULT NULL,
	notify BOOLEAN DEFAULT TRUE,
	password VARCHAR(255) NOT NULL
)');

// Créez la table posts
$pdo->exec('CREATE TABLE IF NOT EXISTS posts (
	id SERIAL PRIMARY KEY,
	user_id INTEGER NOT NULL,
	path VARCHAR(255) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)');

$pdo->exec('CREATE TABLE IF NOT EXISTS comments (
	id SERIAL PRIMARY KEY,
	post_id INTEGER NOT NULL,
	author_id INTEGER NOT NULL,
	content TEXT NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
	FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
)');