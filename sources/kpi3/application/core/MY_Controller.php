<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    /**
     *
     * @var array data to send to Twig 
     */
    protected $data = [];
    
    /**
     *
     * @var array user  
     */
    protected $user = [];
    
    function __construct() {
        parent::__construct();

        switch ($this->session->lang) {
            case 'english':
                $this->session->i18n = 'en_US';
                break;
            case 'french':
                $this->session->i18n = 'fr_FR';
                break;
            case NULL:
                $this->session->lang = 'french';
                $this->session->i18n = 'fr_FR';
                break;
        }
        $this->lang->load('kpi', $this->session->lang);
        $this->data['i18n'] = $this->session->i18n;

        $this->load->add_package_path(APPPATH.'third_party/ion_auth/');
        $this->load->library('ion_auth');

        $this->load->library('menu');
        $this->data['menu_public'] = $this->menu->menu_public();
        if ($this->ion_auth->logged_in()) {
            $this->data['menu_admin'] = $this->menu->menu_admin();
            $this->data['current_user'] = $this->session->user;
        }
        
        if (!$this->input->is_ajax_request()) {
            $this->output->enable_profiler(TRUE);
        }
    }


}
