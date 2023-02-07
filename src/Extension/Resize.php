<?php
/*
 * @package   plg_radicalmart_media_resize
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2023 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

namespace Joomla\Plugin\RadicalMartMedia\Resize\Extension;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\DispatcherInterface;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;
use Joomla\Plugin\RadicalMartMedia\Resize\Helper\ProviderHelper;
use Joomla\Plugin\RadicalMartMedia\Resize\Helper\ResizeHelper;

class Resize extends CMSPlugin implements SubscriberInterface
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    bool
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $autoloadLanguage = true;

	/**
	 * Loads the application object.
	 *
	 * @var  \Joomla\CMS\Application\CMSApplication
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $app = null;

	/**
	 * Loads the database object.
	 *
	 * @var  \Joomla\Database\DatabaseDriver
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $db = null;

	/**
	 * The cascadehelper
	 *
	 * @var    ResizeHelper
	 * @since  __DEPLOY_VERSION__
	 */
	protected $_name = null;

	/**
	 * Constructor
	 *
	 * @param   DispatcherInterface  &$subject  The object to observe
	 * @param   array                 $config   An optional associative array of configuration settings.
	 *                                          Recognized key values include 'name', 'group', 'params', 'language'
	 *                                          (this list is not meant to be comprehensive).
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onRadicalMartPrepareConfigForm' => 'onRadicalMartPrepareConfigForm',
			'onRadicalMartRenderImage'       => 'onRadicalMartRenderImage'
		];
	}

	/**
	 * Method to change forms.
	 *
	 * @param   Event  $event  The event.
	 *
	 * @throws  \Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onRadicalMartPrepareConfigForm(Event $event)
	{
		$form = $event->getArgument(0);

		Form::addFormPath(JPATH_PLUGINS . '/' . $this->_type . '/' . $this->_name . '/forms');
		$form->loadFile('config');
	}

	/**
	 * Method to change forms.
	 *
	 * @param   Event  $event  The event.
	 *
	 * @throws  \Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onRadicalMartRenderImage(Event $event)
	{
		$componentParams = ComponentHelper::getParams('com_radicalmart');

		$context  = $event->getArgument(0);
		$html     = $event->getArgument(1);
		$src      = $event->getArgument(2);
		$attribs  = $event->getArgument(3);
		$data     = $event->getArgument(4);
		$provider = $componentParams->get('resize_provider');
		$image    = null;

		if (!$provider)
		{
			return false;
		}

		$imageParams = ResizeHelper::getInstance()->getImageParams($context);

		if (!$imageParams->get('width') && !$imageParams->get('height'))
		{
			return false;
		}

		$providerClass = ProviderHelper::getProvider($provider);

		if (!$providerClass)
		{
			return false;
		}

		// Image alt
		if (isset($attribs['alt']))
		{
			$alt = (!empty($attribs['alt'])) ? $attribs['alt'] : '';
			unset($attribs['alt']);
		}

		$data = array(
			'src'    => $src,
			'alt'    => $alt,
			'attrs'  => $attribs,
			'path'   => $componentParams->get('resize_path', 'images/radicalmart_media_resize'),
			'params' => $imageParams
		);

		if ($providerClass->checkCache())
		{
			$filePath      = Path::clean(JPATH_ROOT . '/' . ltrim($src, '/'), '/');
			$width         = $imageParams->get('width');
			$height        = $imageParams->get('height');
			$fileInfo      = pathinfo($filePath);
			$md5           = md5($src . $width . $height . $imageParams->get('crop'));
			$subfolder     = substr($md5, 0, 2);
			$cacheFolder   = ltrim($componentParams->get('resize_path', 'images/radicalmart_media_resize'), '/');
			$thumbfile     = $cacheFolder . '/' . $subfolder . '/' . $fileInfo['filename'] . '_' . $width . 'x' . $height . '.' . $fileInfo['extension'];
			$thumbfilePath = Path::clean(JPATH_ROOT . '/' . $thumbfile);

			if (!is_file($thumbfilePath) || filemtime($filePath) > filemtime($thumbfilePath))
			{
				$data['thumb'] = $thumbfilePath;

				$providerClass->generateImage($data);
			}

			$image = HTMLHelper::image($thumbfile, $alt, $attribs);
		}
		else
		{
			$image = $providerClass->generateImage($data);
		}

		if ($image)
		{
			$event->setArgument(1, $image);
		}

		return true;
	}
}