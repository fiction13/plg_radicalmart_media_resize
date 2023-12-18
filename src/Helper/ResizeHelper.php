<?php
/*
 * @package   plg_radicalmart_media_resize
 * @version   1.0.1
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2023 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

namespace Joomla\Plugin\RadicalMartMedia\Resize\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\Registry\Registry;
use Joomla\CMS\Factory;

class ResizeHelper
{
	/**
	 * @since 1.0.0
	 */
	protected static $instance;

	/**
	 * Global application object
	 *
	 * @var CMSApplicationInterface
	 *
	 * @since  1.0.0
	 */
	public $app = null;

	/**
	 * Component params
	 *
	 * @var Registry
	 *
	 * @since  1.0.0
	 */
	public $componentParams;

	/**
	 * Caching image params
	 *
	 * @var array
	 *
	 * @since  1.0.0
	 */
	public static $imageParams = null;

	/**
	 * @param   Registry  $params
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->app             = Factory::getApplication();
		$this->componentParams = ComponentHelper::getParams('com_radicalmart');
	}

	/**
	 *
	 * @return mixed|ResizeHelper
	 *
	 * @since 1.0.0
	 */
	public static function getInstance()
	{
		if (is_null(static::$instance))
		{
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Method for get image params from component config
	 *
	 * @param   string  $names
	 * @param   string  $items
	 *
	 * @return Registry|null
	 */
	public function getImageParams($context)
	{
		$context = str_replace('.', '_', $context);

		if (!isset(self::$imageParams[$context]))
		{
			$extraParam = 0;

			// Get category in category view
			if ($this->app->input->getCmd('view') === 'category')
			{
				$extraParam = $this->app->input->getInt('id');
			}

			// Get category in product view
			if ($this->app->input->getCmd('view') === 'product')
			{
				$extraParam = $this->app->input->getInt('category');
			}

			$sizes       = $this->componentParams->extract('resize_size');
			$imageParams = $sizes->get($context . '.' . $extraParam, $sizes->get($context));

			self::$imageParams[$context] = new Registry($imageParams);
		}

		return self::$imageParams[$context];
	}
}