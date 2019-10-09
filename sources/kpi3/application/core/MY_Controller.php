<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    /**
     *
     * @var array data to send to Twig 
     */
    protected $data = [];
    
    function __construct() {
        parent::__construct();
        $this->load->add_package_path(APPPATH.'third_party/ion_auth/');
        $this->load->library('ion_auth');
        $this->load->model('matchs_model');
        $this->session->lang = 'french';
        $this->lang->load('kpi', $this->session->lang);
        $this->load->library('menu');
        
        $this->data['menu_public'] = $this->menu->menu_public();
        if ($this->ion_auth->logged_in()) {
            $this->data['menu_admin'] = $this->menu->menu_admin();
            $user = $this->ion_auth->user()->row();
            $user_groups = $this->ion_auth->get_users_groups()->result();
            $this->data['user']['first_name'] = $user->first_name;
            $this->data['user']['last_name'] = $user->last_name;
            $this->data['user']['groups'] = $user_groups;
        }
        
        $this->output->enable_profiler(TRUE);
    }

}
