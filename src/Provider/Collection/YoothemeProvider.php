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

use Joomla\CMS\Filesystem\File;
use Joomla\Plugin\RadicalMartMedia\Resize\Provider\InterfaceProvider;

class YoothemeProvider implements InterfaceProvider
{
	/**
	 * Method for check cache for provider
	 *
	 * @return bool
	 *
	 * @since __DEPLOY_VERSION__
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
	 * @since __DEPLOY_VERSION__
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