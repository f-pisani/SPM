<?php
namespace Models;

use Lib\{Configuration, Model};

class User extends Model
{
	/*******************************************************************************************************************
	 * public function validateFullname($fullname)
	 *
	 * Return true if $fullname is valid; false otherwise
	 */
	public function validateFullname($fullname)
	{
		return preg_match('/^[a-zA-Z\- ]{3,255}$/', $fullname);
	}


	/*******************************************************************************************************************
	 * public function validateEmail($email)
	 *
	 * Return true if $email is valid; false otherwise
	 */
	public function validateEmail($email)
	{
		// Regex source: https://projects.lukehaas.me/regexhub/
		return (preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/', $email) && strlen($email) <= 255);
	}


	/*******************************************************************************************************************
	 * public function create($fullname, $email, $pwd)
	 *
	 * Create a new user
	 *
	 * Return true if success; false otherwise
	 */
	public function create($fullname, $email, $pwd)
	{
		$fullname = $this->escape_string($fullname);
		$email = $this->escape_string($email);
		$pwd = password_hash($this->escape_string($pwd), PASSWORD_BCRYPT);

		return $this->rawSQL("INSERT INTO users VALUES(null, '$fullname', '$email', '$pwd', '".time()."', '".time()."', '-1')");
	}


	/*******************************************************************************************************************
	 * public function auth($email, $pwd)
	 *
	 * Connect an user with his $email and $pwd
	 *
	 * Return true if success; false otherwise
	 */
	public function auth($email, $pwd)
	{
		$email = $this->escape_string($email);
		$pwd = $this->escape_string($pwd);

		$result = $this->rawSQL("SELECT * FROM users WHERE email = '$email'");
		if($result->num_rows === 1)
		{
			$user_row = $result->fetch_object();
			if(password_verify($pwd, $user_row->password))
			{
				$_SESSION['user_id'] = $user_row->id;
				$_SESSION['user_email'] = $user_row->email;
				$_SESSION['user_pwd'] = $user_row->password;
				$_SESSION['user_createdAt'] = $user_row->created_at;
				$_SESSION['user_updatedAt'] = $user_row->updated_at;
				$_SESSION['user_loggedAt'] = $user_row->logged_at;

				// Update logged_at field
				$this->rawSQL("UPDATE users SET logged_at = '". time() ."' WHERE id = '$user_row->id'");

				return true;
			}
		}

		return false;
	}


	/*******************************************************************************************************************
	 * public static function isLogged()
	 *
	 * Return true if user is logged; false otherwise
	 */
	public static function isLogged()
	{
		return (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']));
	}


	/*******************************************************************************************************************
	 * public static function id()
	 *
	 * Return user id or null
	 */
	public static function id()
	{
		return ($_SESSION['user_id'] ?? null);
	}
}
