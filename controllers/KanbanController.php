<?php
namespace Controllers;

use Lib\{Config, Controller, View};
use Models\{User};

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

			return View::view('dashboard', compact('request', 'title'));
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
