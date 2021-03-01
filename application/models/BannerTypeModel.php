<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    class BannerTypeModel extends CI_Model {
        private $table = BANNER_TYPE_MASTER;

        function __construct(){
            parent::__construct();
        }

        public function count($status='',$where=''){
            $con = '';
            $response = 0;
            if($status!==''){
                $this->db->where('status', $status);
            }
            if($where!==''){
                $this->db->where($where);
            }
            
            $this->db->select('ifnull(count(*),0) AS `total`');
            $this->db->from($this->table);
            $this->db->where('is_delete', 0);
            $query = $this->db->get();
            $row = $query->row_array();

            return $row['total'];
        }

        public function select($status='',$where='',$orderby=array(),$start='',$length=''){
            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->where('is_delete', 0);
            if($status!==''){
                $this->db->where('status', $status);
            }
            if($where!==''){
                $this->db->where($where);
            }
            if(count($orderby)>0){
                foreach($orderby as $key=>$val){
                    $this->db->order_by($val['column'], $val['order']);
                }
            }
            if($length!=='' && $start!==''){
                $this->db->limit($length, $start);
            }
            $query = $this->db->get();
            $rows = $query->result();
            $records['total'] = count($rows);
            $records['records'] = $rows;
            return json_encode($records);
        }

    }
?>