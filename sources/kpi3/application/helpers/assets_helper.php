<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('css_url'))
{
    function css_url($nom)
    {
        return base_url() . 'assets/css/' . $nom . '.css?='.version();
    }
}

if ( ! function_exists('lib_url'))
{
    function lib_url($nom)
    {
        return base_url() . 'assets/libraries/' . $nom . '?='.version();
    }
}

if ( ! function_exists('js_url'))
{
    function js_url($nom)
    {
        return base_url() . 'assets/javascript/' . $nom . '.js?='.version();
    }
}

if ( ! function_exists('img_url'))
{
    function img_url($nom)
    {
        return base_url() . 'assets/images/' . $nom;
    }
}

if ( ! function_exists('font_url'))
{
    function font_url($nom)
    {
        return base_url() . 'assets/fonts/' . $nom;
    }
}

if ( ! function_exists('img'))
{
    function img($nom, $alt = '')
    {
        return '<img src="' . img_url($nom) . '" alt="' . $alt . '" />';
    }
}

if ( ! function_exists('version'))
{
    function version()
    {
        return VERSION;
    }
}

if ( ! function_exists('titre_appli'))
{
    function titre_appli()
    {
        return TITRE_APPLI;
    }
}