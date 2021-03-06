<?php
namespace controllers;

use lib\{Config, Controller, View};
use models\{User, Board};

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
			$user_invitations = $boards->getUserInvitations(User::id());

			return View::view('dashboard', compact('request', 'title', 'user_invitations', 'user_boards'));
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
				$q_board = $q_board->fetch_object();
				$q_members = $boards->listMembers($board_id);
				$q_invites = $boards->listInvites($board_id);

				$title = "Kanban - ".$q_board->name;

				return View::view('board', compact('request', 'title', 'q_board', 'q_members', 'q_invites'));
			}

			header('Location: '.Config::get('BASE_URL').'dashboard');
			exit();
		}

		header('Location: '.Config::get('BASE_URL').'login');
		exit();
	}
}
