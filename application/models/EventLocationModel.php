<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    class EventLocationModel extends CI_Model {
        private $table = EVENT_LOCATION;

        function __construct(){
            parent::__construct();
        }

        public function save($data){
            if($data['where']==''){
                unset($data['where']);
                $this->db->insert($this->table,$data);
                if($this->db->affected_rows()>0){
                    $response['status'] = 1;
                    $response['message'] = 'Record inserted.';
                }
                else{
                    $response['status'] = 0;
                    $response['message'] = 'Record not insertede.';
                }
            }
            else{
                $where = $data['where'];
                unset($data['where']);
                $this->db->where('slug', $where);
                $this->db->update($this->table, $data);
                if($this->db->affected_rows()>0){
                    $response['status'] = 1;
                    $response['message'] = 'Record updated.';
                }
                else{
                    $response['status'] = 0;
                    $response['message'] = 'Record not updated.';
                }
            }
            return json_encode($response);
        }
        public function DeleteImage($slug,$image){
            $this->db->where('slug', $slug);
            $this->db->update($this->table, array('image' => ''));
            if($this->db->affected_rows()>0){
                $response['status'] = 1;
                $response['message'] = 'Record updated.';
            }
            else{
                $response['status'] = 0;
                $response['message'] = 'Record not updated.';
            }
            return json_encode($response);
        }
        public function edit($slug){
            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->where('slug',$slug);
            $query = $this->db->get();
            return json_encode($query->row_array());
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

        public function status($slug,$status){
            $this->db->where('slug', $slug);
            $this->db->update($this->table, array('status' => $status));
            if($this->db->affected_rows()>0){
                $response['status'] = 1;
                $response['message'] = 'Record updated.';
            }
            else{
                $response['status'] = 0;
                $response['message'] = 'Record not updated.';
            }
            return json_encode($response);
        }

        public function delete($slug){
            $this->db->where('slug', $slug);
            $this->db->update($this->table, array('is_delete' => 1));
            if($this->db->affected_rows()>0){
                $response['status'] = 1;
                $response['message'] = 'Record deleted.';
            }
            else{
                $response['status'] = 0;
                $response['message'] = 'Record not deleted.';
            }
            return json_encode($response);
        }
    }
?>