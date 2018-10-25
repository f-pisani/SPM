<?php
use Lib\Config;
use Models\User;
?>

<div id="navbar">
	<a class="brand" href="<?= Config::get('BASE_URL'); ?>">Kanban</a>
	<nav class="links">
		<ul>
			<?php
			if(User::isLogged())
			{
			?>
			<li><a href="<?= Config::get('BASE_URL').'dashboard'; ?>">Dashboard</a></li>
			<li><a href="<?= Config::get('BASE_URL').'logout'; ?>">Déconnexion</a></li>
			<?php
			}
			else
			{
			?>
			<li><a href="<?= Config::get('BASE_URL').'login'; ?>">Inscription / Connexion</a></li>
			<?php
			}
			?>
		</ul>
	</nav>
</div>
