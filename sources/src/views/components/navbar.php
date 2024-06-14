<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/session.php';

?>

<nav class="navbar navbar-expand-lg bg-primary">
	<div class="container-fluid">
		<a class="navbar-brand text-light" href="/">Accueil</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<?php
				if (!isset($_SESSION['user']))
				{ ?>
					<li class="nav-item">
						<a class="nav-link text-light" href="/auth/login">Se connecter</a>
					</li>
				<?php
				} else {
					?>
					<li class="nav-item">
						<a class="nav-link text-light" href="/account">Mon compte</a>
					</li>
					<li class="nav-item">
						<a class="nav-link text-light" href="/create">Créer un post</a>
					</li>
					<li class="nav-item">
						<form id="form-logout" action="/auth/logout" method="post" class="d-none">
						</form>
						<a class="nav-link text-light" href="#" onclick="event.preventDefault(); document.querySelector('#form-logout').submit();">Se déconnecter</a>
					</li>
					<?php
				}
				?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Dropdown
					</a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="#">Action</a></li>
						<li><a class="dropdown-item" href="#">Another action</a></li>
						<li><hr class="dropdown-divider"></li>
						<li><a class="dropdown-item" href="#">Something else here</a></li>
					</ul>
				</li>
			</ul>
			<form class="d-flex" role="search">
				<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
				<button class="btn btn-outline-success" type="submit">Search</button>
			</form>
		</div>
	</div>
</nav>