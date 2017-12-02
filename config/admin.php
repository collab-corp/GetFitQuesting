<?php return [
	/**
	 * The emails of the site admins.
	 * @return array
	 */
	'emails' => explode(', ', env('ADMIN_EMAILS', ''))
];