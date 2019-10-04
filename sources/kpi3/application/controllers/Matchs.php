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
//        $this->twig->add_function('site_url');
//        $this->twig->add_function('base_url');
        
        $this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        $data['title'] = 'Liste des matchs';
        $data['matchs'] = $this->matchs_model->get_matchs();

//        $this->twig->display('templates/header.html', $data);
//        vdebug($data);
        $this->twig->display('matchs/index.html', $data);
//        $this->twig->display('templates/footer.html');
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

//        $this->twig->display('templates/header.html', $data);
        $this->twig->display('matchs/view.html', $data);
//        $this->twig->display('templates/footer.html');
    }
    
    public function pdf() {
        require_once APPPATH.'third_party/fpdf181/fpdf.php';
        
        $pdf = new FPDF();
        $pdf->AddPage('P','A4',0);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,0,'Pdf généré par FPDF via Codeigniter',0,1,'C');
        $pdf->Output('pageBlanche.pdf' , 'I' );
    }
    
    public function mpdf($id = NULL) {
        $data['match_item'] = $this->matchs_model->get_matchs($id);

        if (empty($data['match_item']))
        {
                show_404();
        }

        $data['title'] = $data['match_item']['Numero_ordre'];
        $_SESSION['toto'] = 'tata';

//        $html = $this->twig->render('templates/header.html', $data);
        $html .= $this->twig->render('matchs/view.html', $data);
//        $html .= $this->twig->render('templates/footer.html');
        
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
		$pdf->Output();
    }
    
    public function ionauth() {
        $this->load->add_package_path(APPPATH.'third_party/ion_auth/');
        $this->load->library('ion_auth');
        $data['users'] = $this->ion_auth->users()->result(); // get all users
        $this->twig->display('matchs/users.html', $data);
    }
    
    public function authenticated() {
        $this->load->add_package_path(APPPATH.'third_party/ion_auth/');
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
        {
              redirect('auth/login');
        }
        $data['user'] = $_SESSION;
        $this->twig->display('matchs/logged_user.html', $data);
    }
}