<?php
/*
 * @package   plg_radicalmart_media_resize
 * @version   1.0.0
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
	 * @since  0.0.0
	 */
	protected $type = 'provider';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @throws  \Exception
	 *
	 * @since  0.0.0
	 */
	protected function getOptions()
	{
		$options = [];
		$path    = JPATH_PLUGINS . '/radicalmart_media/resize/src/Provider/Collection';
		$files   = Folder::files($path, '.php');

		foreach ($files as $file)
		{
			$provider   = str_replace('Provider', '', lcfirst(File::stripExt($file)));
			$class_name = '\\Joomla\\Plugin\\RadicalMartMedia\\Resize\\Provider\\Collection\\' . ucfirst($provider) . 'Provider';

			if (!class_exists($class_name))
			{
				continue;
			}

			$providerClass = new $class_name();

			if (!$providerClass->checkInclude())
			{
				continue;
			}

			$option        = new stdClass();
			$option->value = $file;
			$option->text  = ucfirst($provider);
			$options[]     = $option;
		}

		return array_merge(parent::getOptions(), $options);
	}
}