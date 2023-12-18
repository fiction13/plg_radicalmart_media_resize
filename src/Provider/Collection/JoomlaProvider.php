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
use Joomla\CMS\Image\Image;
use Joomla\Filesystem\Path;
use Joomla\Plugin\RadicalMartMedia\Resize\Provider\InterfaceProvider;
use Joomla\Registry\Registry;

class JoomlaProvider implements InterfaceProvider
{
	/**
	 * Method for check cache for provider
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function checkCache()
	{
		return true;
	}

	/**
	 * @param $data
	 *
	 * @return string|bool
	 *
	 * @since 1.0.0
	 */
	public function checkInclude()
	{
		return true;
	}

	/**
	 * @param   array  $params
	 *
	 * @return string|bool
	 *
	 * @since 1.0.0
	 */
	public function generateImage($data)
	{
		$imagePath = Path::clean(JPATH_ROOT . '/' . ltrim($data['src'], '/'), '/');
		$params    = $data['params'];
		$image     = new Image($imagePath);
		$crop      = $params->get('crop', 'crop');

		if ($crop === 'crop')
		{
			$imageObj = $image->cropResize($params->get('width'), $params->get('height'));
		}
		else
		{
			$imageObj = $image->resize($params->get('width'), $params->get('height'));
		}

		$props = $image::getImageFileProperties($imagePath);

		ob_start();
		$imageObj->toFile(null, $props->type);

		$output = ob_get_contents();
		ob_end_clean();
		File::write($data['thumb'], $output);

		$image->destroy();

		return true;
	}
}