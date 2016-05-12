<?php
class inForm
{

	private $db;
    private $error;

    function __construct()
    {
		$server = 'localhost'; 
		$user = 'informme_api';
		$pass = 'informme_api';
		$database = 'informme_api';
		$connection =  mysql_connect($server, $user, $pass) or die ('Error');
		mysql_set_charset('utf8',$connection);
		mysql_select_db($database,$connection);
        define('DEFAULT_CURRENCY', 'USD');
        define('PAYPAL_CLIENT_ID', 'AUQ_jnZTY46_65xMhwZiP8LBWPRCa-XMTfeJI35T9aPgOo4f9tmNMuU6Icl97O2jO1WsvVDBhExq3F_e'); // Paypal client id
        define('PAYPAL_SECRET', 'EOnodxiLqCFRHt_lIrKVftlAdO_ZCaN0dcbi2Q2TVZztqIxk3rJph9ApZPi866uxBMIGhrSE5RGuat3'); // Paypal secret
    }

   public function storePayment($paypalPaymentId, $userId) {
        $stmt = mysql_query("INSERT INTO payments(paypalPaymentId, id_user, create_time) VALUES(
                '$paypalPaymentId',
                '$userId',
                 NOW()

            )") or die(mysql_error());
       // $stmt->bind_param("sisssds", $paypalPaymentId, $userId, $create_time, $update_time, $state, $amount, $currency);
//$result = $stmt->execute();
       // $stmt->close();
 
        if ($stmt) {
            $stmt = mysql_query("UPDATE users SET type='1' WHERE id='".intval($userId)."'") or die(mysql_error());
            return true;
        } else {
            // task failed to create
            return NULL;
        }
    }

    function changeLang($id,$lang)
    {
        mysql_query("UPDATE users SET lang='".$this->safe($lang)."' WHERE id = '".intval($id)."'");

        $return = array(
                            "message" => 'changed'
                        );
        return $return;

    }

    function deleteFollow($id)
    {
        mysql_query("DELETE FROM follow WHERE id='".intval($id)."'");
        $return = array(
                            "message" => 'deleted'
                        );
        return $return;
    }
 
   function addFollow($id,$child)
   {
     $return = array();
     $q = mysql_query("SELECT * FROM users WHERE email = '".$this->safe($child)."' LIMIT 1");
     $n = mysql_num_rows($q);

     if($n > 0) {
             $row = mysql_fetch_array($q);
             $child = $row['id'];
             $query = mysql_query("SELECT * FROM follow WHERE id_user='".intval($id)."' AND id_child = '".intval($child)."' ");
             $nr = mysql_num_rows($query);
             if($nr > 0)
                {
                    $return = array(
                            "message" => 'You already follow this person'
                        );
                } else 
                {
                    $qu = mysql_query("INSERT INTO follow(id_user,id_child) VALUES('".intval($id)."','".intval($child)."')");
                    if($qu)
                    {
                       $return = array(
                            "message" => 'success'
                        ); 
                    }
                }
         } else
         {
            $return = array(
                            "message" => 'Person not found'
                        );
         }
         return $return;
                
   }
    function addUser($post)
    {
        $return = array();
        $info = array();
        $query = mysql_query("SELECT * FROM users WHERE email = '".$this->safe($post['email'])."' LIMIT 1");
        //echo "SELECT * FROM users WHERE email = '".$this->safe($post['email'])."' LIMIT 1";
        $nr = mysql_num_rows($query);
        if($nr > 0)
        {
            $row = mysql_fetch_array($query);
            $arr = array(
                    'id_user'        => $row['id'],
                    'email'          => $row['email'],
                    'name'           => $row['firstname'],
                    'photo'          => $row['photo'],
                    'birthdate'      => $row['birthdate'],
                    'type'           => $row['type'],
                    'notification'   => $row['notification']
                );
            $return['info'] =  $arr;
        } else {
            $query1 = mysql_query("INSERT INTO users(firstname,email,photo,birthdate,type) VALUES (
                    '".$this->safe($post['name'])."',
                    '".$this->safe($post['email'])."',
                    '".$this->safe($post['photo'])."',
                    '".$this->safe($post['birthdate'])."',
                    '0'
                )");
            if($query1)
            {
                    $query = mysql_query("SELECT * FROM users WHERE email = '".$this->safe($post['email'])."' LIMIT 1");
                    $nr = mysql_num_fields($query);
                    if($nr > 0)
                    {
                        $row = mysql_fetch_array($query);
                        $arr = array(
                                'id_user'        => $row['id'],
                                'email'          => $row['email'],
                                'name'           => $row['firstname'],
                                'photo'          => $row['photo'],
                                'birthdate'      => $row['birthdate'],
                                'type'           => $row['type'],
                                'notification'   => $row['notification']
                            );
            $return['info'] =  $arr;
                    }
            }
        }

        //$return['info'] = $info;
        return $return;
    }

     function userSearch($post)
    { 
        $return = array();
        $info = array();
        $query = mysql_query("SELECT * FROM users WHERE email = '".$this->safe($post['email'])."' LIMIT 1");
        //echo "SELECT * FROM users WHERE email = '".$this->safe($post['email'])."' LIMIT 1";
        $nr = mysql_num_rows($query);
        if($nr > 0)
        {
            $row = mysql_fetch_array($query);
            $arr = array(
                    'id_user'        => $row['id'],
                    'email'          => $row['email'],
                    'name'           => $row['firstname'],
                    'photo'          => $row['photo'],
                    'birthdate'       => $row['birthdate'],
                    'type'           => $row['type']
                );
            $return['info'] =  $arr;
        } else {
            $return['error'] =  1;
            $return['message'] =  "User not found";
        }
        return $return;
    }

     function getCalls($seek)
    { 
        $return = array();
        $calls = array();
        $seek = intval($seek);
        $query = mysql_query("SELECT * FROM calls WHERE id_user = '".$seek."' ORDER BY data DESC");
        //echo "SELECT * FROM calls WHERE id_user = '".$seek."' ORDER BY data DESC"";
        $nr = mysql_num_rows($query);
        if($nr > 0)
        {
            while ($row = mysql_fetch_array($query))
            {
                $arr = array(
                    'id'             => $row['id'],
                    'id_user'        => $row['id_user'],
                    'data'           => $row['data'],
                    'name'           => $row['name'],
                    'number'         => $row['number'],
                    'duration'       => $row['duration'],
                    'type'           => $row['type'],
                    'battery'        => $row['battery']
                );
                array_push($calls, $arr);
            }
           
            $return['calls'] =  $calls;
        } else {
            $return['calls'] =  $calls;
        }
        return $return;
    }

     function getMessages($seek)
    { 
        $return = array();
        $calls = array();
        $seek = intval($seek);
        $query = mysql_query("SELECT * FROM messages WHERE id_user = '".$seek."' ORDER BY data DESC");
        //echo "SELECT * FROM calls WHERE id_user = '".$seek."' ORDER BY data DESC"";
        $nr = mysql_num_rows($query);
        if($nr > 0)
        {
            while ($row = mysql_fetch_array($query))
            {
                $arr = array(
                    'id'             => $row['id'],
                    'id_user'        => $row['id_user'],
                    'data'           => $row['data'],
                    'number'         => $row['number'],
                    'body'           => $row['body'],
                    'type'           => $row['type'],
                    'battery'        => $row['battery']
                );
                array_push($calls, $arr);
            }
           
            $return['messages'] =  $calls;
        } else {
            $return['messages'] =  $calls;
        }
        return $return;
    }


    function getPhoneLocation($seek,$time)
    {

        $query = mysql_query("SELECT * FROM location WHERE id_user = '".$seek."' AND data >= '".$time."' LIMIT 1");
        $nr = mysql_num_rows($query);
        if($nr > 0)
        {
            $row = mysql_fetch_array($query);
            $arr = array(
                    'lat' => $row['lat'],
                    'lon' => $row['lon']
                );

             $address= $this->getaddress($row['lat'],$row['lon']);
                if($address)
                {
                   $arr['address']  = $address;
                }
                else
                {
                     $arr['address'] = "Not found";
                }

            $return['locations'] =  $arr;
        } else {
            $return['error'] =  1;
            $return['message'] =  "No calls found for this user";
        }
        return $return;
    }

   function getLocations($seek)
    { 
        $return = array();
        $calls = array();
        $seek = intval($seek);
        $query = mysql_query("SELECT * FROM location WHERE id_user = '".$seek."' AND data >= CURDATE() ORDER BY data DESC LIMIT 6");
        //echo "SELECT * FROM calls WHERE id_user = '".$seek."' ORDER BY data DESC"";
        $nr = mysql_num_rows($query);
        if($nr > 0)
        {
            while ($row = mysql_fetch_array($query))
            {
                $arr = array(
                    'id'             => $row['id'],
                    'id_user'        => $row['id_user'],
                    'data'           => $row['data'],
                    'lat'            => $row['lat'],
                    'lon'            => $row['lon']
                );
                $address= $this->getaddress($row['lat'],$row['lon']);
                if($address)
                {
                   $arr['address']  = $address;
                }
                else
                {
                     $arr['address'] = "Not found";
                }
                array_push($calls, $arr);
            }
           
            $return['locations'] =  $calls;
        } else {
            $return['error'] =  1;
            $return['message'] =  "No calls found for this user";
        }
        return $return;
    }

    public function getData($calls,$sms,$loc,$battery)
    {
        if(count($calls) > 0 )
        {
            foreach ($calls as $key => $value) {
                $value = $value['nameValuePairs'];
                if(isset($value['name'])) $name = $value['name']; else $name = "";
                $q = mysql_query("SELECT * FROM calls WHERE data = '".$this->safe($value['date'])."' AND id_user = '".$this->safe($value['id_user'])."' AND `number` = '".$this->safe($value['number'])."'");
                if(mysql_num_rows($q) <= 0) {
                $query = mysql_query("INSERT INTO calls(data,id_user,`number`,name,duration,type,battery) VALUES(
                    '".$this->safe($value['date'])."',
                    '".$this->safe($value['id_user'])."',
                    '".$this->safe($value['number'])."',
                    '".$this->safe($name)."',
                    '".$this->safe($value['duration'])."',
                    '".$this->safe($value['type'])."',
                    '".$this->safe($battery)."'
                    )");
                }
            }
        }
        if(count($sms) >0 )
        {
            foreach ($sms as $key => $value) {
                $value = $value['nameValuePairs'];
                //print_r($value);
                if($value['number'] !="" && $value['body'] !="")
                {
                    $q = mysql_query("SELECT * FROM messages WHERE data = '".$this->safe($value['date'])."' AND id_user = '".$this->safe($value['id_user'])."' AND `number` = '".$this->safe($value['number'])."'");
                    if(mysql_num_rows($q) <= 0) {
                    $query = mysql_query("INSERT INTO messages(data,id_user,`number`,body,type,battery) VALUES(
                        '".$this->safe($value['date'])."',
                        '".$this->safe($value['id_user'])."',
                        '".$this->safe($value['number'])."',
                        '".$this->safe($value['body'])."',
                        '".$this->safe($value['type'])."',
                        '".$this->safe($battery)."'

                        )");
                    }
                }  
            }
        }
        if(count($loc) >0 )
        {
            foreach ($loc as $key => $value) {
                $value = $value['nameValuePairs'];
                if($value['lon'] !="0.0" && $value['lat'] !="0.0")
                {
                    $q = mysql_query("SELECT * FROM location WHERE data = '".$this->safe($value['date'])."' AND id_user = '".$this->safe($value['id_user'])."' AND `lat` = '".$this->safe($value['lat'])."'");
                    if(mysql_num_rows($q) <= 0) {
                    $query = mysql_query("INSERT INTO location(data,id_user,`lon`,lat) VALUES(
                        '".$this->safe($value['date'])."',
                        '".$this->safe($value['id_user'])."',
                        '".$this->safe($value['lon'])."',
                        '".$this->safe($value['lat'])."'
                        )");
                    }
                }    
            }
        }
      //  mysql_query("DELETE t1 FROM calls t1, calls t2 WHERE t1.id < t2.id AND t1.data = t2.data AND t1.id_user = t2.id_user AND t1.number = t2.number");
    $return = array("info"=> "success");
    return $return;

    }

    function changeNotifiaction($val,$id)
    {
        mysql_query("UPDATE users SET notification = '".$this->safe($val)."' WHERE id = '".$this->safe(intval($id))."'");
    }

    function getaddress($lat,$lng)
    {
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
    $json = @file_get_contents($url);
    $data=json_decode($json);
    $status = $data->status;
    if($status=="OK")
    return $data->results[0]->formatted_address;
    else
    return false;
    }

    function days($d1,$d2) {

        //$date1  = strtotime($startDate);
       // $date2  = strtotime($endDate);
        return round(abs(strtotime($d1)-strtotime($d2))/86400);
    }

     function days1($startDate,$endDate) {

        //$date1  = strtotime($startDate);
       // $date2  = strtotime($endDate);
        $datetime1 = new DateTime($startDate);

        $datetime2 = new DateTime($endDate);

       // $res    =  (int)(($date2-$date1));       
       $difference = $datetime1->diff($datetime2); 

    return $difference->d ;
    }
    function safe($value){ 
       return mysql_real_escape_string($value); 
    }     

    function to_url($str, $replace=array(), $delimiter='-') {
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = strtolower(trim($clean, '-'));

        return $clean;
    }

    function sort_array_of_array(&$array, $subfield)
    {
        $sortarray = array();
        foreach ($array as $key => $row)
        {
            $sortarray[$key] = $row[$subfield];
        }

        array_multisort($sortarray, SORT_ASC, $array);
    }
}