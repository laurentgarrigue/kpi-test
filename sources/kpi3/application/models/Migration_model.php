<?php
/**
 * Description of Migration_model
 *
 * @author laurent
 */
class Migration_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_user($user_id = FALSE)
    {
        if ($user_id !== FALSE)
        {
            $sql = "SELECT u.*, lc.Nom, lc.Prenom
                FROM gickp_Utilisateur u
                LEFT JOIN gickp_Liste_Coureur lc ON (u.Code = lc.Matric)
                WHERE u.Code = $user_id ;";
            $query = $this->db->query($sql);
            return $query->row_array();
        }

    }

    public function get_competition_groups($competitions_list = FALSE)
    {
        if ($competitions_list !== FALSE && $competitions_list !== '')
        {
            $sql = 'SELECT DISTINCT Code_ref 
                FROM gickp_Competitions
                WHERE Code IN (' .  $competitions_list . ');';
            $query = $this->db->query($sql);
            foreach ($query->result() as $key=>$value) {
                $result[] = $value->Code_ref;
            }
            return implode(',', $result);
        }
    }

    public function migrate_user($user_data = FALSE)
    {
        if ($user_data !== FALSE)
        {
            $sql = "INSERT INTO `users` 
                (`id`, `ip_address`, `username`, `password`, 
                `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, 
                `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, 
                `created_on`, `last_login`, `active`, `first_name`, 
                `last_name`, `company`, `phone`, `seasons`, 
                `compets`, `phases`, `clubs`) 
                VALUES (NULL, '127.0.0.1', '" . $user_data['Code'] . "', '',
                '" . $user_data['Mail'] . "', NULL, NULL, NULL, 
                NULL, NULL, NULL, NULL, 
                CURRENT_TIMESTAMP(), NULL, 1, '" . $user_data['Prenom'] . "', 
                '" . $user_data['Nom'] . "', '" . $user_data['Fonction'] . "', '" . $user_data['Tel'] . "', '" . $user_data['Filtre_saison'] . "', 
                '" . $user_data['Filtre_groupes'] . "', '" . $user_data['Filtre_journee'] . "', '" . $user_data['Limitation_equipe_club'] . "');";
            $this->db->query($sql);
            $insert_id = $this->db->insert_id();

            $sql = "INSERT INTO `users_groups` 
                (`id`, `user_id`, `group_id`) 
                VALUES (NULL, $insert_id, " . $user_data['Niveau'] . ");";
            $this->db->query($sql);

            return $this->db->affected_rows();
        }
    }
}
