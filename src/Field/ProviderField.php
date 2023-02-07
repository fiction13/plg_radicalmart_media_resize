<?php
/*
 * @package   plg_radicalmart_media_resize
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2023 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

namespace Joomla\Plugin\RadicalMartMedia\Resize\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Form\Field\ListField;
use Joomla\Filesystem\File;
use stdClass;

class ProviderField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $type = 'provider';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @throws  \Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function getOptions()
	{
		$options = [];
		$path    = JPATH_PLUGINS . '/radicalmart_media/resize/src/Provider/Collection';
		$files   = Folder::files($path, '.php');

		foreach ($files as $file)
		{
			$file          = str_replace('Provider', '', lcfirst(File::stripExt($file)));
			$option        = new stdClass();
			$option->value = $file;
			$option->text  = ucfirst($file);
			$options[]     = $option;
		}

		return array_merge(parent::getOptions(), $options);
	}
}