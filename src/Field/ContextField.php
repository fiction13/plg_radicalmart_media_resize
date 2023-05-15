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

\defined('_JEXEC') or die;

use Joomla\CMS\Form\Field\ListField;

class ContextField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 *
	 * @since  0.0.0
	 */
	protected $type = 'context';

	/**
	 * Name of the layout being used to render the field.
	 *
	 * @var    string
	 *
	 * @since  0.0.0
	 */
	protected $layout = 'plugins.radicalmart_media.resize.field.context';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   0.0.0
	 */
	public function getOptions()
	{
		$options  = parent::getOptions();
		$check    = $this->value ? in_array($this->value, array_column($options, 'value')) : true;

		if (!$check)
		{
			$currentOption = [];

			$tmp        = new \stdClass();
			$tmp->value = $this->value;
			$tmp->text  = $this->value;

			$currentOption[] = $tmp;

			$options = array_merge($options, $currentOption);
		}

		return $options;
	}
}