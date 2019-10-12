<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('is_admin'))
{
    function is_admin($id = FALSE)
    {
        $CI = &get_instance();
        return $CI->ion_auth->is_admin($id);
    }
}

if ( ! function_exists('in_group'))
{
    function in_group($group, $id = FALSE, $all = FALSE)
    {
        $CI = &get_instance();
        return $CI->ion_auth->in_group($group, $id, $all);
    }
}

