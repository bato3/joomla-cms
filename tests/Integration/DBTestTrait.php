<?php
/**
 * @package     Joomla.Tests
 * @subpackage  Integrations.tests
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @since   4.0.0
 */
namespace Joomla\Tests\Integration;

use Joomla\Database\DatabaseDriver;

/**
 * DBTestTrait
 *
 * @since   4.0.0
 */
trait DBTestTrait
{
	/**
	 * Driver
	 *
	 * @var string
	 *
	 * @since   4.0.0
	 */
	private $driver;

	/**
	 * @param   mixed   DatabaseDriver  $driver  Driver
	 *
	 * @return void
	 * @since   4.0.0
	 */
	public function setDBDriver(DatabaseDriver $driver)
	{
		$this->driver = $driver;
	}

	/**
	 *
	 * @return mixed
	 *
	 * @since   4.0.0
	 */
	public function getDBDriver():DatabaseDriver
	{
		return $this->driver;
	}

	/**
	 *
	 * @return array
	 *
	 * @since   4.0.0
	 */
	public function getSchemasToLoad():array
	{
		return ['datasets/framework.sql'];
	}

	/**
	 * @param   string   $table       Table
	 * @param   array    $data        Data
	 * @param   string   $message     Message
	 * @param   null     $connection  Connection
	 *
	 *
	 * @return void
	 * @since   4.0.0
	 */
	protected function assertDatabaseHas($table, array $data, $message = '', $connection = null)
	{
	}
}
