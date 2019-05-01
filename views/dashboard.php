<?php use lib\{Config}; ?>

<div id="dashboard">
	<?php
	if($user_invitations != false && $user_invitations->num_rows >= 1)
	{
	?>
	<h1><i class="fas fa-user-plus"></i> Invitations</h1>
	<table class="table">
		<thead>
			<tr>
				<th>Projet</th>
				<th>Date de l'invitation</th>
				<th>Actions</th>
			</tr>
		</thead>

		<tbody>
		<?php
		foreach($user_invitations as $board_invite)
		{
			?>
			<tr data-invitationid="<?= $board_invite['invitation_id']; ?>">
				<td><?= '<b>'.$board_invite['board_name'].'</b> par'; ?>
					<?= '<b>'.$board_invite['owner_name'].'</b> (<b>'.$board_invite['owner_email'].'</b>)'; ?></td>
				<td><?= date('d/m/Y à H:i:s', $board_invite['invitation_date']); ?></td>
				<td>
					<a class="link-btn" href="javascript:void(0)" onClick="acceptInvite(<?= $board_invite['invitation_id']; ?>)"><i class="fas fa-check"></i> Accepter</a>
					<a class="link-btn" href="javascript:void(0)" onClick="declineInvite(<?= $board_invite['invitation_id']; ?>)"><i class="fas fa-times"></i> Refuser</a>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<?php
	}
	?>

	<a class="board newboard" onClick="showNewBoardModal()" href="javascript:void(0)">
		<i class="fas fa-plus"></i>&nbsp;&nbsp;Créer un nouveau projet
	</a>
	<div class="boards-list">
		<?php
		foreach($user_boards as $board)
		{
		?>
			<a class="board" href="<?= Config::get('BASE_URL').'board/'.$board['id']; ?>">
				<i class="fas fa-search"></i>&nbsp;&nbsp;<?= $board['name']; ?>
			</a>
		<?php
		}
		?>
	</div>
</div>
<div id="modal-newproject" class="modal">
	<a class="modal-close" onClick="hideNewBoardModal()" href="javascript:void(0)"><i class="fas fa-2x fa-times"></i></a>
	<h1>Créer un nouveau projet</h1>
	<form id="form-newproject" class="form" action="<?= Config::get('BASE_URL').'api/board/create' ?>" method="post">
		<div class="form-row">
			<label class="form-label" for="title">Nom du projet :</label>
			<input class="form-input" id="title" name="title" placeholder="ex : Custom CRM eXpedia" required="true" maxlength="55">
		</div>
		<div class="form-column">
			<label class="form-label" for="desc">Description du projet :</label>
			<textarea class="form-textarea" id="desc" name="desc" placeholder="ex : Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer rhoncus metus sit amet tortor imperdiet.."></textarea>
		</div>
		<div id="project-colorpicker" class="colorpicker" data-colorpicker="<?= implode(',', Config::get('COLORS')) ?>">
		</div>
	<button type="submit" class="form-btn btn-right" name="newproject" value="true">Créer le projet</button>
	</form>
</div>
