<?php
namespace Lib;

class Request
{
	private $get;
	private $post;
    private $parameters;

    public function __construct($get, $post, $parameters)
	{
		$this->get = $get;
		$this->post = $post;
        $this->parameters = $parameters;
    }

	public function hasGet($var_name)
	{
		if(is_array($var_name) && count($var_name) > 0)
		{
			foreach($var_name as $key)
			{
				if(!array_key_exists($key, $this->get))
					return false;
			}

			return true;
		}

		return (array_key_exists($var_name, $this->get));
	}

	public function get($var_name)
	{
		return ($this->get[$var_name] ?? null);
	}

	public function hasPost($var_name)
	{
		if(is_array($var_name) && count($var_name) > 0)
		{
			foreach($var_name as $key)
			{
				if(!array_key_exists($key, $this->post))
					return false;
			}

			return true;
		}

		return (array_key_exists($var_name, $this->post));
	}

	public function post($var_name)
	{
		return ($this->post[$var_name] ?? null);
	}

	public function hasParameter($var_name)
	{
		if(is_array($var_name) && count($var_name) > 0)
		{
			foreach($var_name as $key)
			{
				if(!array_key_exists($key, $this->parameters))
					return false;
			}

			return true;
		}

		return (array_key_exists($var_name, $this->parameters));
	}

	public function parameter($var_name)
	{
		return ($this->parameters[$var_name] ?? null);
	}
}
