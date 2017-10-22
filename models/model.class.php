<?php

	if(!defined('MODEL')) die('This page should not be accessed that way');

	if(!defined(BASIC_LOADED)) require_once(MODEL.'basic.php');
	createConst(__FILE__);

	loadClass('db');
	loadClass('fields');

	abstract class Model
	{
		private $fields = array();
		private $fromSql;
		const SEARCH_QUERY_DEBUG = false;

		public function Model($uniqid = null)
		{
			Fields::initFields($this);

			$this->fromSql = (!is_null($uniqid));

			$this->initClassFields();
			if(!is_null($uniqid)) $this->getFromDb($uniqid);
		}

		private function setFromSql($b=true)
		{
			$this->fromSql = (bool) $b;
		}

		public function isFromSql()
		{
			return (int) $this->fromSql;
		}

		private function resetFields()
		{
			$this->fields = array();
		}

		public function getFields()
		{
			return $this->fields;
		}

		public function addField($SQLfieldName, $paramMode = PDO::PARAM_STR, $isUniqueIdentifier = false)
		{
			if(!is_array($this->fields)) self::resetFields();
			$ccName = Fields::toCamelCaseNaming($SQLfieldName);

			$this->fields[] = array('name' => $ccName, 'sql_name' => $SQLfieldName, 'type' => (is_numeric($paramMode)) ? $paramMode : PDO::PARAM_STR, 'uniqid' => $isUniqueIdentifier);
		}

		public function initClassFields()
		{
			foreach($this->fields as $sql_field)
			{
				$goodNaming = $sql_field['name'];
				$this->$goodNaming = null;
			}
		}

		public function save($preserveOldValue=false)
		{
			if($this->fromSql) $this->update();
			else 
			{
				$this->insert($preserveOldValue);
			}

			$this->setFromSql();
		}

		public function get($fieldName)
		{

			foreach($this->fields as $field)
				if($field['name'] == $fieldName) return $this->$fieldName;

			if (DEBUG) echo ('Fatal error : Trying to acces field '.$fieldName.' in class '.get_class($this));
			exit;
		}

		public function set($fieldName, $value)
		{
			foreach($this->fields as $field)
			{
				if($field['name'] == $fieldName)
				{
					$this->$fieldName = $value;
					return ;
				}
			}

			if(DEBUG) echo ('Fatal error : Trying to acces field '.$fieldName.' in class '.get_class($this));
			exit;
		}

		protected function update()
		{
			$DB = DB::getDB();
			$whereList = '';
			$updateList = '';

			$sep = ''; $sepW = '';

			foreach($this->fields as $sql_field)
			{
				if($sql_field['uniqid'])
				{
					$whereList .= $sepW.'`'.$sql_field['sql_name'].'` = :'.$sql_field['sql_name'];
					$sepW = ' AND ';
				}
				else
				{
					$updateList .= $sep.'`'.$sql_field['sql_name'].'` = :'.$sql_field['sql_name'];
					$sep = ', ';
				}
			}


			$tableName = strtolower(get_class($this));
			$tableName = Fields::getSQLTableName($tableName);
			$query = 'UPDATE '.$tableName.' SET '.$updateList.' WHERE '.$whereList;

			$prep = $DB->prepare($query);

			foreach($this->fields as $sql_field)
			{
                $fname = $sql_field['name'];
				$prep->bindValue(':'.$sql_field['sql_name'], $this->$fname, $sql_field['type']);
			}

			$prep->execute();
			self::reportSqlBugIfExists($prep->errorInfo());
		}

		public static function reportSqlBugIfExists($errorArray)
		{
			if($errorArray[0] ==  '0000') return;

			$message= 'SQL_STATE : '.$errorArray[0]."\n<br />";
			$message.= 'Error code : '.$errorArray[1]."\n<br />\n<br />";
			$message.= $errorArray[2];

			if(DEBUG) echo $message;

			Functions::setResponse(500);
		}

		protected function insert($preserveOldValue=false)
		{
			$DB = DB::getDB();
			$listValues = '';
			$values = '';

			$sep = '';

			foreach($this->fields as $sql_field)
			{
				$listValues .= $sep.'`'.$sql_field['sql_name'].'`';
				$values .= $sep.':'.$sql_field['sql_name'];
				$sep = ', ';
			}

			$tableName = strtolower(get_class($this));
			$tableName = Fields::getSQLTableName($tableName);
			$query = 'INSERT INTO '.$tableName.' ('.$listValues.') VALUES('.$values.')';

			$prep = $DB->prepare($query);

			foreach($this->fields as $sql_field)
			{
				$fname = $sql_field['name'];
                $value = (!$sql_field['uniqid'] || $preserveOldValue) ? $this->$fname : 0;
				$prep->bindValue(':'.$sql_field['sql_name'], $value, $sql_field['type']);
			}

			$prep->execute();
			self::reportSqlBugIfExists($prep->errorInfo());

			$lid = $DB->lastInsertId();
			$this->getFromDb($lid);
		}

		public function delete()
		{
			$DB = DB::getDB();
			$whereList = '';
			$sepW = '';

			foreach($this->fields as $sql_field)
			{
				if($sql_field['uniqid'])
				{
					$whereList .= $sepW.'`'.$sql_field['sql_name'].'` = :'.$sql_field['sql_name'];
					$sepW = ' AND ';
				}
			}

			$tableName = strtolower(get_class($this));
			$tableName = Fields::getSQLTableName($tableName);
			$query = 'DELETE FROM '.$tableName.' WHERE '.$whereList;

			$prep = $DB->prepare($query);

			foreach($this->fields as $sql_field)
			{
				if($sql_field['uniqid'])
				{
                    $fname = $sql_field['name'];
					$prep->bindValue($sql_field['sql_name'], $this->$fname, $sql_field['type']);
				}
			}

			$prep->execute();
			self::reportSqlBugIfExists($prep->errorInfo());
		}

		public static function search($whereClause, $params, $orderByClause='', $limitClause='')
		{
			$className = get_called_class();
			$fieldGetter = new $className();
			$fields = $fieldGetter->getFields();

			$DB = DB::getDB();

			$selection = ''; $sep = '';

			foreach($fields as $field)
			{
				$selection .= $sep.'`'.$field['sql_name'].'`';
				$sep = ', ';
			}

			$tableName = strtolower($className);
			$tableName = Fields::getSQLTableName($tableName);
			$query = 'SELECT '.$selection.' FROM '.$tableName.' WHERE '.$whereClause;

			if(!empty($orderByClause)) $query .= ' ORDER BY '.$orderByClause;
			if(!empty($limitClause)) $query .= ' LIMIT '.$limitClause;

			if(self::SEARCH_QUERY_DEBUG) echo $query."<br/>";

			$prep = $DB->prepare($query);

			foreach($params as $param)
			{
				$prep->bindValue($param['id'], $param['value'], (isset($param['type']) ? intval($param['type']) : PDO::PARAM_STR));
				if(self::SEARCH_QUERY_DEBUG) echo 'Binding value '.$param['value'].' to '.$param['id']."<br />";
			}

			if(self::SEARCH_QUERY_DEBUG) echo "<br />";

			$prep->execute();
			$data = $prep->fetchAll();
			self::reportSqlBugIfExists($prep->errorInfo());

			if(!count($data)) return array();

			$result = array();

			foreach($data as $elt)
			{
				$t = new $className();
				foreach($fields as $field)
				{
					$t->set($field['name'], $elt[$field['sql_name']]);
				}
				$t->setFromSql();
				$result[] = $t;
			}

			return $result;
		}

		private function getFromDb($uniqid)
		{
			// Search for uniqid field

			$uniqid_field = null;

			foreach($this->fields as $sql_field)
			{
				if($sql_field['uniqid']) 
				{
					$uniqid_field = $sql_field;
					break;
				}
			}

			if(is_null($uniqid_field))
			{
				if(DEBUG) echo ('Fatal error : Could not find any unique identifier for class '.get_class($this));
				exit;
			}


			$whereClause = $uniqid_field['sql_name'].' = :'.$uniqid_field['sql_name'];
			$params = array(array('id' => ':'.$uniqid_field['sql_name'], 'value' => $uniqid));
			if(isset($uniqid_field['type'])) $params[0]['type'] = $uniqid_field['type'];

			$results = self::search($whereClause, $params);
			if(!count($results)) throw new RuntimeException('There is no `'.get_class().'` corresponding to uniqid '.$uniqid);

			foreach($this->fields as $sql_field)
			{
                $fname = $sql_field['name'];
				$this->$fname = $results[0]->get($sql_field['name']);
			}
		}

		public static function searchForAll($orderByClause = '', $limitClause='')
		{
			$whereClause = '1';
			$params = array();

			return self::search($whereClause, $params, $orderByClause, $limitClause);
		}

		public function secure($fieldName)
		{
			return Functions::secure($this->get($fieldName));
		}

		public function secureEcho($fieldName)
		{
			echo $this->secure($fieldName);
		}

		public function secureReduced($fieldName, $n)
		{
			return Functions::secure(Functions::reduce($this->get($fieldName), $n));
		}

		public function secureEchoReduced($fieldName, $n)
		{
			echo $this->secureReduced($fieldName, $n);
		}
	}

?>
