<?php
/**
 * Description of Matchs_model
 *
 * @author laurent
 */
class Matchs_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_matchs($id = FALSE)
    {
        if ($id === FALSE)
        {
            $query = $this->db->get('gickp_Matchs', 10, 20);
            return $query->result_array();
        }

        $query = $this->db->get_where('gickp_Matchs', array('Id' => $id));
        return $query->row_array();
    }

}
