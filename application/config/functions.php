<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    /**
     * @param array $array
     * @param boolean format, default true
     * @param boolean exit, default true
     * @return string
     * */
    function printr($array,$formate=true,$exit=true){
        if($formate===true){
            echo '<pre>';print_r($array);echo '</pre>';
        }
        else{
            print_r($array);
        }
        if($exit===true){
            exit;
        }
    }
    
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
        }
        
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    function get_extension($file) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        return $extension ? $extension : false;
    }
    
    function get_filename($file,$ext=true) {
        if($ext===true){
            $name = basename($file);
        }
        else{
            $name = basename($file,'.'.get_extension($file));
        }
        return $name ? $name : false;
    }
    
    function get_thumb($file,$size){
        $image = get_filename($file,false).'-'.$size.'.'.get_extension($file);
        return $image;
    }
    
    /**
     * @param string session type
     * @param boolean redirect, default true
     * @return json
     * */
    function is_login($type,$redirect=true){
        if($type=='user'){
            if(isset($_SESSION['user_id'])&&$_SESSION['user_id']>0){
                $response['status'] = 1;
                $response['id'] = $_SESSION['user_id'];
                $response['message'] = 'User is logged in.';
            }
            else{
                $response['status'] = 0;
                $response['id'] = 0;
                $response['message'] = 'User is not logged in.';
            }
        }
        else if($type=='admin'){
            if(isset($_SESSION['admin_id'])&&$_SESSION['admin_id']>0){
                $response['status'] = 1;
                $response['id'] = $_SESSION['admin_id'];
                $response['message'] = 'Admin is logged in.';
            }
            else{
                $response['status'] = 0;
                $response['id'] = 0;
                $response['message'] = 'Admin is not logged in.';
            }
        }
        else{
            $response['status'] = 0;
            $response['id'] = 0;
            $response['message'] = 'Something went wrong.';
        }
        if($response['status']==0 && $redirect===true){
            if($type=='user'){
                header("location:".SITE_URL."sign-in");exit;
            }
            else if($type=='admin'){
                header("location:".ADMIN_URL."sign-in.php");exit;
            }
        }
        return json_encode($response);
    }
    
    /**
     * @param array exclude array
     * @param string extra, add to parameter
     * @return string
     * */
    function get_params($exclude_array = '', $extra=''){
    	if(!is_array($exclude_array)) $exclude_array = array();
    	$get_url = '';
    	if (is_array($_GET) && (sizeof($_GET) > 0)){
    		reset($_GET);
    	  	while (list($key, $value) = each($_GET)){
                if ( !is_array($value) && (strlen($value) > 0) && ($key != $this->re_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ){
    		   		$get_url .=  '&' .$key . '=' . rawurlencode(stripslashes($value));
    			}
    	  	}
    	}
    	return ($extra?substr($get_url,1):$get_url);
    }
    
    /**
     * @param string str
     * @param int maxlenth, default 150
     * @return string
     * */
    function shortstr($str,$maxlen=150){
    	if(strlen($str)>$maxlen){
    		$str=substr($str,0,$maxlen);
    		$str.="...";
    	}
    	return $str;
    }
    
    /**
     * @param string date
     * @return boolean
     * */
    function is_date($str){
    	$stamp = strtotime( $str );
    	if (!is_numeric($stamp)){
    		return FALSE;
    	}
    	//$month = date( 'm', $stamp ); $day   = date( 'd', $stamp ); $year  = date( 'Y', $stamp );
        $date_arr = explode('/', $this->output_date($str));    //output_date public function return date in dd/mm/yyyy formate
        $day = isset($date_arr[0]) ? $date_arr[0] : "";
        $month = isset($date_arr[1]) ? $date_arr[1] : "";
        $year = isset($date_arr[2]) ? $date_arr[2] : "";
    	
    	if($day!="" && $month!="" && $year!="" && checkdate($month, $day, $year)){
    	 	return TRUE;
    	}
    	else{
            return FALSE;
    	}
    }
    
    /**
     * @param string email
     * @return boolean
     * */
    function is_email($email){
    	return filter_var($email,FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * @param string username
     * @return boolean
     * */
    function is_username($username){
        if(strlen(trim($username))>=5 && strlen(trim($username))<=30 && preg_match('/^[a-z]+([a-z0-9._]*)?[a-z0-9]+$/i', $username)){
            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * @param string url
     * @return boolean
     * */
    function is_url($url){
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)){
          return false;
        }
        else{
            return true;
        }
    }
    
    /**
     * @param string mobile no
     * @return bool
     * */
    function is_phone_number($phoneNumber){
        //Check to make sure the phone number format is valid 
        if( !preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $phoneNumber)){
    		return false; 
    	}
    	else{
    		return true; 
    	}
    }
    
    /**
     * @param int length, default 6
     * @return boolean
     * */
    function random_password_generate($length=6){
        $possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
        $code="";
        for($i=0; $i<=$length; $i++){ 
            $code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
        }
        return $code;
    }
    
    /**
     * @param int length, defaullt 6
     * @return boolean
     * */
    function random_otp_generate($length=6){
        $possible_letters = '1234567890';
        $code="";
        for($i=0; $i<=$length; $i++){
            $code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
        }
        return $code;
    }
    
    /**
     * @param string url
     * @return boolean
     * */
    function is_url_exist($url){
        $ch = curl_init($url);    
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($code == 200){
            $status = true;
        } else{
            $status = false;
        }
        curl_close($ch);
        return $status;
    }
    
    /**
     * @param string date
     * @return date
     * */
    function format_date($date){
    	if($date!='' && $date!='00-00-0000'){
           return date('Y-m-d',strtotime($date));
    	}
        else{
            return '0000-00-00';
        }
    }
    
    /**
     * @param string datetime
     * @param int format, default 24
     * @return datetime
     * */
    function format_datetime($date,$format=24){
    	if($date!='' && $date!='00-00-0000 00:00:00'){
            if($format==24){
                return date('Y-m-d H:i:s',strtotime($date));
            }
            else{
                return date('Y-m-d h:i:s A',strtotime($date));
            }
    	}
        else{
            return '0000-00-00 00:00:00';
        }
    }
    
    /**
     * @param string datetime
     * @param int format, default 24
     * @return string datetime
     * */
    function display_datetime($date,$format=24){
    	if($date!='' && $date!='0000-00-00 00:00'){
            if($format=='24'){
                return date('d-m-Y H:i:s',strtotime($date));
            }
            else{
                return date('d-m-Y h:i:s A',strtotime($date));
            }
    	}
        else{
            return '00-00-0000 00:00';
        }
    }
    /**
     * @param string date
     * @return string date
     * */
    function display_date($date){
    	if($date!='' && $date!='0000-00-00'){
           return date('d-m-Y',strtotime($date));
    	}
        else{
            return '00-00-0000';
        }
    }
    
    /**
     * @param array element
     * @param int parentid, default 0
     * @param string primary column, default id
     * @param string parent column, default parent_id
     * @return array
     * */
    function buildTree(array $elements, $parentId = 0, $id='id', $parent_key='parent_id'){
        $branch = array();
        foreach ($elements as $element) {
            if ($element[$parent_key] == $parentId) {
                $children = buildTree($elements, $element[$id]);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element[$id]] = $element;
            }
        }
        return $branch;
    }
    
    /**
     * @param string message
     * @return string
     * */
    function error_msg($msg){
        ?>
        <div class="alert alert-danger fade in">
            <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
            <?php
                if(is_array($msg)){
                    foreach($msg as $key=>$val){
                        echo $val.'<br/>';
                    }
                }
                else{
                    echo $msg;
                }
            ?>
        </div>
        <?php
    }
    
    /**
     * @param string message
     * @return string
     * */
    function warning_msg($msg){
        ?>
        <div class="alert alert-warning fade in">
            <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
            <strong><i class="fa fa-thumbs-down"></i></strong>
            <?php
                if(is_array($msg)){
                    foreach($msg as $key=>$val){
                        echo $val.'<br/>';
                    }
                }
                else{
                    echo $msg;
                }
            ?>
        </div>
        <?php
    }
    
    /**
     * @param string message
     * @return string
     * */
    function info_msg($msg){
        ?>
        <div class="alert alert-info fade in">
            <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
            <strong><i class="fa fa-volume-up"></i></strong>
            <?php
                if(is_array($msg)){
                    foreach($msg as $key=>$val){
                        echo $val.'<br/>';
                    }
                }
                else{
                    echo $msg;
                }
            ?>
        </div>
        <?php
    }
    
    /**
     * @param string message
     * @return string
     * */
    function success_msg($msg){
        ?>
        <div class="alert alert-success fade in">
            <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
            <strong><i class="ion-android-done-all"></i></strong>
            <?php
                if(is_array($msg)){
                    foreach($msg as $key=>$val){
                        echo $val.'<br/>';
                    }
                }
                else{
                    echo $msg;
                }
            ?>
        </div>
        <?php
    }

    /**
     * @param int number
     * @param int decimal, default 2
     * @return float
     * */
    function to_decimal($number,$decimal=2){
        return number_format((float)$number, $decimal, '.', ',');
    }
    
    /**
     * @param array
     * @parram string rearray column name
     * @return string
     * */
    function reArray($array,$param) {
        $file_ary = array();
        $file_count = count($array[$param]);
        $file_keys = array_keys($array);
        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $array[$key][$i];
            }
        }
        return $file_ary;
    }
    
    function filesize_formatted($size,$thousand_seperator=true,$unit=true){
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        $sep = $thousand_seperator===true?',':'';
        $unit = $unit===true?' '.$units[$power]:'';
        return number_format($size / pow(1024, $power), 2, '.', $sep) . $unit;
    }
    
    function convert($size,$unit,$decimal=2,$thousand_seperator=true,$show_unit=true){
        if($unit == "KB"){
            $fileSize = round($size / 1024,4);	
        }
        else if($unit == "MB"){
            $fileSize = round($size / 1024 / 1024,4);	
        }
        else if($unit == "GB"){
            $fileSize = round($size / 1024 / 1024 / 1024,4);	
        }
        
        $sep = $thousand_seperator===true?',':'';
        $unit = $show_unit===true?' '.$unit:'';
        
        return number_format($fileSize, $decimal, '.', $sep) . $unit;
        
    }
    
    function is_image($mediapath){
        if(@is_array(getimagesize($mediapath))){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array to
     * @param string subject
     * @param string message body
     * @param array cc
     * @param array bcc
     * @param array attachment
     * @return boolean
     * */
    function send_email($to,$subject,$body,$cc=array(),$bcc=array(),$attachemnt=array(),$replyTo=array()){
        // Configuring SMTP server settings
        $mail = new PHPMailer;
	    $mail->isSMTP(true);
	    $mail->Host = SMTP_HOST;
	    $mail->Port = 587; // 465 587
	    $mail->SMTPSecure = 'tls'; //tls ssl
	    $mail->SMTPAuth = true;
	    $mail->setFrom(SMTP_ID,'Property World');
	    $mail->Username = SMTP_ID;
	    $mail->Password = SMTP_PASSWORD;
        
        foreach($to as $key=>$val){
            $mail->addAddress($val);
        }
        foreach($cc as $key=>$val){
            $mail->AddCC($val);
        }
        foreach($bcc as $key=>$val){
            $mail->AddBCC($val);
        }
        foreach($attachemnt as $key=>$val){
            $mail->AddAttachment($val,'Attachment');
        }
        foreach($replyTo as $key=>$val){
            $mail->AddReplyTo($val['email'], $val['name']);
        }
        
        //$mail->AddBCC('scspl.maisuri@gmail.com');
        $mail->Subject = $subject;
        $mail->isHTML(true);    
        $mail->msgHTML($body);
        
        // Success or Failure
        if(! @ $mail->send()){
            return true;
        }
        else {
            return false;
        }
        
    }
    
    function count_digit($number) {
      return strlen($number);
    }
    
    function divider($number_of_digits) {
        $tens="1";
    
      if($number_of_digits>8)
        return 10000000;
    
      while(($number_of_digits-1)>0)
      {
        $tens.="0";
        $number_of_digits--;
      }
      return $tens;
    }
    
    function number_to_word($number){
        //return $number;
        $num = round($number);
        
        $ext="";//thousand,lac, crore
        $number_of_digits = count_digit($num); //this is call :)
            if($number_of_digits>3)
        {
            if($number_of_digits%2!=0)
                $divider=divider($number_of_digits-1);
            else
                $divider=divider($number_of_digits);
        }
        else
            $divider=1;
    
            $fraction=$num/$divider;
            $fraction=number_format($fraction,2);
            if($number_of_digits==4 ||$number_of_digits==5)
                $ext="k";
            if($number_of_digits==6 ||$number_of_digits==7)
                $ext="Lac";
            if($number_of_digits==8 ||$number_of_digits==9)
                $ext="Cr";
            return $fraction." ".$ext;
            
            $no = round($number);
           $point = round($number - $no, 2) * 100;
           $hundred = null;
           $digits_1 = strlen($no);
           $i = 0;
           $str = array();
           $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
           $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
           while ($i < $digits_1) {
             $divider = ($i == 2) ? 10 : 100;
             $number = floor($no % $divider);
             $no = floor($no / $divider);
             $i += ($divider == 10) ? 1 : 2;
             if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
             } else $str[] = null;
          }
          $str = array_reverse($str);
          $result = implode('', $str);
          $points = ($point) ?
            "." . $words[$point / 10] . " " . 
                  $words[$point = $point % 10] : '';
          return $result . "Rupees  " . $points . " Paise";
    }
    
    function front_pagination($targetpage,$page,$lastpage,$lpm1,$prev,$next,$adjacents){
        $pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<div class=\"home2-pagination\">";
            $pagination .= "<nav aria-label=\"Page navigation example\">";
            $pagination .= "<ul class=\"pagination\">";
    		//previous button
    		if ($page > 1) 
    			$pagination.= '<li class="page-item cta"><a class="page-link" href="'.$targetpage.'/page/'.$prev.'"><i class="zmdi zmdi-long-arrow-left"></i></a></li>';
    		else
    			$pagination.= '<li class="page-item cta"><a class="page-link" href="javascript:void(0);"><i class="zmdi zmdi-long-arrow-left"></i></a></li>';	
    		
    		//pages	
    		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= '<li class="page-item active"><a class="page-link" href="javascript:void(0);">'.$counter.'</a></li>';
    				else
    					$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/'.$counter.'">'.$counter.'</a></li>';					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
    		{
    			//close to beginning; only hide later pages
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= '<li class="page-item active"><a class="page-link" href="javascript:void(0);">'.$counter.'</a></li>';
    					else
    						$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/'.$counter.'">'.$counter.'</a></li>';					
    				}
    				$pagination.='<li class="page-item"><a class="page-link" href="javascript:void(0);">...</a></li>';
    				$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/'.$lpm1.'">'.$lpm1.'</a></li>';
    				$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/'.$lastpage.'">'.$lastpage.'</a></li>';		
    			}
    			//in middle; hide some front and some back
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/1">1</a></li>';
    				$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/2">2</a></li>';
    				$pagination.= '<li class="page-item"><a class="page-link" href="javascript:void(0);">...</a></li>';
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= '<li class="page-item active"><a class="page-link" href="javascript:void(0);">'.$counter.'</a></li>';
    					else
    						$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/'.$counter.'">'.$counter.'</a></li>';					
    				}
    				$pagination.= '<li class="page-item"><a class="page-link" href="javascript:void(0);">...</a></li>';
    				$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/'.$lpm1.'">'.$lpm1.'</a></li>';
    				$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/'.$lastpage.'">'.$lastpage.'</a></li>';		
    			}
    			//close to end; only hide early pages
    			else
    			{
    				$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/1">1</a></li>';
    				$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/2">2</a></li>';
    				$pagination.= '<li class="page-item"><a class="page-link" href="javascript:void(0);">...</a></li>';
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= '<li class="page-item active"><a class="page-link" href="javascript:void(0);">'.$counter.'</a></li>';
    					else
    						$pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'/page/'.$counter.'">'.$counter.'</a></li>';				
    				}
    			}
    		}
    		
    		//next button
    		if ($page < $counter - 1) 
    			$pagination.= '<li class="page-item ctas"><a class="page-link" href="'.$targetpage.'/page/'.$next.'"><i class="zmdi zmdi-long-arrow-right"></i></a></li>';
    		else
    			$pagination.= '<li class="page-item ctas"><a class="page-link" href="javascript:void(0);"><i class="zmdi zmdi-long-arrow-right"></i></a></li>';
    		$pagination.= "</ul></nav></div>";		
    	}
        return $pagination;
    }
    
    /**
     * @param string gcm id
     * @param string notification title
     * @param string notification message
     * @param string icon
     * @param string sound
     * */
    function sendpushnotifications($reg_id,$slug,$title,$notification,$icon='myicon',$image='myimage',$sound='mysound'){
        
        $msg = array(
                        'name' 	=> $notification,
                        'title'	=> $title,
                        'slug' => $slug,
                        'timestamp' => time(),
                        'attach_file' => $image,
                        'icon'	=> $icon,/*Default Icon*/
                        //'big_icon'	=> $image,/*Default Icon*/
                        'sound' => $sound/*Default sound*/
                    );
                    
        $fields = array(
                        'registration_ids'		=> $reg_id,
                        'data'	=> $msg,
                        //'data message' => $msg,
                        //'data' => array('slug'=>$slug,'title' => $title,'property_name' => $notification,'image'	=> $image)
                    );
                    
        $headers = array(
                        'Authorization: key=' . FCM_SERVER_KEY,
                        'Content-Type: application/json'
                    );
        //printr($fields);
        #Send Reponse To FireBase Server	
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        
        return $result;
    }
    
    function send_sms($msg,$mobile){
        if(SEND_SMS==1){
            $msg=urlencode($msg);
            $string_enter="NEW_LINE";
            $string_enter_new="%0A";
            $msg = str_replace($string_enter,$string_enter_new,$msg);
           
            $url='http://www.smsjust.com/blank/sms/user/urlsms.php?username='.SMS_USERNAME.'&pass='.SMS_PASSWORD.'&senderid='.SMS_SENDERID.'&dest_mobileno='.$mobile.'&message='.$msg.'&Response=N';
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $output=curl_exec($ch);
            curl_close($ch);
        }
    }
?>