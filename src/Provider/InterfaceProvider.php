<?php
/*
 * @package   plg_radicalmart_media_resize
 * @version   1.0.1
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2023 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

namespace Joomla\Plugin\RadicalMartMedia\Resize\Provider;

defined('_JEXEC') or die;

interface InterfaceProvider
{
	public function generateImage($data);

	public function checkCache();

	public function checkInclude();
}