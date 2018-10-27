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


	/*******************************************************************************************************************
	 * public function inviteBoard()
	 *
	 * Board invitations
	 */
	public function inviteBoard()
	{
		if(User::isLogged())
		{
			$response = ["success" => false, "errors" => []];
			$request = $this->request;
			$users = new User();
			$boards = new Board();

			if($request->hasPost('action'))
			{
				$action = $request->post('action');

				switch($action)
				{
					case 'send':
						if($request->hasPost(['board_id', 'email']))
						{
							$board_id = $request->post('board_id');
							$email = trim($request->post('email'), ENT_QUOTES | ENT_HTML5);

							if($boards->isUserOwner(User::id(), $board_id) && User::email() != $email)
							{
								if(!$users->validateEmail($email))
									$response["errors"]["email"] = "Le format de l'adresse email est invalide.";

								if(!$users->exists($email))
									$response["errors"]["email"] = "Aucun utilisateur avec cette adresse email.";

								if($users->isMemberOfBoard($email, $board_id))
									$response["errors"]["email"] = "L'utilisateur est déjà membre de ce projet.";

								if(count($response["errors"]) == 0)
								{
									$q_user = $users->get($email)->fetch_object();
									if(!$boards->isInvitePending($q_user->id, $board_id))
									{
										$response["success"] = true;
										$response["data"]["user_id"] = $q_user->id;
										$response["data"]["email"] = $q_user->email;
										$response["data"]["date"] = date('d/m/Y à H:i:s', time());
										$boards->sendInvite($q_user->id, $board_id);
									}
									else
										$response["errors"]["email"] = "Une invitation a déjà été envoyée à l'utilisateur.";
								}

								echo json_encode($response);
								exit();
							}
						}
					break;

					case 'cancel':
						if($request->hasPost(['board_id', 'user_id']))
						{
							$board_id = $request->post('board_id');
							$user_id = $request->post('user_id');

							if($boards->isUserOwner(User::id(), $board_id))
							{
								if($boards->cancelInvite($user_id, $board_id))
									$response["success"] = true;

								echo json_encode($response);
								exit();
							}
						}
					break;

					case 'remove':
						if($request->hasPost(['board_id', 'user_id']))
						{
							$board_id = $request->post('board_id');
							$user_id = $request->post('user_id');

							if($boards->isUserOwner(User::id(), $board_id))
							{
								if($boards->removeMember($user_id, $board_id))
									$response["success"] = true;

								echo json_encode($response);
								exit();
							}
						}
					break;

					case 'accept': break;

					case 'decline': break;

					case 'leave': break;

					default: ;
				}
			}
		}

		echo "{}";
		exit();
	}
}
