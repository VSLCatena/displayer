<?php
/************************************************
PHP
************************************************/
error_reporting(E_ALL);
ini_set('display_errors', '1');
/************************************************
PHP END
************************************************/

/************************************************
Load external files and definitions
************************************************/

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


function dictToArray($data) {
	$dictarray=array();
	$data1 = str_replace('{', '', $data);
	$data2 = str_replace('}', '', $data1);
	$data3 = explode(", ", $data2);
	foreach ($data3 as $key => $value) {
		$v = explode(": ",$value);
		$dictarray[str_replace('\'','',$v[0])]=str_replace('\'','',$v[1]);	
		}
    return $dictarray;
}


/************************************************
Load external files and definitions END
************************************************/

//client
$host = "127.0.0.1"; 
$port = 25003;
$myip = getRealIpAddr();
$pydict = 	"{'name':'PHP-" . $myip . "','asset_uri': 'None', 'time': 'None'}";
#print $pydict;
// create socket 
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket<br>"); 
// connect to server 
$result = socket_connect($socket, $host, $port) or die("Could not connect to server<br>"); 
// send string to server 
socket_write($socket, $pydict, strlen($pydict)) or die("Could not send data to server<br>"); 
// get server response 
$result = socket_read ($socket, 4096) or die("Could not read server response<br>"); 
// close socket
socket_close($socket); 

$serverdata = dictToArray($result);
//print_r($serverdata);
echo json_encode($serverdata);

?>
