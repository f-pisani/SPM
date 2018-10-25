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
	 * public function getUserBoards($user_id)
	 *
	 * Retrieves boards for a specific user ID
	 */
	public function getUserBoards($user_id)
	{
		$user_id = $this->escape_string($user_id);

		return $this->rawSQL("SELECT * FROM boards WHERE user_id = '$user_id'");
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

		return $this->rawSQL("SELECT * FROM boards WHERE id = '$board_id' AND user_id = '$user_id'")->num_rows;
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
