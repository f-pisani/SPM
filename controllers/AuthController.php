<?php
namespace Controllers;

use Lib\{Config, Controller, View};
use Models\{User};

class AuthController extends Controller
{
	/*******************************************************************************************************************
	 * public function index()
	 *
	 * Display registration and login forms.
	 */
	public function index()
	{
		if(!User::isLogged())
		{
			$request = $this->request;
			$title = "Kanban - Inscription / Connexion";

			$users = new User();
			$errors = [];
			$success = [];

			// Form::Registration
			if($request->hasPost(['fullname', 'email_register', 'pwd', 'pwd_conf', 'register']))
			{
				if(!$users->validateFullname($request->post('fullname')))
					$errors["Nom invalide !"] = "Le nom ne peut contenir que des lettres, des tirets et des espaces. Il doit être compris entre 2 et 255 caractères.";

				if(!$users->validateEmail($request->post('email_register')))
					$errors["E-mail invalide !"] = "Le format de l'e-mail est invalide ou ce dernier fait plus de 255 caractères.";

				if($request->post('pwd') != $request->post('pwd_conf'))
					$errors["Les mots de passe sont différents !"] = "Les deux mots de passe ne correspondent pas.";

				if(count($errors) == 0)
				{
					$result = $users->create($request->post('fullname'),
					               			 $request->post('email_register'),
								   			 $request->post('pwd'));
					if($result)
					{
						$success["Une dernière étape, connectez-vous !"] = "Votre inscription c'est déroulé à la perfection, vous pouvez désormais vous connecter !";
					}
					else
					{
						$errors["Oups ! Une erreur est survenue."] = "Assurez-vous que l'e-mail ne soit pas déjà associée à un compte. Si le problème persiste veuillez contacter le support.";
					}
				}
			}

			// Form::Login
			if($request->hasPost(['email', 'pwd', 'login']))
			{
				if($users->auth($request->post('email'), $request->post('pwd')))
				{
					header('Location: '.Config::get('BASE_URL').'dashboard');
					exit();
				}
				else
				{
					$errors["Oups ! Une erreur est survenue."] = "Assurez-vous que ayez déjà un compte avant de vous connecter. Si le problème persiste veuillez contacter le support.";
				}
			}

			return View::view('login', compact('request', 'title', 'errors', 'success'));
		}

		header('Location: '.Config::get('BASE_URL').'dashboard');
		exit();
	}


	/*******************************************************************************************************************
	 * public function logout()
	 *
	 * Logout
	 */
	public function logout()
	{
		if(User::isLogged())
		{
			// Session is cleared
			session_unset();
			session_destroy();
		}

		header('Location: '.Config::get('BASE_URL').'login');
		exit();
	}
}