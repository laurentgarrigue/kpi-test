<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// if ( ! function_exists('selectpicker_array_format'))
// {
//     function selectpicker_array_format($array, )
// 	{
//         foreach ($array as $key=>$value) {
//             $result[$value->Code] = $value->Code;
//         }
//     }
// }
// ------------------------------------------------------------------------

if ( ! function_exists('form_selectpicker'))
{
	/**
	 * Drop-down Menu
	 *
	 * @param	mixed	$data
	 * @param	mixed	$options
	 * @param	mixed	$selected
	 * @param	mixed	$extra
	 * @return	string
	 */
	function form_selectpicker($data = '', $options = array(), $selected = array(), $extra = '')
	{
		$defaults = array();

		if (is_array($data))
		{
			if (isset($data['selected']))
			{
				$selected = $data['selected'];
				unset($data['selected']); // select tags don't have a selected attribute
			}

			if (isset($data['options']))
			{
				$options = $data['options'];
				unset($data['options']); // select tags don't use an options attribute
			}
		}
		else
		{
			$defaults = array('name' => $data);
		}

		is_array($selected) OR $selected = array($selected);
		is_array($options) OR $options = array($options);

		// If no selected state was submitted we will attempt to set it automatically
		if (empty($selected))
		{
			if (is_array($data))
			{
				if (isset($data['name'], $_POST[$data['name']]))
				{
					$selected = array($_POST[$data['name']]);
				}
			}
			elseif (isset($_POST[$data]))
			{
				$selected = array($_POST[$data]);
			}
		}

		$extra = _attributes_to_string($extra);

		$multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select '.rtrim(_parse_form_attributes($data, $defaults)).$extra.$multiple.">\n";

		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val))
			{
				if (empty($val))
				{
					continue;
				}

				$form .= '<optgroup label="'.$key."\">\n";

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
					$form .= '<option value="'.html_escape($optgroup_key).'"'.$sel.'>'
						.(string) $optgroup_val."</option>\n";
				}

				$form .= "</optgroup>\n";
			}
			else
			{
				$form .= '<option value="'.html_escape($key).'"'
					.(in_array($key, $selected) ? ' selected="selected"' : '').'>'
					.(string) $val."</option>\n";
			}
		}

		return $form."</select>\n";
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('form_selectpicker2'))
{
	/**
	 * Drop-down Menu with optgroups
	 *
	 * @param	mixed	$data
	 * @param	mixed	$options
	 * @param	mixed	$selected
	 * @param	mixed	$extra
	 * @return	string
	 */
	function form_selectpicker2($data = '', $options = array(), $selected = array(), $extra = '')
	{
		$defaults = array();

		if (is_array($data))
		{
			if (isset($data['selected']))
			{
				$selected = $data['selected'];
				unset($data['selected']); // select tags don't have a selected attribute
			}

			if (isset($data['options']))
			{
				$options = $data['options'];
				unset($data['options']); // select tags don't use an options attribute
			}
		}
		else
		{
			$defaults = array('name' => $data);
		}

		is_array($selected) OR $selected = array($selected);
		is_array($options) OR $options = array($options);

		// If no selected state was submitted we will attempt to set it automatically
		if (empty($selected))
		{
			if (is_array($data))
			{
				if (isset($data['name'], $_POST[$data['name']]))
				{
					$selected = array($_POST[$data['name']]);
				}
			}
			elseif (isset($_POST[$data]))
			{
				$selected = array($_POST[$data]);
			}
		}

		$extra = _attributes_to_string($extra);

		$multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select '.rtrim(_parse_form_attributes($data, $defaults)).$extra.$multiple.">\n";

		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val))
			{
				if (empty($val))
				{
					continue;
				}

                if (isset($val['label'])) {
                    $form .= '<optgroup label="'.$val['label']."\">\n";
                } else {
                    $form .= '<optgroup label="'.$key."\">\n";
                }

                if (isset($val['options'])) {
                    foreach ($val['options'] as $optgroup_val)
                    {
                        $sel = in_array($optgroup_val['value'], $selected) ? ' selected="selected"' : '';
                        $class = isset($optgroup_val['class']) ? ' class="' . html_escape($optgroup_val['class']) . '"' : '';
                        $title = isset($optgroup_val['title']) ? ' title="' . html_escape($optgroup_val['title']) . '"' : '';
                        $data_tokens = isset($optgroup_val['data-tokens']) ? ' data-tokens="' . html_escape($optgroup_val['data-tokens']) . '"' : '';
                        $label = isset($optgroup_val['label']) ? html_escape($optgroup_val['label']) : (string) $optgroup_val['value'];
                        $form .= '<option value="'.html_escape($optgroup_val['value']).'"'.$class.$title.$data_tokens.$sel.'>'
                            .$label."</option>\n";
                    }
                } else {
                    foreach ($val as $optgroup_key => $optgroup_val)
                    {
                        $sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
                        $form .= '<option value="'.html_escape($optgroup_key).'"'.$sel.'>'
                            .(string) $optgroup_val."</option>\n";
                    }
                }

				$form .= "</optgroup>\n";
			}
			else
			{
				$form .= '<option value="'.html_escape($key).'"'
					.(in_array($key, $selected) ? ' selected="selected"' : '').'>'
					.(string) $val."</option>\n";
			}
		}

		return $form."</select>\n";
	}
}

// ------------------------------------------------------------------------