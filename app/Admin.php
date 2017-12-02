<?php

namespace App;

class Admin 
{	
	/**
	 * Array of admin emails.
	 * 
	 * @return array
	 */
	public static function emails()
	{
		return config('admin.emails');
	}

	/**
	 * Whether the given user is an admin.
	 * 
	 * @param  object|string $user
	 * @return boolean       
	 */
	public static function check($user)
	{
		if (is_object($user)) {
			$user = $user->email;
		}

		if (is_email($user)) {
			return in_array($user, static::emails(), true);
		}

		return false;
	}
}