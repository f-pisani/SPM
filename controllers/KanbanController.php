<?php
namespace Controllers;

use Lib\{Config, Controller, View};
use Models\{User, Board};

class KanbanController extends Controller
{
	/*******************************************************************************************************************
	 * public function index()
	 *
	 * Display registration and login forms.
	 */
	public function index()
	{
		if(User::isLogged())
		{
			$request = $this->request;
			$title = "Kanban - Dashboard";

			$boards = new Board();
			$user_boards = $boards->getUserBoards(User::id());

			return View::view('dashboard', compact('request', 'title', 'user_boards'));
		}

		header('Location: '.Config::get('BASE_URL').'login');
		exit();
	}


	/*******************************************************************************************************************
	 * public function board()
	 *
	 * Display a specific board
	 */
	public function board()
	{
		$request = $this->request;

		if(User::isLogged() && $request->hasParameter('id'))
		{
			$board_id = $request->parameter('id');

			$boards = new Board();
			$q_board = $boards->getBoard($board_id);
			if($boards->isUserAllowed(User::id(), $board_id) == 1 && $q_board->num_rows == 1)
			{
				$title = "Kanban - Dashboard";
				$q_board = $q_board->fetch_object();
				//echo var_dump($q_board->fetch_object());

				return View::view('board', compact('request', 'title', 'q_board'));
			}

			header('Location: '.Config::get('BASE_URL').'dashboard');
			exit();
		}

		header('Location: '.Config::get('BASE_URL').'login');
		exit();
	}


	/*******************************************************************************************************************
	 * public function settings()
	 *
	 * Display a specific board settings
	 */
	public function settings()
	{
		if(User::isLogged())
		{
			$request = $this->request;
			$title = "Kanban - Dashboard";

			return View::view('dashboard', compact('request', 'title'));
		}

		header('Location: '.Config::get('BASE_URL').'login');
		exit();
	}


	/*******************************************************************************************************************
	 * public function invitations()
	 *
	 * Display a specific board invitations
	 */
	public function invitations()
	{
		if(User::isLogged())
		{
			$request = $this->request;
			$title = "Kanban - Dashboard";

			return View::view('dashboard', compact('request', 'title'));
		}

		header('Location: '.Config::get('BASE_URL').'login');
		exit();
	}
}
