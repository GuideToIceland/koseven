<?php

class Kohana_CSRF {

	/**
	 * Check for existing or generate CSRF token 
	 *
	 * @return String - CSRF token
	 */
	public static function get()
	{
		$token = Session::instance()->get('kohana-csrf');
		if ( ! $token)
		{
			if (function_exists('mcrypt_create_iv'))
			{
				$token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
			}
			else
			{
				$token = bin2hex(openssl_random_pseudo_bytes(32));
			}

			Session::instance()->set('kohana-csrf', $token);
		}
		if ( ! Helper_Cookie::get('X-CSRF-token'))
		{
			Helper_Cookie::set('X-CSRF-token', $token);
		}
		return $token;
	}

	/**
	 * Delete token 
	 *
	 */
	public static function clear()
	{
		Session::instance()->delete('kohana-csrf');
		self::get();
	}

	/**
	 * Check CSRF token
	 *
	 * @param Array $values Data from reguest to check token
	 * @param boolean $purge delete token after check if TRUE
	 *
	 * @return boolean  Is token valid
	 */
	public static function check($values, $purge = TRUE)
	{
		$token = self::get();
		$valid = (isset($values['kohana-csrf']) AND hash_equals($token, $values['kohana-csrf']));
		if ( ! $valid AND $purge) 
		{
			self::clear();
		}

		return $valid;
	}
}
