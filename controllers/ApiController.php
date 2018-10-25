<?php
namespace controllers;

use lib\{Config, Controller, View};
use models\{User, Board};

class ApiController extends Controller
{
	/*******************************************************************************************************************
	 * public function createBoard()
	 *
	 * Create a new board
	 */
	public function createBoard()
	{
		if(User::isLogged())
		{
			$response = ["success" => false, "errors" => []];
			$request = $this->request;
			$boards = new Board();

			if($request->hasPost(['title', 'desc', 'colorpicker']))
			{
				$title = trim(htmlentities($request->post('title'), ENT_QUOTES | ENT_HTML5));
				if(!$boards->validateBoardName($title))
					$response["errors"]["title"] = "Le nom doit être compris entre 3 et 55 caractères.";

				$desc = trim(htmlentities($request->post('desc'), ENT_QUOTES | ENT_HTML5));
				$colorpicker = $request->post('colorpicker');
				if(!in_array($colorpicker, Config::get('COLORS')))
					$colorpicker = Config::get('COLORS')[0];

				if(count($response["errors"]) == 0)
				{
					$response["success"] = true;
					$result = $boards->create(User::id(), $title, $desc, $colorpicker);

					if($result !== false)
					{
						$response["redirect"] = Config::get('BASE_URL').'board/'.$result;
					}
				}

				echo json_encode($response);
				exit();
			}
		}

		echo "{}";
		exit();
	}


	/*******************************************************************************************************************
	 * public function updateBoard()
	 *
	 * Update board data
	 */
	public function updateBoard()
	{
		if(User::isLogged())
		{
			$response = ["success" => false, "errors" => []];
			$request = $this->request;
			$boards = new Board();

			if($request->hasPost(['board_id', 'title', 'desc', 'colorpicker']))
			{
				$board_id = $request->post('board_id');

				if($boards->isUserOwner(User::id(), $board_id))
				{
					$title = trim(htmlentities($request->post('title'), ENT_QUOTES | ENT_HTML5));
					if(!$boards->validateBoardName($title))
						$response["errors"]["title"] = "Le nom doit être compris entre 3 et 55 caractères.";

					$desc = trim(htmlentities($request->post('desc'), ENT_QUOTES | ENT_HTML5));
					$colorpicker = $request->post('colorpicker');
					if(!in_array($colorpicker, Config::get('COLORS')))
						$colorpicker = Config::get('COLORS')[0];

					if(count($response["errors"]) == 0)
					{
						$response["success"] = true;
						$result = $boards->update(User::id(), $board_id, $title, $desc, $colorpicker);
					}

					echo json_encode($response);
					exit();
				}
			}
		}

		echo "{}";
		exit();
	}
}
