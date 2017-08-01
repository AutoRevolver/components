<?php
namespace app\components\tools;

class Enum
{
	/**
	 * 根据id获取枚举名称
	 */
	public static function getVal($name, $key)
	{
		$result = null;
		$file_path = MY_APP_BASE_PATH . '/enum/' . MY_APP_ID . '.php';
		if (file_exists($file_path))
		{
			$result = include $file_path;
			$result = $result[$name][$key];
		}
		return $result;
	}

	/**
	 * 根据名称获取枚举id
	 */
	public static function getKey($name, $val)
	{
		$result = null;
		$file_path = MY_APP_BASE_PATH . '/enum/' . MY_APP_ID . '.php';
		if (file_exists($file_path))
		{
			$result = include $file_path;
			$result = array_flip($result[$name]);
			$result = $result[$val];
		}
		return $result;
	}

	/**
	 * 获取枚举数组
	 */
	public static function getArr($name)
	{
		$result = null;
		$file_path = MY_APP_BASE_PATH . '/enum/' . MY_APP_ID . '.php';
		if (file_exists($file_path))
		{
			$result = include $file_path;
			$result = $result[$name];
		}
		return $result;
	}
}



