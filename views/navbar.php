<?php
use lib\Config;
use models\User;
?>

<div id="navbar">
	<a class="brand" href="<?= Config::get('BASE_URL'); ?>">Kanban</a>
	<nav class="links">
		<ul>
			<?php
			if(User::isLogged())
			{
			?>
			<li><a href="<?= Config::get('BASE_URL').'dashboard'; ?>"><i class="fas fa-home"></i> Dashboard</a></li>
			<li><a href="<?= Config::get('BASE_URL').'logout'; ?>"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
			<?php
			}
			else
			{
			?>
			<li><a href="<?= Config::get('BASE_URL').'login'; ?>"><i class="fas fa-sign-in-alt"></i> Inscription / Connexion</a></li>
			<?php
			}
			?>
		</ul>
	</nav>
</div>
