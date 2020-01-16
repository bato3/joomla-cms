<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Users\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

/**
 * Users component debugging helper.
 *
 * @since  1.6
 */
class DebugHelper
{
	/**
	 * Get a list of the components.
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public static function getComponents()
	{
		// Initialise variable.
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('name AS text, element AS value')
			->from('#__extensions')
			->where('enabled >= 1')
			->where('type =' . $db->quote('component'));

		$items = $db->setQuery($query)->loadObjectList();

		if (count($items))
		{
			$lang = Factory::getLanguage();

			foreach ($items as &$item)
			{
				// Load language
				$extension = $item->value;
				$source = JPATH_ADMINISTRATOR . '/components/' . $extension;
				$lang->load("$extension.sys", JPATH_ADMINISTRATOR, null, false, true)
					|| $lang->load("$extension.sys", $source, null, false, true);

				// Translate component name
				$item->text = Text::_($item->text);
			}

			// Sort by component name
			$items = ArrayHelper::sortObjects($items, 'text', 1, true, true);
		}

		return $items;
	}

	/**
	 * Get a list of the actions for the component or code actions.
	 *
	 * @param   string  $component  The name of the component.
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public static function getDebugActions($component = null)
	{
		$actions = array();

		// Try to get actions for the component
		if (!empty($component))
		{
			$component_actions = Access::getActionsFromFile(JPATH_ADMINISTRATOR . '/components/' . $component . '/access.xml');

			if (!empty($component_actions))
			{
				foreach ($component_actions as &$action)
				{
					$descr = (string) $action->title;

					if (!empty($action->description))
					{
						$descr = (string) $action->description;
					}

					$actions[$action->title] = array($action->name, $descr);
				}
			}
		}

		// Use default actions from configuration if no component selected or component doesn't have actions
		if (empty($actions))
		{
			$filename = JPATH_ADMINISTRATOR . '/components/com_config/forms/application.xml';

			if (is_file($filename))
			{
				$xml = simplexml_load_file($filename);

				foreach ($xml->children()->fieldset as $fieldset)
				{
					if ('permissions' == (string) $fieldset['name'])
					{
						foreach ($fieldset->children() as $field)
						{
							if ('rules' == (string) $field['name'])
							{
								foreach ($field->children() as $action)
								{
									$descr = (string) $action['title'];

									if (isset($action['description']) && !empty($action['description']))
									{
										$descr = (string) $action['description'];
									}

									$actions[(string) $action['title']] = array(
										(string) $action['name'],
										$descr
									);
								}

								break;
							}
						}
					}
				}

				// Load language
				$lang = Factory::getLanguage();
				$extension = 'com_config';
				$source = JPATH_ADMINISTRATOR . '/components/' . $extension;

				$lang->load($extension, JPATH_ADMINISTRATOR, null, false, false)
					|| $lang->load($extension, $source, null, false, false)
					|| $lang->load($extension, JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
					|| $lang->load($extension, $source, $lang->getDefault(), false, false);
			}
		}

		return $actions;
	}

	/**
	 * Get a list of filter options for the levels.
	 *
	 * @return  array  An array of \JHtmlOption elements.
	 */
	public static function getLevelsOptions()
	{
		// Build the filter options.
		$options = array();
		$options[] = HTMLHelper::_('select.option', '1', Text::sprintf('COM_USERS_OPTION_LEVEL_COMPONENT', 1));
		$options[] = HTMLHelper::_('select.option', '2', Text::sprintf('COM_USERS_OPTION_LEVEL_CATEGORY', 2));
		$options[] = HTMLHelper::_('select.option', '3', Text::sprintf('COM_USERS_OPTION_LEVEL_DEEPER', 3));
		$options[] = HTMLHelper::_('select.option', '4', '4');
		$options[] = HTMLHelper::_('select.option', '5', '5');
		$options[] = HTMLHelper::_('select.option', '6', '6');

		return $options;
	}
}