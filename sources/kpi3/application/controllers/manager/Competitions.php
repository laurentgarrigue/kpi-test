<?php
/**
 * Description of Competitions
 *
 * @author laurent
 */
class Competitions extends MY_Controller {
    
    /**
     *
     * @var array data to send to Twig 
     */
    public $data;
    
    public function __construct()
    {
        parent::__construct();
        $this->data['page'] = 'Competitions';
    }

    public function index()
    {

        $this->twig->display('home.html', $this->data);
    }
    
}