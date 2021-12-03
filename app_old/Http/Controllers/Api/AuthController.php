<?php  

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Common\Services\Authservice;
use App\Common\Services\Galleryservice;
use App\Common\Services\Voterservice;
use Session;


class AuthController extends Controller
{

	function __construct()
	{
		// dd('in controller');
		$this->Authservice    =  new Authservice();
		$this->Voterservice   =  new Voterservice();
		//$this->Galleryservice =  new Galleryservice();
	}

	public function login(Request $request)
	{/*dd('hiiii');*/
		$arr_response = $this->Authservice->processlogin($request);
		//dd($arr_response);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

	
	public function register(Request $request)
	{//dd('hiiii');
		$arr_response = $this->Authservice->register($request);
		//dd('hiiii');
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

	

	public function verify_user(Request $request,$id)
	 {  // dd('verify_user');
		$arr_response = $this->Authservice->verify_user($request,$id);
		//return view('success');
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

	public function forgot_password(Request $request)
	{  //dd('hello');  
		$arr_response = $this->Authservice->forgot_password($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

	public function new_password(Request $request,$id,$email,$reset_code)
	{   
		$arr_response = $this->Authservice->new_password($request,$id,$email,$reset_code);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}
	
    public function change_password(Request $request)
	{
		//dd('hello');
		$arr_response = $this->Authservice->change_password($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);	
	}

    
	public function get_country(Request $request)
	{  
		$arr_response = $this->Authservice->get_country($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);	
	}


	public function profile_details(Request $request)
	{
		$arr_response = $this->Authservice->profile_details($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

	

	public function update_profile(Request $request)
	{
		$arr_response = $this->Authservice->update_profile($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}
/********************************************Voter ?Money Distributed***********************************/
	


	public function transfer_money_voter(Request $request)
	{
		$arr_response = $this->Authservice->transfer_money_voter($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

	public function view_voter_money_detail(Request $request,$id)
	{
		$arr_response = $this->Authservice->view_voter_money_detail($request,$id);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}
	public function votermoney_listing(Request $request)
	{
		$arr_response = $this->Authservice->votermoney_listing($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}
	/*public function subadmin_distributed_money_to_voter(Request $request)
	{
		$arr_response = $this->Authservice->subadmin_distributed_money_to_voter($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}*/
	/*public function update_profile_voter(Request $request)
	{
		$arr_response = $this->Authservice->update_profile_voter($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}*/

	public function create(Request $request)
	{/*dd('hiiii');*/
		$arr_response = $this->Voterservice->create_voter($request);
		//dd($arr_response);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

   public function voter_listing(Request $request)
	{
		$arr_response = $this->Voterservice->voter_listing($request);
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

	
/*********************************Voter Money Distributed***********************************************/

	public function logout(Request $request)
	{
		$arr_response = $this->Authservice->logout($request);
		return $this->build_response($arr_response['status'],$arr_response['msg'],$arr_response['data']);
	}

}


?>