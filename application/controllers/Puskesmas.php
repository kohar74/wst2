<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class mobil extends REST_Controller{

	function __construct($config = 'rest'){
		parent::__construct($config);
		$this->load->driver('cache', array('adapter' => 'apc','backup' => 'file'));
	}

	//Menampilkan data
	public function index_get(){

		$id = $this->get('id');
		$mobil=[];
		if ($id == '') {
			$data = $this->db->get('mobil')->result();
			foreach ($data as $row => $key): 
				$mobil[]=[
                        "mobilID"=>$key->mobilID,
						"wilayah"=>$key->wilayah,
						"nama_mobil"=>$key->nama_mobil,
						"alamat_mobil"=>$key->alamat_mobil,
					];
			endforeach;

			$etag = hash('sha256', time());
			$this->cache->save($etag, $mobil, 300);
			$this->output->set_header('ETag:'.$etag);
			$this->output->set_header('Cache-Control: must-revalidate');
			if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag){
				$this->output->set_header('HTTP/1.1 304 Not Modified');
			}else{
				$result = [
					"took"=>$_SERVER["REQUEST_TIME_FLOAT"],
					"code"=>200,
					"message"=>"Response successfully",
					"data"=>$mobil
				];
				$this->response($result, 200);
			}

		}else{
			$this->db->where('mobilID', $id);
			$data = $this->db->get('mobil')->result();
			$mobil[]=[
				"mobilID"=>$key->mobilID,
						"wilayah"=>$key->wilayah,
						"nama_mobil"=>$key->nama_mobil,
						"alamat_mobil"=>$key->alamat_mobil,
			];
		$etag = hash('sha256', $data[0]->mobilID);
		$this->cache->save($etag, $mobil, 300);
		$this->output->set_header('ETag:'.$etag);
		$this->output->set_header('Cache-Control: must-revalidate');
		if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag){
			$this->output->set_header('HTTP/1.1 304 Not Modified');
		}else{
			$result = [
				"took"=>$_SERVER["REQUEST_TIME_FLOAT"],
				"code"=>200,
				"message"=>"Response successfully",
				"data"=>$mobil];
			$this->response($result, 200);
		}
	}
}
?>