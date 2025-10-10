<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Request;
use DB;

class DataReceiverController extends Controller
{
    //
    public function saveData(Request $request){
       $input= file_get_contents('php://input');
       $input=$this->prepareGPS($input);
       //$input = $request::all();
       $result=DB::insert('INSERT INTO data(data) VALUES (?)', [$input]);
       return $result;
    }
    public function sendData(Request $request){
            $input= file_get_contents('php://input');
            $data = str_replace('_id', 'id', $input);
            $ch = curl_init();
            $username='elastic';
            $password='changeme';
            curl_setopt($ch, CURLOPT_URL, "http://192.168.1.76:9200/ukudox3/_doc");
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
    public function createMapping(){
        // {
        //     "mappings": {
        //       "properties": {
        //         "location": {
        //           "type": "geo_point"
        //         }
        //       }
        //     }
        //   }
    }
    public function prepareGPS($originalData){
        //$post =json_decode($data);
        //$originalData=file_get_contents('php://input');
        $json =json_decode($originalData,false);
        $latitude=$json->location[0];
        $longitude=$json->location[1];
        $newString=$longitude.','.$latitude;
        $oldString=$latitude.','.$longitude;
        $finalData = str_replace('_id', 'id',$originalData);
        $test=str_replace($oldString, $newString,$finalData);
        return $test;
    }
}
