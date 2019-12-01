<?php
/**
 * Public home page
 *
 * @author laurent
 */
class Home extends MY_Controller {
    
    /**
     *
     * @var array data to send to Twig 
     */
    public $data;
    
    public function __construct()
    {
        parent::__construct();
        $this->data['page'] = lang('Home');
    }

    public function index()
    {

        $this->twig->display('home.html', $this->data);
    }
    
    /**
     * Changement de langue (ajax)
     * 
     * @param string lang (POST)
     *
     * @return void
     */
    public function lang() {
        if($this->input->is_ajax_request() && in_array($this->input->post('lang'), ['fr', 'en'])) {
            switch ($this->input->post('lang')) {
                case 'fr':
                    $lang = 'french';
                break;
                case 'en':
                    $lang = 'english';
                break;
            }
            $this->session->lang = $lang;
            $response = array('status' => 'OK');
            
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->_display();
            exit;

        } else {
            show_404();
        }
    }
    
}