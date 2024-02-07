<?php
/*
 * @package   plg_radicalmart_media_resize
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2023 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

defined('_JEXEC') or die;

/**
 * Templates variables.
 * -----------------
 * @var  string $src    Image source.
 * @var  string $alt    Image alt.
 * @var  int    $width  Image width.
 * @var  int    $height Image height.
 * @var  array  $attrs  Image attributes.
 * @var string  $crop   Image crop
 */

$image = $this->el('image', [
	'src'       => $src,
	'alt'       => $alt,
	'loading'   => null,
	'width'     => $width,
	'height'    => $height,
	'thumbnail' => true,
	'crop'      => $crop
]);

// Image
$image->attr(
	array_merge(
		[
			'uk-img'      => true,
			'data-width'  => $width,
			'data-height' => $height
		], $attrs
	)
);

// Display image
echo $image->render();