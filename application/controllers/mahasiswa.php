<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require "vendor\autoload.php";

use Restserver\Libraries\REST_Controller;
use \Firebase\JWT\JWT;

class mobil extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
    }

    //Menampilkan data 
    public function index_get() {
        $authHeader = $this->input->get_request_header('Authorization');
        $arr = explode(" ", $authHeader);
        $jwt = isset($arr[1])? $arr[1] : "";
        $secretkey = base64_encode("gampang");

        if($jwt){
            try {
                $decode = JWT::decode($jwt, $secretkey, array('HS256'));
                $id = $this->get('id');
        if ($id == '') {
            $data = $this->db->get('brand')->result();
        } else {
            $this->db->where('id', $id);
            $data = $this->db->get('brand')->result();
        }
        $result =["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
                  "code"=>200,
                  "message"=>"Response successfully",
                  "data"=>$data];
        $this->response($result, 200);
            }catch (Exception $e){

                $result = ["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
                        "code"=>401,
                        "message"=>"Access denied",
                        "data"=>null];
                $this->response($result, 401);
            }
        }else{
            $result = ["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
                        "code"=>401,
                        "message"=>"Access denied",
                        "data"=>null];
                $this->response($result, 401);
        }
    }

}
?>
