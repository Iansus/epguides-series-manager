<?php

    if(!defined('MODEL')) die('This page should not be accessed that way');

    if(!defined(BASIC_LOADED)) require_once(MODEL.'basic.php');
    createConst(__FILE__);

    loadClass('model');

    class Serie extends Model
	{
		public static function getPoster($epguidesLink)
		{
			return $epguidesLink.'cast.jpg';
		}
    }

?>
