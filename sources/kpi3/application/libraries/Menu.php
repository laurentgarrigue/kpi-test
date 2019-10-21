<?php
/**
 * Description of Menu
 *
 * @author laurent
 */
class Menu {

    /**
	 * @var object Instance CodeIgniter
	 */
	private $CI;
    
    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->add_package_path(APPPATH.'third_party/ion_auth/');
        $this->CI->load->library('ion_auth');
    }
    
    
    /**
     * Renvoi du menu public construit
     * 
     * @return array
     */
    public function menu_public() {
        $menu = $this->_build_menu($this->_content_public());
        return $menu;
    }
    
    /**
     * Renvoi du menu d'administration construit
     * 
     * @return array|bool
     */
    public function menu_admin() {
        if ($this->CI->ion_auth->logged_in()) {
            $menu = $this->_build_menu($this->_content_admin());
            return $menu;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Contenu du menu public
     * label, route, groups (optionnel), content (array, optionnel)
     * 
     * @return array
     */
    private function _content_public()
    {
        $menu = array(
            [ 'label' => 'Home', 'route' => '/' ],
            [ 'label' => 'Schedule', 'route' => 'matchs/index'],
            [ 'label' => 'Games', 'route' => 'tests/menu' ],
            [ 'label' => 'Rankings', 'route' => 'matchs/index' ],
            [ 'label' => 'History', 'route' => 'matchs/index' ],
            [ 'label' => 'Teams', 'route' => 'matchs/index' ],
            [ 'label' => 'Clubs', 'route' => 'matchs/index' ],
            [ 'label' => 'Documents', 'route' => 'matchs/index', 
                'content' => [
                    [ 'label' => 'Games', 'route' => 'manager/docs/doc1' ],
                    [ 'label' => 'Rankings', 'route' => 'manager/docs/doc2', 'groups' => array('admin') ],
                    [ 'content' => 'separator' ],
                    [ 'label' => 'Teams', 'route' => 'manager/docs/doc2', 'groups' => array('admin') ],
                ]
            ],
            );
        
        return $menu;
    }
    
    /**
     * Contenu du menu administration
     * label, route, groups (optionnel), content (array, optionnel)
     * 
     * @return array
     */
    private function _content_admin()
    {
        $menu = [
            [ 'label' => 'Competitions', 'route' => 'manager/competitions' ],
            [ 'label' => 'Docs', 'route' => '', 
                'content' => [
                    [ 'label' => 'Doc1', 'route' => 'manager/docs/doc1' ],
                    [ 'label' => 'Doc2', 'route' => 'manager/docs/doc2', 'groups' => array('admin') ],
                    [ 'label' => 'Doc3', 'route' => 'manager/docs/doc3' ],
                ]
            ],
            [ 'label' => 'Teams', 'route' => 'manager/teams', 'groups' => array('admin') ],
            [ 'label' => 'Clubs', 'route' => 'manager/clubs', 'groups' => array('members') ],
            [ 'label' => 'Athletes', 'route' => 'manager/athletes', 'groups' => array('admin', 'members') ],
            [ 'label' => 'Phases', 'route' => 'manager/athletes', 'groups' => array('admin', 'members') ],
            [ 'label' => 'Games', 'route' => 'manager/athletes', 'groups' => array('admin', 'members') ],
            [ 'label' => 'Rankings', 'route' => 'manager/athletes' ],
            [ 'label' => 'Stats', 'route' => 'manager/athletes', 'groups' => array('admin', 'members') ],
            [ 'label' => 'Import', 'route' => 'manager/athletes', 'groups' => array('admin', 'members') ],
            [ 'label' => 'Users', 'route' => 'auth/user_list', 'groups' => array('admin') ],
            ];
        
        return $menu;
    }
    
    /**
     * Construction du menu
     * 
     * @param array $menu
     */
    private function _build_menu($menu) {
        foreach ($menu as $key => $item) {
            // S'il y a un contrôle de groupe
            if (isset($item['groups']) || array_key_exists('groups', $item)) {
                // autorisé selon groupe de l'utilisateur
                if (!$this->CI->ion_auth->in_group($item['groups'])) { 
                    continue;
                }
            }

            // Label
            if (isset($item['label']) || array_key_exists('label', $item)) {
                $item['page'] = $item['label'];
                $item['label'] = lang('kpi_' . $item['label']);
            }
            
            // Route
            if (isset($item['route']) || array_key_exists('route', $item)) {
                if($item['route'] != '') {
                    $item['route'] = base_url($item['route']);
                }
            }
            
            // S'il y a un sous-menu
            if (isset($item['content']) || array_key_exists('content', $item)) {
                if (is_array($item['content'])) {
                    $item['content'] = $this->_build_menu($item['content']);
                }
            }
            
            $builded_menu[] = $item;
        }
        return $builded_menu;
    }
}
