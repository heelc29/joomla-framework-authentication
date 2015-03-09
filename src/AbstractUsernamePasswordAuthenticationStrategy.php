<?php
/**
 * Part of the Joomla Framework Authentication Package
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Authentication;

/**
 * Abstract AuthenticationStrategy for username/password based authentication
 *
 * @since  __DEPLOY_VERSION__
 */
abstract class AbstractUsernamePasswordAuthenticationStrategy implements AuthenticationStrategyInterface
{
	/**
	 * The last authentication status.
	 *
	 * @var    integer
	 * @since  __DEPLOY_VERSION__
	 */
	protected $status;

	/**
	 * Attempt to authenticate the username and password pair.
	 *
	 * @param   string  $username  The username to authenticate.
	 * @param   string  $password  The password to attempt authentication with.
	 *
	 * @return  string|boolean  A string containing a username if authentication is successful, false otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function doAuthenticate($username, $password)
	{
		$hashedPassword = $this->getHashedPassword($username);

		if ($hashedPassword === false)
		{
			$this->status = Authentication::NO_SUCH_USER;

			return false;
		}

		if (!$this->verifyPassword($username, $password, $hashedPassword))
		{
			$this->status = Authentication::INVALID_CREDENTIALS;

			return false;
		}

		$this->status = Authentication::SUCCESS;

		return $username;
	}

	/**
	 * Retrieve the hashed password for the specified user.
	 *
	 * @param   string  $username  Username to lookup.
	 *
	 * @return  string|boolean  Hashed password on success or boolean false on failure.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	abstract protected function getHashedPassword($username);

	/**
	 * Get the status of the last authentication attempt.
	 *
	 * @return  integer  Authentication class constant result.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getResult()
	{
		return $this->status;
	}

	/**
	 * Attempt to verify the username and password pair.
	 *
	 * @param   string  $username        The username to authenticate.
	 * @param   string  $password        The password to attempt authentication with.
	 * @param   string  $hashedPassword  The hashed password to attempt authentication against.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function verifyPassword($username, $password, $hashedPassword)
	{
		return password_verify($password, $hashedPassword);
	}
}
