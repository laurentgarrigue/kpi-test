<?php
/**
 * Part of CodeIgniter Simple and Secure Twig
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-ss-twig
 */
defined('BASEPATH') OR exit('No direct script access allowed');
// If you don't use Composer, uncomment below
/*
require_once APPPATH . 'third_party/Twig-1.xx.x/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
*/
class Twig
{
	/**
	 * @var array Paths to Twig templates
	 */
	private $paths = [];
    
	/**
	 * @var array Twig Environment Options
	 * @see http://twig.sensiolabs.org/doc/api.html#environment-options
	 */
	private $config = [];
    
	/**
	 * @var array Functions to add to Twig
	 */
	private $functions_asis;
    
	/**
	 * @var array Functions with `is_safe` option
	 * @see http://twig.sensiolabs.org/doc/advanced.html#automatic-escaping
	 */
	private $functions_safe;
    
	/**
	 * @var string Template extension
	 */
	private $template_ext;
    
	/**
	 * @var bool Whether functions are added or not
	 */
	private $functions_added = FALSE;
    
	/**
	 * @var Twig_Environment
	 */
	private $twig;
    
	/**
	 * @var Twig_Loader_Filesystem
	 */
	private $loader;
    
	public function __construct($params = [])
	{
		$this->CI =& get_instance();
        $this->_config = $this->CI->config->item('twig');
        
        $this->functions_asis = $this->_config['functions_asis'];
        $this->functions_safe = $this->_config['functions_safe'];
        $this->template_ext = $this->_config['template_ext'];
        
        if (isset($params['functions']))
		{
			$this->functions_asis =
				array_unique(
					array_merge($this->functions_asis, $params['functions'])
				);
			unset($params['functions']);
		}
        
		if (isset($params['functions_safe']))
		{
			$this->functions_safe =
				array_unique(
					array_merge($this->functions_safe, $params['functions_safe'])
				);
			unset($params['functions_safe']);
		}
        
		if (isset($params['paths']))
		{
			$this->paths = $params['paths'];
			unset($params['paths']);
		}
		else
		{
			$this->paths = $this->_config['template_dir'];
		}
        
		// default Twig config
		$this->config = [
			'cache'      => APPPATH . 'cache/twig',
			'debug'      => ENVIRONMENT !== 'production',
			'autoescape' => $this->_config['environment']['autoescape'],
		];
		$this->config = array_merge($this->config, $params);
	}
    
	protected function resetTwig()
	{
		$this->twig = null;
		$this->createTwig();
	}
    
	protected function createTwig()
	{
		// $this->twig is singleton
		if ($this->twig !== null)
		{
			return;
		}
		if ($this->loader === null)
		{
			$this->loader = new \Twig_Loader_Filesystem($this->paths);
		}
		$twig = new \Twig_Environment($this->loader, $this->config);
		if ($this->config['debug'])
		{
			$twig->addExtension(new \Twig_Extension_Debug());
		}
		$this->twig = $twig;
	}
    
	protected function setLoader($loader)
	{
		$this->loader = $loader;
	}
    
	/**
	 * Registers a Global
	 *
	 * @param string $name  The global name
	 * @param mixed  $value The global value
	 */
	public function addGlobal($name, $value)
	{
		$this->createTwig();
		$this->twig->addGlobal($name, $value);
	}
    
	/**
	 * Renders Twig Template and Set Output
	 *
	 * @param string $view   Template filename without `.twig`
	 * @param array  $params Array of parameters to pass to the template
	 */
	public function display($view, $params = [])
	{
		$CI =& get_instance();
		$CI->output->set_output($this->render($view, $params));
	}
    
	/**
	 * Renders Twig Template and Returns as String
	 *
	 * @param string $view   Template filename without `.twig`
	 * @param array  $params Array of parameters to pass to the template
	 * @return string
	 */
    
	public function render($view, $params = [])
	{
		$this->createTwig();
		// We call addFunctions() here, because we must call addFunctions()
		// after loading CodeIgniter functions in a controller.
		$this->addFunctions();
		$view = $view . $this->template_ext;
		return $this->twig->render($view, $params);
	}
    
	protected function addFunctions()
	{
		// Runs only once
		if ($this->functions_added)
		{
			return;
		}
		// as is functions
		foreach ($this->functions_asis as $function)
		{
			if (function_exists($function))
			{
				$this->twig->addFunction(
					new \Twig_SimpleFunction(
						$function,
						$function
					)
				);
			}
		}
		// safe functions
		foreach ($this->functions_safe as $function)
		{
			if (function_exists($function))
			{
				$this->twig->addFunction(
					new \Twig_SimpleFunction(
						$function,
						$function,
						['is_safe' => ['html']]
					)
				);
			}
		}
		// customized functions
		if (function_exists('anchor'))
		{
			$this->twig->addFunction(
				new \Twig_SimpleFunction(
					'anchor',
					[$this, 'safe_anchor'],
					['is_safe' => ['html']]
				)
			);
		}
		$this->functions_added = TRUE;
	}
    
	/**
	 * @param string $uri
	 * @param string $title
	 * @param array  $attributes [changed] only array is acceptable
	 * @return string
	 */
	public function safe_anchor($uri = '', $title = '', $attributes = [])
	{
		$uri = html_escape($uri);
		$title = html_escape($title);
		$new_attr = [];
		foreach ($attributes as $key => $val)
		{
			$new_attr[html_escape($key)] = html_escape($val);
		}
		return anchor($uri, $title, $new_attr);
	}
    
	/**
	 * @return \Twig_Environment
	 */
	public function getTwig()
	{
		$this->createTwig();
		return $this->twig;
	}
}