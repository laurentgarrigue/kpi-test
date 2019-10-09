<?php
/**
 * Description of Matchs
 *
 * @author laurent
 */
class Tests extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['title'] = 'Liste des matchs';
        $this->data['matchs'] = $this->matchs_model->get_matchs();

//        vdebug($this->data['user']);
        $this->twig->display('tests/index.html', $this->data);
    }
    
    public function menu()
    {
        

//        vdebug($this->data);
        $this->twig->display('tests/menu_dynamique.html', $this->data);
    }
    
    public function traduction()
    {
        $this->lang->load('kpi', $this->session->lang);
        // méthode 1 : librairie
        $this->data['title'] = $this->lang->line('kpi_title');
        // méthode 2 : helper
        $this->load->helper('language');
        $this->data['title2'] = lang('kpi_title');
        // méthode 3 : helper via Twig {{ lang('kpi_title') }}
        
        
        $this->data['matchs'] = $this->matchs_model->get_matchs();

        $this->twig->display('tests/index.html', $this->data);
    }

    public function view($id = NULL)
    {
        $this->data['match_item'] = $this->matchs_model->get_matchs($id);

        if (empty($this->data['match_item']))
        {
                show_404();
        }

        $this->data['title'] = $this->data['match_item']['Numero_ordre'];
        $_SESSION['toto'] = 'tata';

//        $this->twig->display('templates/header.html', $this->data);
        $this->twig->display('tests/view.html', $this->data);
//        $this->twig->display('templates/footer.html');
    }
    
    public function fpdf() {
        require_once APPPATH.'third_party/fpdf-1.8.1/fpdf.php';
        
        $pdf = new FPDF();
        $pdf->AddPage('P','A4',0);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,0,'Pdf généré par FPDF via Codeigniter',0,1,'C');
        $pdf->Output('pageBlanche.pdf' , 'I' );
    }
    
    public function tfpdf() { // FPDF en UTF-8
        // Définition facultative du répertoire des polices systèmes
        // Sinon tFPDF utilise le répertoire [chemin vers tFPDF]/font/unifont/
        // define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");
        require_once APPPATH.'third_party/tfpdf-1.25/tfpdf.php';
        

        $pdf = new tFPDF();
        $pdf->AddPage();

        // Ajoute une police Unicode (utilise UTF-8)
        $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
        $pdf->SetFont('DejaVu','',14);

        // Charge une chaîne UTF-8
        $pdf->Write(8,'Pdf généré par FPDF via Codeigniter en UTF-8 (DejaVu)');

        // Sélectionne une police standard (utilise windows-1252)
        $pdf->SetFont('Arial','',14);
        $pdf->Ln(10);
        $pdf->Write(5,"Partie générée en windows-1252 (Arial).");
        $pdf->Ln(10);
        $pdf->Write(5,"La taille de ce PDF n'est que de 13 ko.");

        $pdf->Output();
        
//        $pdf = new FPDF();
//        $pdf->AddPage('P','A4',0);
//        $pdf->SetFont('Arial','B',16);
//        $pdf->Cell(0,0,'Pdf généré par FPDF via Codeigniter',0,1,'C');
//        $pdf->Output('pageBlanche.pdf' , 'I' );
    }
    
    public function mpdf($id = NULL) {
        $this->data['match_item'] = $this->matchs_model->get_matchs($id);

        if (empty($this->data['match_item']))
        {
                show_404();
        }

        $this->data['title'] = $this->data['match_item']['Numero_ordre'];
        $_SESSION['toto'] = 'tata';

//        $html = $this->twig->render('templates/header.html', $this->data);
        $html .= $this->twig->render('tests/view.html', $this->data);
//        $html .= $this->twig->render('templates/footer.html');
        
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
		$pdf->Output();
    }
    
    public function ionauth() {
        $this->load->add_package_path(APPPATH.'third_party/ion_auth/');
        $this->load->library('ion_auth');
        $this->data['users'] = $this->ion_auth->users()->result(); // get all users
        $this->twig->display('tests/users.html', $this->data);
    }
    
    public function authenticated() {
        $this->load->add_package_path(APPPATH.'third_party/ion_auth/');
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
        {
              redirect('auth/login');
        }
        $this->data['user'] = $_SESSION;
        $this->twig->display('tests/logged_user.html', $this->data);
    }
}