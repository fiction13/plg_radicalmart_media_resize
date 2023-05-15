<?php
/*
 * @package   plg_radicalmart_media_resize
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2023 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

namespace Joomla\Plugin\RadicalMartMedia\Resize\Provider\Collection;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Plugin\RadicalMartMedia\Resize\Provider\InterfaceProvider;

class YoothemeProvider implements InterfaceProvider
{
	/**
	 * Method for check cache for provider
	 *
	 * @return bool
	 *
	 * @since 0.0.0
	 */
	public function checkCache()
	{
		return false;
	}

	/**
	 * @param $data
	 *
	 * @return string|bool
	 *
	 * @since 0.0.0
	 */
	public function checkInclude()
	{
		// Get the database object and a new query object.
		$db    = Factory::getContainer()->get('DatabaseDriver');
		$query = $db->getQuery(true);

		// Build the query.
		$query->select('*')
			->from($db->quoteName('#__template_styles'))
			->where($db->quoteName('client_id') . ' = 0')
			->where($db->quoteName('home') . ' = 1')
			->where($db->quoteName('template') . ' LIKE '. $db->quote('yootheme%'));

		// Set the query and load the templates.
		$db->setQuery($query);

		return (bool) $db->loadResult();
	}

	/**
	 * @param $data
	 *
	 * @return string|bool
	 *
	 * @since 0.0.0
	 */
	public function generateImage($data)
	{
		$view = (function_exists('\YOOtheme\app')) ? \YOOtheme\app(\YOOtheme\View::class) : false;

		if (!$view)
		{
			return false;
		}

		$path = JPATH_PLUGINS . '/radicalmart_media/resize/src/Provider/Template/yootheme';

		// Render new image
		$data = array(
			'src'    => $data['src'],
			'alt'    => $data['alt'] ?? '',
			'width'  => $data['params']->get('width'),
			'height' => $data['params']->get('height'),
			'attrs'  => $data['attrs'] ?? ''
		);

		return $view($path, $data);
	}
}