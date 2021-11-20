<?php  

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Common\Services\Authservice;
use App\Common\Services\Voterservice;
use Session;


class VoterController extends Controller
{

	function __construct()
	{
		// dd('in controller');
		$this->Authservice         =  new Authservice();
		$this->Voterservice 	   =  new Voterservice();
		//$this->Galleryservice =  new Galleryservice();
	}

	public function create(Request $request)
	{/*dd('hiiii');*/
		$arr_response = $this->Voterservice->create_voter($request);
		//dd($arr_response);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

	public function edit(Request $request, $enc_id)
	{//dd('hiii')
		$arr_response = $this->Voterservice->edit($request,$enc_id);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

	public function view($enc_id)
	{
		$arr_response = $this->Voterservice->view($enc_id);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

	public function delete($enc_id)
	{
		$arr_response = $this->Voterservice->delete($enc_id);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

}


?>