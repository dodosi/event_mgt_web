<?php

 $data=file_get_contents('php://input');
 require_once 'db_connect.php';

 $sql="INSERT INTO `data`(`data`) VALUES ('$data')";
		if($connect->query($sql) === TRUE) {
				$response='ok';
				
		} else {
				
		}
$resStr = str_replace('_id', 'id', $data);
	
$data=sendData($resStr);
echo $data;

function sendData($data){
	$ch = curl_init();
	$username='elastic';
	$password='changeme';
	curl_setopt($ch, CURLOPT_URL, "http://192.168.1.76:9200/ukudox/_doc");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json",
	  "Accept: application/json"
	));

	$response = curl_exec($ch);
	curl_close($ch);
//	$data=json_decode($response);
//	$status=$data->status;
    return $response;
}	

?>