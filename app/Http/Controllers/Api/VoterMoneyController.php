<?php  

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Common\Services\VoterMoneyService;
use App\Common\Services\Galleryservice;
use Session;


class VoterMoneyController extends Controller
{

	function __construct()
	{	
		// dd('in controller');
		$this->VoterMoneyService =  new VoterMoneyService();

		//$this->Galleryservice =  new Galleryservice();
	}

	public function transfer_money_voter(Request $request)
	{
		$arr_response = $this->VoterMoneyService->transfer_money_voter($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

	public function view_voter_money_detail(Request $request,$id)
	{
		$arr_response = $this->VoterMoneyService->view_voter_money_detail($request,$id);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}
	public function subadmin_distributed_money_to_voter(Request $request)
	{
		$arr_response = $this->VoterMoneyService->subadmin_distributed_money_to_voter($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}
}


?>