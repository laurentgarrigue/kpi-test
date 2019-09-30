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
        $this->twig->add_function('site_url');
        
        $this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        $data['title'] = 'Liste des matchs';
        $data['matchs'] = $this->matchs_model->get_matchs();

        $this->twig->display('templates/header.twig.html', $data);
        $this->twig->display('matchs/index.twig.html', $data);
        $this->twig->display('templates/footer.twig.html');
    }

    public function view($id = NULL)
    {
        $data['match_item'] = $this->matchs_model->get_matchs($id);

        if (empty($data['match_item']))
        {
                show_404();
        }

        $data['title'] = $data['match_item']['Numero_ordre'];
        $_SESSION['toto'] = 'tata';

        $this->twig->display('templates/header.twig.html', $data);
        $this->twig->display('matchs/view.twig.html', $data);
        $this->twig->display('templates/footer.twig.html');
    }
}