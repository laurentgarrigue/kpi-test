<?php
/**
 * Description of Migration
 *
 * @author laurent
 */
class Migration extends MY_Controller {
    
    /**
     *
     * @var array data to send to Twig 
     */
    public $data;
    
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
            redirect('/auth/login/');
		}
        $this->data['page'] = 'Migration';

        if (isset($this->session->message)) {
            $messages[] = [ 'context' => 'primary', 'content' => $this->session->message];
        }
    }

    public function index()
    {
        $this->data['messages'] = json_encode($messages);
        $this->twig->display('manager/migration.html', $this->data);
    }

    public function import_user($user)
    {
        $this->load->model('migration_model');
        $user_data = $this->migration_model->get_user($user);

        if ($user_data['Filtre_saison'] !== '') {
            $user_data['Filtre_saison'] = str_replace('|', ',', trim($user_data['Filtre_saison'], '|'));
        }

        if ($user_data['Filtre_competition'] !== '') {
            $user_data['Filtre_competition'] = '"' . str_replace('|', '","', trim($user_data['Filtre_competition'], '|')) . '"';
        }
        $user_data['Filtre_groupes'] = $this->migration_model->get_competition_groups($user_data['Filtre_competition']);

        if ($user_data['Niveau'] > 1) {
            $user_data['Niveau'] *= 10;
        }

        $result = $this->migration_model->migrate_user($user_data);


        // vdebug($saisons);
        redirect('/auth/user_list/');

    }
    
}