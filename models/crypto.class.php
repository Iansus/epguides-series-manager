<?php

    if(!defined('MODEL')) die('This page should not be accessed that way');

    if(!defined(BASIC_LOADED)) require_once(MODEL.'basic.php');
    createConst(__FILE__);

    loadClass('model');

    class Crypto extends Model
	{

		// Returns array(IV, HASH)
		// When input IV is NULL (default), generates new IV
		public static function hashPassword($password, $inbase64iv = NULL)
		{
			$ITERATION_COUNT = 10000;
			// Input vector generation
			if(is_null($inbase64iv))
			{
				$iv = openssl_random_pseudo_bytes(64);
				$base64iv = base64_encode($iv);
			}
			else
			{
				$iv = base64_decode($inbase64iv);
				$base64iv = $inbase64iv;
			}

			// Iterate hashing function
			$inputText = $password;
			for($i = 0; $i < $ITERATION_COUNT; $i++)
				$inputText = hash_hmac('sha256', $inputText, $iv);

			return array($base64iv,$inputText);
		}

	}

?>
