<?php
namespace Lib;

class RouteUriException extends \Exception {};

class RouteUri
{
	private $uri;
	private $httpMethod;
	private $regex;
	private $parameters;
	private $where;
	private $controller;
	private $controllerMethod;

	public function __construct($httpMethod, $uri, $callback)
	{
		$this->uri = $uri;
		$this->httpMethod = $httpMethod;
		$this->where = array();

		$this->generateRegex();
		$this->registerCallback($callback);
	}

	public function match($uri)
	{
		$this->generateRegex();

		if(preg_match($this->regex, $uri))
		{
			$data = array();
			preg_match_all($this->regex, $uri, $data);

			$key_parameters = array_keys($this->parameters);
			for($i=1; $i<count($data); $i++)
				$this->parameters[$key_parameters[$i-1]] = $data[$i][0];

			return true;
		}

		return false;
	}

	public function getUri(){ return $this->uri; }
	public function getHttpMethod(){ return $this->httpMethod; }
	public function getRegex(){ return $this->regex; }
	public function getParameters(){ return $this->parameters; }
	public function getControllerName(){ return $this->controller; }
	public function getControllerMethodName(){ return $this->controllerMethod; }

	public function where($params_regex)
	{
		if(is_array($params_regex))
		{
			foreach($params_regex as $param_name => $param_regex)
				$this->where[$param_name] = $param_regex;
		}
	}

	private function generateRegex()
	{
		$this->parameters = array();
		$this->regex = '/^' . str_replace('/', '\\/', $this->uri) . '$/';
		$uri_params = array();
		$uri_params_count = preg_match_all("/\{([a-zA-Z0-9_]+)\}/", $this->uri, $uri_params);

		// $uri contains at least 1 parameter ({var name})
		if($uri_params_count)
		{
			// Replace each parameter by a regex
			foreach($uri_params[1] as $var_name)
			{
				if(!array_key_exists($var_name, $this->parameters))
					$this->parameters[$var_name] = null;

				if(array_key_exists($var_name, $this->where))
					$this->regex = str_replace('{' . $var_name . '}', '('.$this->where[$var_name].')', $this->regex);
				else
					$this->regex = str_replace('{' . $var_name . '}', '([A-Za-z0-9_\-]+)', $this->regex);
			}
		}
	}


	private function registerCallback($callback)
	{
		$callback_split = array();

		// Callback format is 'Controller@MethodToCall'
		if(preg_match_all('/^([A-Za-z0-9_]+)@([A-Za-z0-9_]+)$/', $callback, $callback_split) === 1)
		{
			$this->controller = "\\Controllers\\".$callback_split[1][0]; // Controller Class
			$this->controllerMethod = $callback_split[2][0]; // Controller Method

			// ReflectionClass will throw a ReflectionException if className doesn't exists
			try
			{
				$controllerReflection = new \ReflectionClass($this->controller);
			}
			catch(\ReflectionException $e)
			{
				throw new RouteUriException("RouteUri::registerCallback(): class '$this->controller' does not exists.");

				return false;
			}

			if($controllerReflection->isSubclassOf('Lib\Controller') && $controllerReflection->hasMethod($this->controllerMethod))
				return true;
			else
				throw new RouteUriException("RouteUri::registerCallback(): class '$this->controller' does not
				                             implements method '$this->controllerMethod' and must inherit
											 from 'Lib\Controller'.");
		}

		return false;
	}
}
