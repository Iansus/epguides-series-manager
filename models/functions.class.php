<?php

	if(!defined('MODEL')) die('This page should not be accessed that way');

	if(!defined(BASIC_LOADED)) require_once(MODEL.'basic.php');
	createConst(__FILE__);

	class Functions
	{
		public static function randomHash()
		{
			return sha1(time().((string) rand()));
		}

		public static function setResponse($code)
		{
			$test = floor($code/100);

			http_response_code($code);

			if($test >= 4) exit();
		}

		public static function redirect($url)
		{
			header('Location: '.$url);
			exit;
		}

		public static function pError($error)
		{
			echo '<p class="error">'.$error.'</p>';
		}

		public static function pMessage($message)
		{
			echo '<p class="message">'.$message.'</p>';
		}

		public static function secure($s)
		{
			return htmlentities($s, ENT_QUOTES, 'UTF-8');
		}

		public static function validId($id, $allowZero=false)
		{
			if(!is_numeric($id) || floor($id) != $id || $id<0) return false;
			return ($id>0 || $allowZero);
		}

		public static function elt($array, $index)
		{
			return (isset($array[$index])) ? $array[$index] : null;
		}

		public static function post($index)
		{
			return Functions::elt($_POST, $index);
		}

		public static function get($index)
		{
			return Functions::elt($_GET, $index);
		}

		public static function sanitize($string)
		{
			return strtolower($string);
		}

		public static function var_dump($v)
		{
			echo '<pre>';
			var_dump($v);
			echo '</pre>';
		}

		public static function echos($str)
		{
			# TODO increase strength by escaping chars depending on the context
			# e.g. this does not cover PRSSI vulnerabilities
			echo htmlentities($str, ENT_NOQUOTES);
		}
	}

?>
