<?php

    if(!defined('MODEL')) die('This page should not be accessed that way');

    if(!defined(BASIC_LOADED)) require_once(MODEL.'basic.php');
    createConst(__FILE__);

    loadClass('model');

    class Token extends Model
	{
		const MAX_IDLE_TIME = 7*86400;

		public static function purgeExpired()
		{
			$whereClause = 'last_activity < :t';
			$params = array(
				array('id'=>':t', 'type'=>PDO::PARAM_INT, 'value'=> (time() - self::MAX_IDLE_TIME))
			);

			$results = Token::search($whereClause, $params);

			foreach($results as $result)
				$result->delete();
		}

		public static function getUserIdFromToken($value)
		{
			$whereClause = 'value = :v';
			$params = array(
				array('id'=>':v', 'type'=>PDO::PARAM_STR, 'value'=>$value)
			);

			$results = Token::search($whereClause, $params);

			if(count($results)>0)
				return $results[0]->get('userId');
			else
				return NULL;
		}
    }

?>
