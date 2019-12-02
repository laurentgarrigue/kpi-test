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
        if (isset($this->session->message)) {
            $messages[] = [ 'context' => 'primary', 'content' => $this->session->message];
        }
    }

    public function index()
    {
        
        $this->data['messages'] = json_encode($messages);
        $this->twig->display('home.html', $this->data);
    }
    
}