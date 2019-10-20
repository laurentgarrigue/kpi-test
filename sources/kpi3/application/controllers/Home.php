<?php
/**
 * Description of Matchs
 *
 * @author laurent
 */
class Home extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->twig->display('home.html', $this->data);
    }
    
    /**
     * Changement de lange (ajax)
     * 
     * @param string lang (POST)
     *
     * @return void
     */
    public function lang() {
        if($this->input->is_ajax_request() && in_array($this->input->post('lang'), ['french', 'english'])) {
            $this->session->lang = $this->input->post('lang');
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