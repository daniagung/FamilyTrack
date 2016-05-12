<?php
class inForm
{

	private $db;
    private $error;
    public $config;

    function __construct()
    {
        $this->config = include("config.php");
        $connection =  mysql_connect($this->config['server'], $this->config['username'], $this->config['password']) or die ('Error');
        mysql_set_charset('utf8',$connection);
        mysql_select_db($this->config['database'],$connection);
        define('DEFAULT_CURRENCY', 'USD');
        define('PAYPAL_CLIENT_ID', 'AUQ_jnZTY46_65xMhwZiP8LBWPRCa-XMTfeJI35T9aPgOo4f9tmNMuU6Icl97O2jO1WsvVDBhExq3F_e'); // Paypal client id
        define('PAYPAL_SECRET', 'EOnodxiLqCFRHt_lIrKVftlAdO_ZCaN0dcbi2Q2TVZztqIxk3rJph9ApZPi866uxBMIGhrSE5RGuat3'); // Paypal secret
    }


public function getConfig()
{
    return  $this->config;
}


public function getCount($table)
{
    $query = mysql_query("SELECT * FROM ".$table);
    $nr = mysql_num_rows($query);
    return $nr;
}

public function addPro($id)
{
    mysql_query("UPDATE users SET type = '1'  WHERE id = '".intval($id)."'");
    mysql_query("INSERT INTO payments (id_user,create_time) VALUES('".intval($id)."',NOW())");
}


public function expirePro($id)
{
     $return = false;
     $user = $this->getUser($id);
     $data = $user['data'];
     $now = time(); // or your date as well
     $your_date = strtotime($data);
     $datediff = $now - $your_date;
     $days =  floor($datediff/(60*60*24));
    return $days;
}
public function checkPro($id)
{
    $return = false;
    $user = $this->getUser($id);
    if($user['type'] == '1')
        {
            $return = true;
        }
        else{
             $data = $user['data'];
             $now = time(); // or your date as well
             $your_date = strtotime($data);
             $datediff = $now - $your_date;
             $days =  floor($datediff/(60*60*24));
             if($days <= 7) $return = true; else $return = false;
        }
    return $return;

}
   public function getUser($id)
   {
        $return = array();
        $query = mysql_query("SELECT * FROM users WHERE id ='".$this->safe($id)."' LIMIT 1");
        if($query === FALSE) { 
            die(mysql_error()); // TODO: better error handling
        }

        while($row = mysql_fetch_array($query))
        {
            $return = $row;
        }

        return $return;
   }

   public function getChild($id)
   {
        $return = array();
        $query = mysql_query("SELECT * FROM users WHERE id ='".$this->safe($id)."' LIMIT 1");
        if($query === FALSE) { 
            die(mysql_error()); // TODO: better error handling
        }

        while($row = mysql_fetch_array($query))
        {
            $return['user'] = $row;
        }

        $q = mysql_query("SELECT * FROM calls WHERE  id_user ='".$this->safe($id)."' ");
        $return['calls'] = mysql_num_rows($q);

        $q1 = mysql_query("SELECT * FROM messages WHERE id_user ='".$this->safe($id)."' ");
        $return['messages'] = mysql_num_rows($q1);

        $q2 = mysql_query("SELECT * FROM location WHERE  id_user ='".$this->safe($id)."' ");
        $return['locations'] = mysql_num_rows($q2);
        return $return;

   }


   public function getMessages($id)
   {
        $return = array();
        $query = mysql_query("SELECT * FROM messages WHERE id_user ='".$this->safe($id)."' ORDER BY data DESC ");
        while($row = mysql_fetch_array($query))
        {
            array_push($return,$row);
        }

        return $return;
   }

   public function getCalls($id)
   {
        $return = array();
        $query = mysql_query("SELECT * FROM calls WHERE id_user ='".$this->safe($id)."' ORDER BY data DESC ");
        while($row = mysql_fetch_array($query))
        {
            array_push($return,$row);
        }

        return $return;
   }


   public function getLocations($id)
   {
        $return = array();
        $query = mysql_query("SELECT * FROM location WHERE id_user ='".$this->safe($id)."' ORDER BY data DESC ");
        while($row = mysql_fetch_array($query))
        {
            array_push($return,$row);
        }

        return $return;
   }


   public function getChilds($id)
   {
        $return = array();
        $query = mysql_query("SELECT * FROM follow WHERE id_user ='".$this->safe($id)."' ORDER BY id DESC ");
        while($row = mysql_fetch_array($query))
        {
            $query1 = mysql_query("SELECT * FROM users WHERE id ='".$this->safe($row['id_child'])."' LIMIT 1 ");
            $row1 = mysql_fetch_array($query1);
            array_push($row1,$row['id']);
            array_push($return, $row1);
        }

        return $return;

   }


   public function getParents($id)
   {

        $return = array();
        $query = mysql_query("SELECT * FROM follow WHERE id_child ='".$this->safe($id)."' ORDER BY id DESC ");
        while($row = mysql_fetch_array($query))
        {
            $query1 = mysql_query("SELECT * FROM users WHERE id ='".$this->safe($row['id_user'])."' LIMIT 1 ");
            $row1 = mysql_fetch_array($query1);
            //array_push($row1,$row['id']);
            array_push($return, $row1);
        }

        return $return;
   }

    public function getaddress($lat,$lng)
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