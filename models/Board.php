<?php
namespace models;

use lib\{Configuration, Model};

class Board extends Model
{
	/*******************************************************************************************************************
	 * public function validateBoardName($name)
	 *
	 * Return true if $name is valid; false otherwise
	 */
	public function validateBoardName($name)
	{
		return (strlen($name) >= 3 && strlen($name) <= 55);
	}


	/*******************************************************************************************************************
	 * public function create($user_id, $title, $desc, $color)
	 *
	 * Create a new board
	 *
	 * Return insert ID if success; false otherwise
	 */
	public function create($user_id, $title, $desc, $color)
	{
		$user_id = $this->escape_string($user_id);
		$title = $this->escape_string($title);
		$desc = $this->escape_string($desc);
		$color = $this->escape_string($color);

		$board_id = false;
		$result = $this->rawSQL("INSERT INTO boards VALUES(null, '$title', '$desc', '$color', '".time()."', '".time()."', '$user_id')");
		if($result)
		{
			$board_id = $this->insert_id();
			$this->rawSQL("INSERT INTO board_lists VALUES(null, 'Backlog', '', '0', '".time()."', '".time()."', '$board_id')");
			$this->rawSQL("INSERT INTO board_lists VALUES(null, 'In Progress', '', '1', '".time()."', '".time()."', '$board_id')");
			$this->rawSQL("INSERT INTO board_lists VALUES(null, 'Testing', '', '2', '".time()."', '".time()."', '$board_id')");
			$this->rawSQL("INSERT INTO board_lists VALUES(null, 'Done', '', '3', '".time()."', '".time()."', '$board_id')");
		}

		return $board_id;
	}


	/*******************************************************************************************************************
	 * public function update($user_id, $title, $desc, $color)
	 *
	 * Update a board
	 *
	 * Return insert ID if success; false otherwise
	 */
	public function update($user_id, $board_id, $title, $desc, $color)
	{
		$user_id = $this->escape_string($user_id);
		$board_id = $this->escape_string($board_id);
		$title = $this->escape_string($title);
		$desc = $this->escape_string($desc);
		$color = $this->escape_string($color);

		return $this->rawSQL("UPDATE boards SET name = '$title',
		                                        description = '$desc',
												color = '$color',
												updated_at = '".time()."'
											WHERE id = '$board_id' AND
											      user_id = '$user_id'");
	}


	/*******************************************************************************************************************
	 * public function getUserBoards($user_id)
	 *
	 * Retrieves boards for a specific user ID
	 */
	public function getUserBoards($user_id)
	{
		$user_id = $this->escape_string($user_id);

		return $this->rawSQL("SELECT * FROM boards WHERE user_id = '$user_id'
			                  UNION
							  SELECT B.*
							  FROM boards B, board_user BU
							  WHERE BU.board_id = B.id AND BU.user_id = '$user_id' ORDER BY name ASC");
	}


	/*******************************************************************************************************************
	 * public function getUserBoards($user_id)
	 *
	 * Retrieves board invitations for a specific user ID
	 */
	public function getUserInvitations($user_id)
	{
		$user_id = $this->escape_string($user_id);

		$query = "SELECT U.fullname AS owner_name,
						 U.email AS owner_email,
		                 B.name AS board_name,
						 B.created_at AS board_created,
						 BI.created_at AS invitation_date,
						 BI.id AS invitation_id
		          FROM users U, boards B, board_invitations BI
				  WHERE BI.user_id = '$user_id' AND
				        B.id = BI.board_id AND
						U.id = B.user_id AND
						BI.accepted = '0'
				  ORDER BY BI.created_at DESC";

		return $this->rawSQL($query);
	}


	/*******************************************************************************************************************
	 * public function isUserAllowed($user_id, $board_id)
	 *
	 * Return 1 if allowed 0 if user is not allowed.
	 */
	public function isUserAllowed($user_id, $board_id)
	{
		$user_id = $this->escape_string($user_id);
		$board_id = $this->escape_string($board_id);

		return $this->rawSQL("SELECT * FROM boards WHERE id = '$board_id' AND user_id = '$user_id'
			 				  UNION
							  SELECT B.* FROM boards B, board_user BU WHERE BU.board_id = B.id AND BU.board_id = '$board_id' AND BU.user_id = '$user_id'")->num_rows;
	}


	/*******************************************************************************************************************
	 * public function isUserOwner($user_id, $board_id)
	 *
	 * Return 1 if owner 0 if user is not owner.
	 */
	public function isUserOwner($user_id, $board_id)
	{
		$user_id = $this->escape_string($user_id);
		$board_id = $this->escape_string($board_id);

		return $this->rawSQL("SELECT * FROM boards WHERE id = '$board_id' AND user_id = '$user_id'")->num_rows;
	}


	/*******************************************************************************************************************
	 * public function isInvitePending($user_id, $board_id)
	 *
	 * Return 1 if pending 0 if no invite
	 */
	public function isInvitePending($user_id, $board_id)
	{
		$user_id = $this->escape_string($user_id);
		$board_id = $this->escape_string($board_id);

		return $this->rawSQL("SELECT * FROM board_invitations WHERE accepted = '0' AND board_id = '$board_id' AND user_id = '$user_id'")->num_rows;
	}


	/*******************************************************************************************************************
	 * public function listInvites($board_id)
	 *
	 * Get all invitations pending for $board_id
	 */
	public function listInvites($board_id)
	{
		$board_id = $this->escape_string($board_id);

		return $this->rawSQL("SELECT U.id AS user_id, U.email, BI.* FROM users U, board_invitations BI
			                  WHERE BI.board_id = '$board_id' AND BI.user_id = U.id AND BI.accepted = '0' ORDER BY BI.created_at DESC");
	}


	/*******************************************************************************************************************
	 * public function sendInvite($user_id, $board_id)
	 *
	 * Send an invitation to $user_id to join $board_id
	 */
	public function sendInvite($user_id, $board_id)
	{
		$user_id = $this->escape_string($user_id);
		$board_id = $this->escape_string($board_id);

		return $this->rawSQL("INSERT INTO board_invitations VALUES(null,
		                                                           '0',
															       '$board_id',
															       '$user_id',
															       '".time()."',
															       '".time()."')");
	}


	/*******************************************************************************************************************
	 * public function cancelInvite($user_id, $board_id)
	 *
	 * Cancel an invitation to $user_id to join $board_id
	 */
	public function cancelInvite($user_id, $board_id)
	{
		$user_id = $this->escape_string($user_id);
		$board_id = $this->escape_string($board_id);

		return $this->rawSQL("DELETE FROM board_invitations WHERE accepted = '0' AND board_id = '$board_id' AND user_id = '$user_id'");
	}


	/*******************************************************************************************************************
	 * public function removeMember($user_id, $board_id)
	 *
	 * Remove member $user_id from $board_id
	 */
	public function removeMember($user_id, $board_id)
	{
		$user_id = $this->escape_string($user_id);
		$board_id = $this->escape_string($board_id);

		$this->rawSQL("DELETE FROM board_invitations WHERE accepted = '1' AND board_id = '$board_id' AND user_id = '$user_id'");

		return $this->rawSQL("DELETE FROM board_user WHERE board_id = '$board_id' AND user_id = '$user_id'");
	}


	/*******************************************************************************************************************
	 * public function acceptInvite($user_id, $invitation_id)
	 *
	 * Accept $invitation_id for $user_id
	 */
	public function acceptInvite($user_id, $invitation_id)
	{
		$user_id = $this->escape_string($user_id);
		$invitation_id = $this->escape_string($invitation_id);

		$result = $this->rawSQL("SELECT board_id FROM board_invitations WHERE accepted = '0' AND id = '$invitation_id' AND user_id = '$user_id'");

		if($result->num_rows == 0)
			return false;

		$board_id = $result->fetch_object()->board_id;
		$this->rawSQL("UPDATE board_invitations SET accepted = '1', updated_at = '".time()."' WHERE id = '$invitation_id' AND user_id = '$user_id'");
		$this->rawSQL("INSERT INTO board_user VALUES(null, '$board_id', '$user_id', '".time()."')");

		return true;
	}


	/*******************************************************************************************************************
	 * public function declineInvite($user_id, $invitation_id)
	 *
	 * Decline $invitation_id for $user_id
	 */
	public function declineInvite($user_id, $invitation_id)
	{
		$user_id = $this->escape_string($user_id);
		$invitation_id = $this->escape_string($invitation_id);

		$this->rawSQL("DELETE FROM board_invitations WHERE id = '$invitation_id' AND user_id = '$user_id'");

		return true;
	}


	/*******************************************************************************************************************
	 * public function leave($user_id, $board_id)
	 *
	 * $user_id leave $board_id
	 */
	public function leave($user_id, $board_id)
	{
		$user_id = $this->escape_string($user_id);
		$board_id = $this->escape_string($board_id);

		$this->rawSQL("DELETE FROM board_invitations WHERE accepted = '1' AND board_id = '$board_id' AND user_id = '$user_id'");
		$this->rawSQL("DELETE FROM board_user WHERE board_id = '$board_id' AND user_id = '$user_id'");

		return true;
	}


	/*******************************************************************************************************************
	 * public function listMembers($board_id)
	 *
	 * Get all members for $board_id
	 */
	public function listMembers($board_id)
	{
		$board_id = $this->escape_string($board_id);

		return $this->rawSQL("SELECT U.id AS user_id, U.email, BU.* FROM users U, board_user BU
			                  WHERE BU.board_id = '$board_id' AND BU.user_id = U.id
							  ORDER BY BU.joined_at DESC");
	}


	/*******************************************************************************************************************
	 * public function getBoard($board_id)
	 *
	 * Retrieves board data.
	 */
	public function getBoard($board_id)
	{
		$board_id = $this->escape_string($board_id);

		return $this->rawSQL("SELECT B.*, U.fullname FROM boards B, users U WHERE B.id = '$board_id' AND B.user_id = U.id");
	}
}
