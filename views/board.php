<?php use lib\{Config}; ?>

<div id="board">
	<div class="header" style="background-color: #<?= $q_board->color; ?>">
		<div class="infos">
			<div class="title"><?= $q_board->name; ?></div>
			<pre class="desc" data-fulldesc="<?= $q_board->description; ?>"><?php
			if(strlen($q_board->description) > 255)
				echo substr($q_board->description, 0, 255).'..<i class="fas fa-caret-down" onClick="toggleBoardDesc()"></i>';
			else
				echo $q_board->description;
			?>
			</pre>
		</div>
		<div class="actions">
			<a class="link-btn" href="javascript:void(0)" onClick="showBoardSettingsModal()"><i class="fas fa-cog"></i> Configuration</a>
			<a class="link-btn" href="<?= Config::get('BASE_URL').'board/'.$q_board->id.'/members' ?>"><i class="fas fa-users"></i> Membres</a>
		</div>
	</div>
</div>

<!-- *************************************************************************************************************** -->
<!-- MODALS                                                                                                          -->
<!-- *************************************************************************************************************** -->
<div id="modal-settings" class="modal">
	<a class="modal-close" onClick="hideBoardSettingsModal()" href="javascript:void(0)"><i class="fas fa-2x fa-times"></i></a>
	<h1><i class="fas fa-cog"></i> Configuration</h1>
	<form id="form-edit" class="form" action="<?= Config::get('BASE_URL').'api/board/update' ?>" method="post">
		<input type="hidden" name="board_id" value="<?= $q_board->id; ?>">
		<div class="form-row">
			<label class="form-label" for="title">Nom du projet :</label>
			<input class="form-input" id="title" name="title" placeholder="ex : Custom CRM eXpedia" value="<?= $q_board->name; ?>" required="true" maxlength="55">
		</div>
		<div class="form-column">
			<label class="form-label" for="desc">Description du projet :</label>
			<textarea class="form-textarea" id="desc" name="desc" placeholder="ex : Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer rhoncus metus sit amet tortor imperdiet.."><?= $q_board->description; ?></textarea>
		</div>
		<div id="project-colorpicker" class="colorpicker" data-colorpicker="<?= implode(',', Config::get('COLORS')) ?>" data-selected="<?= $q_board->color; ?>">
		</div>
	<button type="submit" class="form-btn btn-right" name="edit" value="true">Modifier</button>
	</form>
</div>
