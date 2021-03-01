<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    class AdminModel extends CI_Model {
        private $table = ADMIN_MASTER;

        public function SignIn($data){
            $username = $data['username'];
            $password = $data['password'];

            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->where('username', $username);
            $this->db->where('password', md5($password));
            $query = $this->db->get();

            if ( $query->num_rows() > 0 ){
                $row = $query->row_array();
                $this->session->set_userdata('admin_id',$row['admin_id']);
                $this->session->set_userdata('admin_name',$row['first_name'].' '.$row['last_name']);
                $response['status'] = 1;
                $response['message'] = 'Authentication success.';
            }
            else{
                $response['status'] = 0;
                $response['message'] = 'Invalid detail.';
            }
            return $response;
        }

        public function ChangePassword($data){
            $admin_id = $data['admin_id'];
            unset($data['admin_id']);
            $array['password'] = md5($data['new_password']);
            $this->db->where('admin_id',$admin_id);
            $this->db->where('password',md5($data['current_password']));
            $this->db->update($this->table, $array);
            if($this->db->affected_rows()>0){
                $response['status'] = 1;
                $response['message'] = 'Password updated.';
            }
            else{
                $response['status'] = 0;
                $response['message'] = 'Please enter valid password.';
            }
            return json_encode($response);
        }
    }
?>