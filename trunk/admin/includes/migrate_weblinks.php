<?php
/**
 * jUpgrade
 *
 * @version		  $Id$
 * @package		  MatWare
 * @subpackage	com_jupgrade
 * @author      Matias Aguirre <maguirre@matware.com.ar>
 * @link        http://www.matware.com.ar
 * @copyright		Copyright 2006 - 2011 Matias Aguire. All rights reserved.
 * @license		  GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Upgrade class for Weblinks
 *
 * This class takes the weblinks from the existing site and inserts them into the new site.
 *
 * @since	0.4.5
 */
class jUpgradeWeblinks extends jUpgrade
{
	/**
	 * @var		string	The name of the source database table.
	 * @since	0.4.5
	 */
	protected $source = '#__weblinks';

	/**
	 * @var		string	The name of the source database table.
	 * @since	0.4.4
	 */
	protected $_tbl_key = 'id';

	/**
	 * Setting the conditions hook
	 *
	 * @return	void
	 * @since	3.0.0
	 * @throws	Exception
	 */
	public function getConditionsHook()
	{
		$conditions = array();

		$conditions['where'] = false;

		$conditions['select'] = '`id`, `catid`, `sid`, `title`, `alias`, `url`, `description`, `date`, `hits`, '
     .' `published` AS state, `checked_out`, `checked_out_time`, `ordering`, `archived`, `approved`,`params`';
				
		return $conditions;
	}

	/**
	 * Get the raw data for this part of the upgrade.
	 *
	 * @return	array	Returns a reference to the source data array.
	 * @since	0.4.5
	 * @throws	Exception
	 */
	public function &getSourceDatabase()
	{
		// Getting the rows
		$rows = parent::getSourceDatabase();

		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row['params'] = $this->convertParams($row['params']);
		}

		return $rows;
	}
	
	/**
	 * Sets the data in the destination database.
	 *
	 * @return	void
	 * @since	3.0.
	 * @throws	Exception
	 */
	protected function setDestinationData()
	{
		// Getting the component parameter with global settings
		$params = $this->getParams();

		// Get the source data.
		$rows = $this->loadData('weblinks');

		// Getting the categories id's
		$categories = $this->getMapList('categories', 'com_weblinks');

		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			// Convert the array into an object.
			$row = (object) $row;

			$cid = $row->catid;
			$row->catid = &$categories[$cid]->new;
		}

		parent::setDestinationData($rows);
	}
}
