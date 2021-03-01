<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct(){
        parent::__construct();
        
        if ($this->session->userdata('admin_id') == null || $this->session->userdata('admin_id') < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'SignIn' && $this->router->class != 'Api') 
            {
                redirect(base_url());
            }
        }
        else{
            if ($this->router->class == 'SignIn') 
            {                     
                redirect(base_url().'Dashboard');
            }
        }
	}

	/**
	 * @param string
	 * @return string
	 * */
	public function db_output($string){
		$string=preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $string);
		$string= stripslashes($string);
		return htmlspecialchars($string);
	}
	
	/**
	 * @param string
	 * @param boolean integer
	 * @return string
	 * */
	public function db_input($string,$int=false){
		// Stripslashes
		if (get_magic_quotes_gpc()){
			$string = stripslashes($string);
		}
		if (!is_numeric($string)){
			$db = get_instance()->db->conn_id;
			$string = mysqli_real_escape_string($db,$string);
		}
		else{
			$string=$string;
		}
		$string = trim($string);
		if($int===true){
			return intval($string);
		}
		else{
			return strval($string);
		}
	}
	
	public function filter($request, $columns){
		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = $this->pluck( $columns, 'column' );
		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			$str = $request['search']['value'];
			$str = trim($str,'^');
			$str = trim($str,'$');
			$str = trim($str);
			
			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];
				if ( isset($column['column']) && $requestColumn['searchable'] == 'true' ) {
					$globalSearch[] = "`".$column['prefix']."`.`".$column['column']."` LIKE '%".$this->db_input($str)."%'";
				}
			}
		}
		
		// Individual column filtering
		if ( isset( $request['columns'] ) ) {
			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];
				//echo '<pre>';print_R($column);
				if(isset($column['column'])){
					
					$str = $requestColumn['search']['value'];
					$str = trim($str,'^');
					$str = trim($str,'$');
					$str = trim($str);
					//$requestColumn['searchable'] == 'true' && 
					if ( $str != '' ){
						$col = isset($column['filter_column'])?$column['filter_column']:$column['column'];
						if($str=='0'&&isset($column['child_column'])){
							$col = $column['child_column'];
							$column['prefix'] = $column['child_prefix'];
						}
						$columnSearch[] = "`".$column['prefix']."`.`".$col."` = '".$this->db_input($str)."'";
					}
				}
			}
		}
		// Combine the filters into a single string
		$where = '';
		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}
		if ( count( $columnSearch ) ) {
			$where = $where === '' ? implode(' AND ', $columnSearch) : $where .' AND '. implode(' AND ', $columnSearch);
		}
		return $where;
	}
	
	public function pluck ( $a, $prop ){
		$out = array();
		for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
		  if(isset($a[$i][$prop])){
			$out[] = $i;
			}
		}
		return $out;
	}
	
	public function order ( $request, $columns, $array=false){
		$order = '';
		$orderarray = array();
		if ( isset($request['order']) && count($request['order'])>0 ) {
			$orderBy = array();
			$dtColumns = $this->pluck( $columns, 'column' );
			for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
				// Convert the column index into the column data property
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];
				if ( $requestColumn['orderable'] == 'true' ) {
					$dir = $request['order'][$i]['dir'] === 'asc' ?
						'ASC' :
						'DESC';
					
					$orderBy[] = ' ORDER BY `'.$column['column'].'` '.$dir;
					if($column['prefix']==''){
					  array_push($orderarray,array('column'=>$column['prefix'].$column['column'],'order'=>$dir));
					}
					else{
						array_push($orderarray,array('column'=>$column['prefix'].'.'.$column['column'],'order'=>$dir));
					}
				}
			}
			$order = ' '.implode(', ', $orderBy);
		}
		if($array===false){
		  return $order;
		}
		else{
			return $orderarray;
		}
	}
	
	public function slugify($str, $table, $where='') {
		$invalidSlug = true;
		$count = 1;
		$ins = new StopWords();
		$stopwords = explode(',',json_decode($ins->select(1),true)['records']);

		$str = strtolower(trim($str));
		$str = preg_replace('/[^a-zA-Z0-9-]/', ' ', $str);
		$str = trim(preg_replace('/ +/', " ", $str));
		$str = explode(" ",$str);
		$arr = array_keys(array_intersect($str,$stopwords));
		foreach($arr as $key=>$val){
			$str[$val] = '-';
		}
		$str = trim(implode(" ",$str));
		$str = trim($str,"-");
		$str = preg_replace('/ +/', "-", $str);
		$str = preg_replace('/-+/', "-", $str);
		$str = trim($str," ");
		$slug = trim($str,"-");
		
		while($invalidSlug){
			$q = "SELECT COUNT(*) AS `total`
					FROM `".$table."`
					WHERE `is_delete`='0' AND `slug`='".$slug."' ".$where;
					//echo $q;exit;
			$res = $this->db->query($q);
			$total = $res->row()->total;
			if($total==0){
				$invalidSlug = false;
			}
			else{
				$count++;
				$slug = $slug.'-'.$count;
			}
		}
		return $slug;
	}
}
