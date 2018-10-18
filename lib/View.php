<?php
namespace Lib;

class ViewException extends \Exception {};

class View
{
	public static function view($view_name, $data = array())
	{
		$base_layout = '../Views/layout.php';
		$custom_view = '../Views/'.$view_name.'.php';

		if(file_exists($base_layout))
		{
			if(file_exists($custom_view))
			{
				$title = Config::get('DEFAULT_TITLE', 'Undefined title');
				$content = '';
				extract($data);

				ob_start();
				require $custom_view;
				$content = ob_get_contents();
				ob_clean();

				require $base_layout;
			}
			else
				throw new ViewException("View::view(): file '$custom_view' doesn't exists.");
		}
		else
			throw new ViewException("View::view(): file '$base_layout' doesn't exists.");
	}
}
