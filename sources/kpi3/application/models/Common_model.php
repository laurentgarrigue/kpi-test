<?php
/**
 * Description of Common_model
 *
 * @author laurent
 */
class Common_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    /**
     * Seasons list
     *
     * @param boolean $manager
     * @param string $seasons
     * @return array
     */
    public function get_seasons($manager = FALSE, $seasons = '')
    {
        if ($manager === TRUE && $seasons !== NULL && $seasons !== '') {
            $seasons = explode(',', $seasons);
            foreach ($seasons as $value) {
                $result[$value] = $value;
            }
            return $result;
        }

        $sql = "SELECT Code 
                FROM gickp_Saison 
                ORDER BY Code DESC";
        $query = $this->db->query($sql);
        foreach ($query->result() as $key=>$value) {
            $result[$value->Code] = $value->Code;
        }

        return $result;
    }

    /**
     * Competition group list
     *
     * @param boolean $selectpicker
     * @param boolean $manager
     * @param string $compet_groups
     * @return array
     */
    public function get_compet_groups($selectpicker = FALSE, $manager = FALSE, $compet_groups = '')
    {
        if (!$selectpicker && $manager === TRUE 
                && $compet_groups !== NULL && $compet_groups !== '') {
            $compet_groups = explode(',', $compet_groups);
            foreach ($compet_groups as $value) {
                $result[$value] = $value;
            }
            return $result;
        } 
        
        if($manager === TRUE && $compet_groups !== NULL && $compet_groups !== '') {
            $sql = "SELECT g.section, g.ordre, g.Groupe, g.Libelle,
                    s.name section_name
                    FROM gickp_Competitions_Groupes g
                    INNER JOIN gickp_sections s ON g.section = s.id
                    WHERE g.Groupe IN (?)
                    ORDER BY section, ordre ";
            foreach ($compet_groups as $value) {
                $group[] = "'" . $value . "'";
            }
            $query = $this->db->query($sql, array(implode(',', $group)));
        } else {
            $sql = "SELECT g.section, g.ordre, g.Groupe, g.Libelle,
                    s.name section_name
                    FROM gickp_Competitions_Groupes g
                    INNER JOIN gickp_sections s ON g.section = s.id
                    ORDER BY section, ordre ";
            $query = $this->db->query($sql);
        }
        
        if ($selectpicker) {
            $optgroup = FALSE;
            foreach ($query->result() as $key=>$value) {
                if($optgroup !== $value->section) {
                    $optgroup = $value->section;
                    $result[$optgroup]['label'] = $value->section_name;
                }
                $result[$optgroup]['options'][] = [
                    'value' => $value->Groupe,
                    'title' => $value->Groupe,
                    'label' => $value->Groupe . ' - ' . $value->Libelle
                ];
            }
        } else {
            foreach ($query->result() as $key=>$value) {
                $result[$value->Groupe] = $value->Groupe;
            }
        }

        return $result;
    }

    /**
     * Club list
     *
     * @param boolean $selectpicker
     * @return array
     */
    public function get_clubs($selectpicker = FALSE)
    {
        $sql = "SELECT c.Code, c.Libelle, c.Code_comite_dep, cd.Code_comite_reg,
                    cd.Libelle Libelle_cd, cr.Libelle Libelle_cr
                FROM gickp_Club c
                INNER JOIN gickp_Comite_dep cd ON c.Code_comite_dep = cd.Code
                INNER JOIN gickp_Comite_reg cr ON cd.Code_comite_reg = cr.Code
                ORDER BY cd.Code_comite_reg, c.Code_comite_dep, c.Libelle ";
        $query = $this->db->query($sql);
        
        if ($selectpicker) {
            $optgroup = FALSE;
            foreach ($query->result() as $key=>$value) {
                if($optgroup !== $value->Code_comite_reg) {
                    $optgroup = $value->Code_comite_reg;
                    $result[$optgroup]['label'] = $value->Libelle_cr;
                }
                $result[$optgroup]['options'][] = [
                    'value' => $value->Code,
                    'title' => $value->Code,
                    'label' => $value->Code . ' - ' . $value->Libelle,
                    'data-tokens' => $value->Libelle_cr . ' - ' . $value->Libelle_cd
                ];
            }
        } else {
            foreach ($query->result() as $key=>$value) {
                $result[$value->Code] = $value->Code;
            }
        }

        return $result;
    }

    /**
     * users list 
     *
     * @param int $role_id
     * @return array
     */
    public function get_users($role_id) {
        if ($role_id === 1) { // ADMIN
            $sql = "SELECT u.id, u.username, u.email, u.active, u.first_name, u.last_name, 
                    u.company, u.phone, u.seasons, u.compets, u.phases, u.clubs,
                    GROUP_CONCAT( ug.group_id ) groups_id
                    FROM users u LEFT OUTER JOIN users_groups ug ON u.id = ug.user_id
                    GROUP BY u.id
                    ORDER BY u.id ASC";
            $query = $this->db->query($sql);
        } elseif ($role_id >= 2) {
            $sql = "SELECT u.id, u.username, u.email, u.active, u.first_name, u.last_name, 
                    u.company, u.phone, u.seasons, u.compets, u.phases, u.clubs,
                    GROUP_CONCAT( ug.group_id ) groups_id
                    FROM users u LEFT OUTER JOIN users_groups ug ON u.id = ug.user_id 
                    WHERE g.id > ?
                    GROUP BY u.id
                    ORDER BY u.id ASC";
            $query = $this->db->query($sql, array($role_id));
        } else {
            return [];
        }

        $roles_list = $this->get_roles($role_id);
        foreach ($query->result() as $key => $value) {
            $result[$key] = $value;
            if ($value->groups_id != '') {
                $groups = explode(',', $value->groups_id);
                foreach ($groups as $group) {
                    $role[] = [
                        'id' => $roles_list[$group]['id'],
                        'name' => $roles_list[$group]['name'],
                        'description' => $roles_list[$group]['description']
                    ];
                }
                $result[$key]->roles = $role;
            }
            unset($role);
        }

        return $result;
    }

    /**
     * Roles list
     *
     * @param bool|int $role_id
     * @return array
     */
    public function get_roles($role_id) {
        if ($role_id === 1) { // ADMIN
            $sql = "SELECT g.id, g.name, g.description
                    FROM groups g
                    ORDER BY g.id ASC";
            $query = $this->db->query($sql);
        } elseif ($role_id >= 2) {
            $sql = "SELECT g.id, g.name, g.description
                    FROM groups g
                    WHERE g.id > ?
                    ORDER BY g.id ASC";
            $query = $this->db->query($sql, array($role_id));
        } else {
            return [];
        }

        foreach ($query->result() as $key => $value) {
            $result[$value->id] = [
                'id' => $value->id, 
                'name' => $value->name, 
                'description' => $value->description
            ];
        }

        return $result;

    }


}
