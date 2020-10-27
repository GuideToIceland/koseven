<?php

class Kohana_CSRF_Form extends Kohana_Form {
	
	/**
	 * Create an Input with CSRF token to insert into forms
	 *
	 * @param String $token_name 'kohana-csrf'
	 * @return String - input type="hidden"
	 */
	public static function hidden($token_name, $namespace = NULL, array $attributes = NULL)
	{
		return parent::hidden($token_name, CSRF::get());
	}
}
