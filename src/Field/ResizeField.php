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

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\SubformField;
use Joomla\CMS\Form\Form;
use Joomla\Registry\Registry;

class ResizeField extends SubformField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 *
	 * @since  1.0.0
	 */
	protected $type = 'resize';

	/**
	 * Method to attach a Form object to the field.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function setup(\SimpleXMLElement $element, $value, $group = null)
	{
		Factory::getApplication()->getDocument()->addStyleDeclaration(
			'div[data-base-name="resize_size"] {
				display: grid;
				grid-template-columns: 1fr 1fr;
				grid-gap: calc(var(--gutter-x) * 0.5);
				padding-top: 0;
			}
			
			div[data-base-name="resize_size"] .btn-toolbar, div[data-base-name="resize_size"] .control-group:nth-child(2) {
				grid-column: 1 / -1;
			}
			
			div[data-base-name="resize_size"] .control-group {
				margin-bottom: 0;
			}
			'
		);

		return parent::setup($element, $value, $group);
	}

	/**
	 * Binds given data to the subform and its elements.
	 *
	 * @param   Form  $subForm  Form instance of the subform.
	 *
	 * @return  Form[]  Array of Form instances for the rows.
	 *
	 * @since   1.0.0
	 */
	protected function loadSubFormData(Form $subForm)
	{
		$value = $this->value ? (array) $this->value : array();

		// Simple form, just bind the data and return one row.
		if (!$this->multiple)
		{
			$subForm->bind($value);

			return array($subForm);
		}

		// Multiple rows possible: Construct array and bind values to their respective forms.
		$forms = array();
		$value = array_values($value);

		// Show as many rows as we have values, but at least min and at most max.
		$c = max($this->min, min(\count($value), $this->max));

		for ($i = 0; $i < $c; $i++)
		{
			$control = $this->name . '[' . $this->fieldname . $i . ']';

			if (!empty($value[$i]) && isset($value[$i]['context']) && $value[$i]['context'])
			{
				$name    = str_replace('.', '_', $value[$i]['context']);
				$control = $this->name . '[' . $name . ']';
			}

			$itemForm = Form::getInstance($subForm->getName() . $i, $this->formsource, array('control' => $control));

			if (!empty($value[$i]))
			{
				$itemForm->bind($value[$i]);
			}

			$forms[] = $itemForm;
		}

		return $forms;
	}

	/**
	 * Method to post-process a field value.
	 *
	 * @param   mixed     $value  The optional value to use as the default for the field.
	 * @param   string    $group  The optional dot-separated form group path on which to find the field.
	 * @param   Registry  $input  An optional Registry object with the entire data set to filter
	 *                            against the entire form.
	 *
	 * @return  mixed   The processed value.
	 *
	 * @since   1.0.0
	 */
	public function postProcess($value, $group = null, Registry $input = null)
	{

		if ($value)
		{
			foreach ($value as $key => $val)
			{
				$context = str_replace('.', '_', $val->context);

				if ($key !== $context)
				{
					unset($value->$key);

					if (!isset($value->{"$context"}))
					{
						$value->$context = $val;
					}
				}
			}
		}

		return $value;
	}
}