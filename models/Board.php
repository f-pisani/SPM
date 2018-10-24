<?php
namespace Models;

use Lib\{Configuration, Model};

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

		$result = $this->rawSQL("INSERT INTO boards VALUES(null, '$title', '$desc', '$color', '".time()."', '".time()."', '$user_id')");
		if($result)
			return $this->insert_id();

		return false;
	}
}
