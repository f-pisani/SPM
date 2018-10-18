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
		return (array_key_exists($var_name, $this->get) && !empty($this->get[$var_name]));
	}

	public function get($var_name)
	{
		return ($this->get[$var_name] ?? null);
	}

	public function hasPost($var_name)
	{
		return (array_key_exists($var_name, $this->post) && !empty($this->post[$var_name]));
	}

	public function post($var_name)
	{
		return ($this->post[$var_name] ?? null);
	}

	public function hasParameter($var_name)
	{
		return (array_key_exists($var_name, $this->parameters) && !empty($this->parameters[$var_name]));
	}

	public function parameter($var_name)
	{
		return ($this->parameters[$var_name] ?? null);
	}
}
