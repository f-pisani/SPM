<?php
namespace Lib;

class RouteException extends \Exception {};

class Route
{
	private static $routes = array(); // Contains all registered routes
	private static $route_404 = null;


	const REQUEST_METHOD_GET = 'GET'; // HTTP REQUEST_METHOD Get
	const REQUEST_METHOD_POST = 'POST'; // HTTP REQUEST_METHOD Post
	const REQUEST_METHOD_DELETE = 'DELETE'; // HTTP REQUEST_METHOD Delete
	const REQUEST_METHOD_PUT = 'PUT'; // HTTP REQUEST_METHOD Put
	const REQUEST_METHOD_ANY = 'ANY'; // ANY HTTP REQUEST_METHOD (GET|POST|DELETE|PUT)


	/*******************************************************************************************************************
	 * public static function get($uri, $callback)
	 *
	 * $uri : URI to register
	 * $callback : Controller@Method (Callback will be called if the route match the current URI)
	 *
	 * Register a route for HTTP Method GET.
	 *
	 * Return : RouteUri object.
	 */
	public static function get($uri, $callback)
	{
		return self::registerRoute(self::REQUEST_METHOD_GET, $uri, $callback);
	}


	/*******************************************************************************************************************
	 * public static function post($uri, $callback)
	 *
	 * $uri : URI to register
	 * $callback : Controller@Method (Callback will be called if the route match the current URI)
	 *
	 * Register a route for HTTP Method POST.
	 *
	 * Return : RouteUri object.
	 */
	public static function post($uri, $callback)
	{
		return self::registerRoute(self::REQUEST_METHOD_POST, $uri, $callback);
	}


	/*******************************************************************************************************************
	 * public static function delete($uri, $callback)
	 *
	 * $uri : URI to register
	 * $callback : Controller@Method (Callback will be called if the route match the current URI)
	 *
	 * Register a route for HTTP Method DELETE.
	 *
	 * Return : RouteUri object.
	 */
	public static function delete($uri, $callback)
	{
		return self::registerRoute(self::REQUEST_METHOD_DELETE, $uri, $callback);
	}


	/*******************************************************************************************************************
	 * public static function put($uri, $callback)
	 *
	 * $uri : URI to register
	 * $callback : Controller@Method (Callback will be called if the route match the current URI)
	 *
	 * Register a route for HTTP Method PUT.
	 *
	 * Return : RouteUri object.
	 */
	public static function put($uri, $callback)
	{
		return self::registerRoute(self::REQUEST_METHOD_PUT, $uri, $callback);
	}


	/*******************************************************************************************************************
	 * public static function any($uri, $callback)
	 *
	 * $uri : URI to register
	 * $callback : Controller@Method (Callback will be called if the route match the current URI)
	 *
	 * Register a route for ANY HTTP Method (GET|POST|DELETE|PUT).
	 *
	 * Return : RouteUri object.
	 */
	public static function any($uri, $callback)
	{
		return self::registerRoute(self::REQUEST_METHOD_ANY, $uri, $callback);
	}


	/*******************************************************************************************************************
	 * public static function error404($callback)
	 *
	 * $callback : Controller@Method (Callback will be called if the route match the current URI)
	 *
	 * Register a route for error 404.
	 *
	 * Return : RouteUri object.
	 */
	public static function error404($callback)
	{
		self::$route_404 = new RouteUri(self::REQUEST_METHOD_ANY, '', $callback);
	}


	/*******************************************************************************************************************
	 * public static function execute()
	 *
	 * Based on $_SERVER['REQUEST_METHOD'] and $_SERVER['REQUEST_URI'] this method will look for a registered route to
	 * handle the request.
	 */
	public static function execute()
	{
		$request_uri = str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
		$request_method = $_SERVER['REQUEST_METHOD'];

		foreach(self::$routes as $uri_raw => $routeUri)
		{
			if($routeUri->match($request_uri))
			{
				if($routeUri->getHttpMethod() === $request_method || $routeUri->getHttpMethod() === self::REQUEST_METHOD_ANY)
				{
					$request = new Request($_GET, $_POST, $routeUri->getParameters());
					return self::executeCallback($routeUri->getControllerName().'@'.$routeUri->getControllerMethodName(), $request);
				}
				else
					throw new RouteException("Route::execute(): uri '$uri_raw' doesn't allow http request method '". $request_method ."'.");
			}
		}

		if(self::$route_404 !== null)
		{
			$request = new Request($_GET, $_POST, $routeUri->getParameters());
			return self::executeCallback(self::$route_404->getControllerName().'@'.self::$route_404->getControllerMethodName(), $request);
		}
		else
			throw new RouteException("Route::execute(): uri '$request_uri' doesn't match any registered route.");
	}


	/*******************************************************************************************************************
	 * private static function registerRoute($method, $uri, $callback)
	 *
	 * Create and store a route with an associated RouteUri object.
	 */
	private static function registerRoute($method, $uri, $callback)
	{
		self::$routes[$uri] = new RouteUri($method, $uri, $callback);

		return self::$routes[$uri];
	}


	/*******************************************************************************************************************
	 * private static function executeCallback($callback)
	 *
	 * Call the controller and the controller method specified by $callback. (Controller@Method)
	 */
	private static function executeCallback($callback, $request)
	{
		$callback_split = array();
		// Callback format is 'Controller@MethodToCall'
		if(preg_match_all('/^([A-Za-z0-9_\\\]+)@([A-Za-z0-9_]+)$/', $callback, $callback_split) === 1)
		{
			$className = $callback_split[1][0]; // Controller Class
			$classMethodName = $callback_split[2][0]; // Controller Method

			$controller = new $className($request);
			return $controller->$classMethodName();
		}

		throw new RouteException("Route::executeCallback(): callback '$callback' is not well formated or doesn't exists.");
	}
}
