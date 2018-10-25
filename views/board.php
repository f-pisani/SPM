<?php use lib\{Config}; ?>

<div id="board">
	<div class="header" style="background-color: #<?= $q_board->color; ?>">
		<div class="infos">
			<div class="title"><?= $q_board->name; ?></div>
			<div class="desc"><?= substr($q_board->description, 0, 255); ?>..</div>
		</div>
		<div class="actions">
			<a class="link-btn" href="<?= Config::get('BASE_URL').'board/'.$q_board->id.'/settings' ?>"><i class="fas fa-cog"></i> Configuration</a>
			<a class="link-btn" href="<?= Config::get('BASE_URL').'board/'.$q_board->id.'/members' ?>"><i class="fas fa-users"></i> Membres</a>
		</div>
	</div>
</div>
