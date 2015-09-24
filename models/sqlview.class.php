<?php

	if(!defined('MODEL')) die('This page should not be accessed that way');

	if(!defined(BASIC_LOADED)) require_once(MODEL.'basic.php');
	createConst(__FILE__);

	loadClass('db');
	loadClass('fields');
	loadClass('model');

	abstract class SQLView extends Model
	{
		protected function insert() {
			throw new NotImplementException('No insertion possible for an SQL View');
		}

		protected function update() {
			throw new NotImplementException('No update possible for an SQL View');
		}

		public function save() {
			throw new NotImplementException('No save possible for an SQL View');
		}

		public function delete() {
			throw new NotImplementException('No deletion possible for an SQL View');
		}
	}

?>
