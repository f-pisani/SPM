<?php
namespace controllers;

use lib\{Config, Controller, View};
use models\{User, Board};

class ApiController extends Controller
{
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
}
