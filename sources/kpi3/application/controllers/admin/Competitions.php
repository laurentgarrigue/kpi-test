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
    protected $data;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['page'] = 'Competitions';

        $this->twig->display('home.html', $this->data);
    }
    
}