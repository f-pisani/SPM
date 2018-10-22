<?php use Lib\Config; ?>

<div id="wrapper">
	<?php include 'navbar.php'; ?>
	<div id="content">
		<?php include 'display_msg.php'; ?>
		<div id="home-forms">
			<form id="form-register" class="form" action="<?= Config::get('BASE_URL').'login' ?>" method="post">
				<h2>Rejoinez-nous ! Commencez de nouveaux projets.</h2>
				<div class="form-row">
					<label class="form-label" for="fullname">Nom complet :</label>
					<input class="form-input" type="text" id="fullname" name="fullname" placeholder="ex: Jean Dupont" value="<?= $request->post('fullname') ?? '' ?>">
				</div>
				<div class="form-row">
					<label class="form-label" for="email_register">E-mail :</label>
					<input class="form-input" type="email" id="email_register" name="email_register" placeholder="ex: jean.dupont@gmail.com" value="<?= $request->post('email_register') ?? '' ?>">
				</div>
				<div class="form-row">
					<label class="form-label" for="pwd_register">Mot de passe :</label>
					<input class="form-input" type="password" id="pwd_register" name="pwd">
				</div>
				<div class="form-row">
					<label class="form-label" for="pwd_conf">Confirmation mot de passe :</label>
					<input class="form-input" type="password" id="pwd_conf" name="pwd_conf">
				</div>
				<button class="form-btn btn-right" type="submit" name="register" value="true">Je m'inscris</button>
			</form>

			<form id="form-login" class="form" action="<?= Config::get('BASE_URL').'login' ?>" method="post">
				<h2>Suivez l'avancement de vos projets. Connectez-vous !</h2>
				<div class="form-row">
					<label class="form-label" for="email">E-mail :</label>
					<input class="form-input" type="email" id="email" name="email" placeholder="ex: jean.dupont@gmail.com" value="<?= $request->post('email') ?? '' ?>">
				</div>
				<div class="form-row">
					<label class="form-label" for="pwd">Mot de passe :</label>
					<input class="form-input" type="password" id="pwd" name="pwd">
				</div>
				<button class="form-btn btn-right" type="submit" name="login" value="true">Connexion</button>
			</form>
		</div>
	</div>
</div>
