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
			<a class="link-btn" href="javascript:void(0)" onClick="showBoardInvitesModal()"><i class="fas fa-users"></i> Membres</a>
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

<div id="modal-invites" class="modal">
	<a class="modal-close" onClick="hideBoardInvitesModal()" href="javascript:void(0)"><i class="fas fa-2x fa-times"></i></a>
	<div id="members-wrapper">
		<div class="members">
			<h1><i class="fas fa-users"></i> Membres</h1>
			<ul class="members-listing">
				<?php
				foreach($q_members as $member)
				{
					echo '<li data-userid="'.$member['user_id'].'"><span><b>'.$member['email'].'</b> a rejoint le <b>'
					     .date('d/m/Y à H:i:s', $member['joined_at']).'</b></span>
					     <i class="fas fa-user-times" onClick="removeMember('.$member['user_id'].')"></i></li>';
				}
				?>
			</ul>
		</div>
		<div class="invites">
			<h1><i class="fas fa-user-plus"></i> Invitations en cours</h1>
			<form id="form-invite" class="form" action="<?= Config::get('BASE_URL').'api/board/invite'; ?>" method="post">
				<input type="hidden" name="board_id" value="<?= $q_board->id; ?>">
				<input type="hidden" name="action" value="send">
				<div class="form-row">
					<label class="form-label" for="email">E-mail :</label>
					<input class="form-input" type="email" id="email" name="email" placeholder="jean.dupont@gmail.com">
				</div>
				<button type="submit" class="form-btn btn-right" name="invite" value="true">Inviter</button>
			</form>
			<ul class="members-listing">
				<?php
				foreach($q_invites as $invite)
				{
					echo '<li data-userid="'.$invite['user_id'].'"><span><b>'.$invite['email'].'</b> invité le <b>'
					     .date('d/m/Y à H:i:s', $invite['created_at']).'</b></span>
					     <i class="fas fa-user-times" onClick="cancelInvite('.$invite['user_id'].')"></i></li>';
				}
				?>
			</ul>
		</div>
	</div>
</div>
