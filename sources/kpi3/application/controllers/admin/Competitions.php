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
        $this->data['title'] = 'Compétitions';
        $this->data['matchs'] = $this->matchs_model->get_matchs();

        $this->twig->display('tests/index.html', $this->data);
    }
    
}