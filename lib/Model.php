<?php
namespace Lib;

abstract class Model
{
    private static $mysqli;

	public function escape_string($str)
	{
		return self::getMysqli()->real_escape_string($str);
	}
	
    protected function rawSQL($sql)
	{
		$res = self::getMysqli()->query($sql);

        return $res;
    }

    protected static function getMysqli()
	{
        if(self::$mysqli === null)
		{
            $host = Config::get("DB_HOST");
            $user = Config::get("DB_USER");
            $password = Config::get("DB_PWD");
			$database = Config::get("DB_BASE");

            self::$mysqli = new \mysqli($host, $user, $password, $database);

			if(self::$mysqli->connect_errno)
				throw new \Exception("MySQLi : (" . self::$mysqli->connect_errno . ") " . self::$mysqli->connect_error);
        }

        return self::$mysqli;
    }
}
