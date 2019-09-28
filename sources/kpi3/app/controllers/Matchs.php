<?php
/**
 * Description of Matchs
 *
 * @author laurent
 */
class Matchs extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('matchs_model');
        $this->load->helper('url_helper');
        $this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        $data['title'] = 'Liste des matchs';
        $data['matchs'] = $this->matchs_model->get_matchs();

        $this->load->view('templates/header', $data);
        $this->load->view('matchs/index', $data);
        $this->load->view('templates/footer');
    }

    public function view($id = NULL)
    {
        $data['match_item'] = $this->matchs_model->get_matchs($id);

        if (empty($data['match_item']))
        {
                show_404();
        }

        $data['title'] = $data['match_item']['Numero_ordre'];

        $this->load->view('templates/header', $data);
        $this->twig->display('matchs/view.twig', $data);
        $this->load->view('templates/footer');
    }
}