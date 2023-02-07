<?php
/*
 * @package   plg_radicalmart_media_resize
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2023 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

namespace Joomla\Plugin\RadicalMartMedia\Resize\Helper;

use Joomla\Plugin\RadicalMartMedia\Resize\Provider\InterfaceProvider;

defined('_JEXEC') or die;

class ProviderHelper
{
	/**
	 * Method for get provider class
	 *
	 * @param  string  $provider  Provider type
	 *
	 * @return InterfaceProvider|void
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public static function getProvider(string $provider)
	{
		if (empty($provider))
		{
			return;
		}

		$class_name = '\\Joomla\\Plugin\\RadicalMartMedia\\Resize\\Provider\\Collection\\' . ucfirst($provider) . 'Provider';

		if (!class_exists($class_name))
		{
			return;
		}

		return new $class_name();
	}
}