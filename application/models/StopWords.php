<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    class StopWords extends CI_Model{
        private $table = STOP_WORDS_MASTER;
        
        /**
         * @param int status, default all
         * @param string where conditions, default all
         * @param array order by, default array
         * @param int start, default none
         * @param int length, default none
         * @return json of records
         * */
        public function select($status='',$where='',$orderby=array(),$start='',$length=''){
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
            
            $this->db->select('GROUP_CONCAT(LOWER(`word`)) AS `words`');
            $this->db->from($this->table);
            $this->db->where('is_delete',0);
            $records = array();
            $query = $this->db->get();
            $rows = $query->row_array();

			$response['total'] = count($rows);
			$response['records'] = $rows['words'];
			return json_encode($response);
        }
    }
?>