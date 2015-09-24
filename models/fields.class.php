<?php

	if(!defined('MODEL')) die('This page should not be accessed that way');

	if(!defined(BASIC_LOADED)) require_once(MODEL.'basic.php');
	createConst(__FILE__);

	abstract class Fields
	{
		public static function initFields(Model $object)
		{
			$className = strtolower(get_class($object));

			switch($className)
			{

			case 'serie':
				$object->addField('id', PDO::PARAM_INT, true);
				$object->addField('name');
				$object->addField('epguides_url');
				$object->addField('binsearch_url');
				$object->addField('last_seen_season', PDO::PARAM_INT);
				$object->addField('last_seen_episode', PDO::PARAM_INT);
				break;

			case 'episode':
				$object->addField('id', PDO::PARAM_INT, true);
				$object->addField('serie_id');
				$object->addField('name');
				$object->addField('season');
				$object->addField('episode');
				$object->addField('air_date');
				$object->addField('link');
				break;

			default:
				if (DEBUG) echo ('Fatal error : Unable to load fields for class '.$className.' !');
				Functions::setResponse(500);
				break;
			}
		}

		public static function getSQLTableName($tableName)
		{
			$tables = array(
				'serie'		=>	'series',
				'episode'	=>	'episodes',
					   );

			if (isset($tables[$tableName]))
				return $tables[$tableName];
			else
			{
				if (DEBUG) echo ('Fatal error : Unable to load the table name for class '.$className.' !');
				Functions::setResponse(500);
			}

			return;

		}

		public static function toCamelCaseNaming($sqlName)
		{
			$camelCaseName = $sqlName;
			while(($c = strpos($camelCaseName, '_'))!==FALSE)
			{
				$camelCaseName = substr($camelCaseName, 0, $c).strtoupper($camelCaseName[$c+1]).substr($camelCaseName, $c+2);
			}

			return $camelCaseName;
		}

		public static function toSqlNaming($camelCaseName)
		{
			$sql_name = $camelCaseName;

			for($x=0; $x<strlen($sql_name); $x++)
			{
				$c = $sql_name[$x];
				if(self::isChar($c) && strtoupper($c)==$c)
				{
					$sql_name = substr($sql_name, 0, $x).'_'.strtolower($c).substr($sql_name, $x+1);
				}
			}

			return $sql_name;
		}

		private static function isChar($c)
		{
			$o = ord($c);
			return (($o>=ord('A') && $o<=ord('Z')) || ($o>=ord('a') && $o<=ord('z')));
		}
	}

?>
