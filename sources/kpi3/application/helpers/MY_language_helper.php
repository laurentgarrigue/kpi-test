<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Language Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/language_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('lang'))
{
	/**
	 * Lang
	 *
	 * Fetches a language variable and optionally outputs a form label
	 *
	 * @param	string	$line		The language line
	 * @param	string	$for		The "for" value (id of the form element)
	 * @param	array	$attributes	Any additional HTML attributes
	 * @return	string
	 */
	function lang($line, $for = '', $attributes = array())
	{
		if(!$line2 = get_instance()->lang->line($line)) {
            // Si pas de traduction disponible, retourne l'index
            return $line;
        }

		if ($for !== '')
		{
			$line2 = '<label for="'.$for.'"'._stringify_attributes($attributes).'>'.$line2.'</label>';
		}

		return $line2;
	}
}
