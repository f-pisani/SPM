<?php use Lib\{Config}; ?>

<div id="board">
	<div class="header" style="background-color: #<?= $q_board->color; ?>">
		<div class="title"><?= $q_board->name; ?></div>
		<div class="desc"><?= substr($q_board->description, 0, 255); ?>..</div>
	</div>
</div>
