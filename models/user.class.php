<?php

    if(!defined('MODEL')) die('This page should not be accessed that way');

    if(!defined(BASIC_LOADED)) require_once(MODEL.'basic.php');
    createConst(__FILE__);

    loadClass('model');
	loadClass('token');

    class User extends Model
	{
		public static function isLoggedIn()
		{
			return (isset($_SESSION['user_id']) && !is_null($_SESSION['user_id']));
		}

		public static function populateSessionFromCookie()
		{
			if(!User::isLoggedIn() && isset($_COOKIE['token'])) {
				$token = Token::getUserIdFromToken($_COOKIE['token']);
				if (!is_null($token))
					$_SESSION['user_id'] = $token;
			}

			if(User::isLoggedIn())
				setcookie('token', $_SESSION['user_id'], time()+Token::MAX_IDLE_TIME, "/", "", false,true);

		}

		public static function login($userId)
		{
			$_SESSION['user_id'] = $userId;

			$token = new Token();
			$value = Functions::randomHash();

			$token->set('value', $value);
			$token->set('lastActivity', time());
			$token->set('userId', $userId);
			$token->save();

			setcookie('token', $value, time()+Token::MAX_IDLE_TIME, "/", "", false, true);
		}

		public static function logout()
		{
			$_SESSION['user_id'] = NULL;

			if(isset($_COOKIE['token']))
			{
				$whereClause = 'value = :v';
				$params = array(array('id'=>':v', 'type'=>PDO::PARAM_STR, 'value'=>$_COOKIE['token']));

				$results = Token::search($whereClause, $params);
				foreach($results as $result)
					$result->delete();

				setcookie('token', '', time-86400);
			}
		}
	}

?>
