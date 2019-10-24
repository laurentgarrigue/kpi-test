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
        $this->data['page'] = 'Competitions';
    }

    public function index()
    {
        $this->load->model('matchs_model');
        $this->data['matchs'] = $this->matchs_model->get_matchs();

        $this->data['message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Holy guacamole!</strong> You should check in on some of those fields below.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        $messages[] = [ 'context' => 'warning', 'content' => '<b>Holy guacamole!</b> You should check in on some of those fields below.'];
        $messages[] = [ 'context' => 'primary', 'content' => '<b>Hep!</b> Attention.'];
        
        $this->data['messages'] = json_encode($messages);
        $this->twig->display('tests/index.html', $this->data);
    }
    
    public function menu()
    {
        

//        vdebug($this->data);
        $this->twig->display('tests/menu_dynamique.html', $this->data);
    }
    
    public function sendmail()
    {
        $this->load->library('email');

        $this->email->from('contact@kayak-polo.info', 'Kayak-polo.info v3');
        $this->email->to('lgarrigue@gmail.com');
//        $this->email->cc('another@another-example.com');
        $this->email->bcc('contact@kayak-polo.info');

        $this->email->subject('Email Test');
        $this->email->message('Testing the email class 2');
        
//        $this->email->attach('image.jpg');

        $this->email->send();
        echo $this->email->print_debugger();
        
        redirect('tests/menu');

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
        
        $this->load->model('matchs_model');
        $this->data['matchs'] = $this->matchs_model->get_matchs();

        $this->twig->display('tests/index.html', $this->data);
    }

    public function view($id = NULL)
    {
        $this->load->model('matchs_model');
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
    }
    
    public function mpdf($id = NULL) {
        $this->load->model('matchs_model');
        $this->data['match_item'] = $this->matchs_model->get_matchs($id);

        if (empty($this->data['match_item']))
        {
                show_404();
        }

        $this->data['title'] = $this->data['match_item']['Numero_ordre'];
        $_SESSION['toto'] = 'tata';

        $html = $this->twig->render('tests/view.html', $this->data);
        
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

    public function selectpicker() {
        $this->data['mon_select'] = [
			'name'    => 'mon_select',
			'id'      => 'mon_select',
			'type'    => 'selectpicker',
			'class' => 'form-control selectpicker',
			'value'   => '',
        ];
        $this->data['mon_select_options'] = [
			'OptGroup 1'    => [
                'n1' => 'Ligne 1',
                'n2' => 'Ligne 2',
                'n3' => 'Ligne 3',
                'n4' => 'Ligne 4',
            ],
			'OptGroup 2'    => [
                'n5' => 'Ligne 5',
                'n6' => 'Ligne 6',
                'n7' => 'Ligne 7',
                'n8' => 'Ligne 8',
                'n9' => 'Ligne 9',
            ],
        ];

        if ($this->ion_auth->logged_in()) {
            $this->data['current_user']['seasons'] = $this->user->seasons;
        }

        $this->load->model('common_model');
        $this->data['seasons'] = $this->common_model->get_seasons(TRUE, $this->user->seasons);

        $this->twig->display('tests/selectpicker.html', $this->data);

    }
}