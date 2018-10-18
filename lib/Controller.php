<?php
namespace Lib;

abstract class Controller
{
    protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}
}
