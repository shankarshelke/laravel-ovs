<?php  

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WardsModel;
use App\Models\WebAdmin;
use Validator;
use Session;
use DB;


class WardController extends Controller
{

	function __construct()
	{
		// dd('in controller');
		//$this->Galleryservice =  new Galleryservice();
		$this->BaseModel					= new WardsModel();
	}

	

	public function create(Request $request)
	{
        $arr_rules['fist_name']      	= "required"; 
        $arr_rules['last_name']      	= "required"; 
        $arr_rules['email']      	    = "required"; 
        $arr_rules['contact']      	    = "required"; 
        $arr_rules['password']      	= "required"; 
        $arr_rules['address']      	    = "required"; 
        $arr_rules['word_id']      	    = "required"; 
		
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails())
		{	
            $errors = $validator->errors()->messages();
			return $this->build_response('error','Validation Error',[$errors]);
		}
        $arr_data['email']          = $request->input('email', null);
        $arr_data['first_name']     = $request->input('first_name', null);
        $arr_data['last_name']      = $request->input('last_name', null);
        $arr_data['role']           = $request->input('role', 'Accounting');
        $arr_data['admin_type']     = $request->input('admin_type', null);
        $arr_data['password']       = bcrypt($request->input('password', null));
        $arr_data['contact']        = $request->input('contact', null);
        $arr_data['address']        = $request->input('address', null);
		$arr_response = $this->WebAdmin->create($arr_data);
        if($this->WebAdmin){
            $dara_arr = [
                'subadmin_id' => $arr_response->id,
                'word' => $request->input('word_id', null),
            ];
            $this->WebAdmin->create($dara_arr);
        }
		return $this->build_response('success','Add team successfully', []);
	}
	public function add_money(Request $request)
	{
        $arr_rules['subadmin_id']      	= "required"; 
		$arr_rules['amount']  	        = "required";
		
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{	
            $errors = $validator->errors()->messages();
			return $this->build_response('error','Validation Error',[$errors]);
		}
        $dara_arr = [
            'subadmin_id' => $request->input('subadmin_id'),
            'amount' => $request->input('amount'),
        ];
		$arr_response = $this->MoneyDistributionModel->create($dara_arr);
		return $this->build_response('success','Add amount successfully', []);
	}

    public function ward_listing(Request $request)
	{
		$obj_data = $this->BaseModel->where('status', '1');
        $page = 0;
        $record_to_show = 15; 
        $from = 0;
        $page = $request->input('page', null);

		$search = $request->input('search', null);

		if($page){
			$page = $page -1;
			$from = $page * $record_to_show;
			$obj_data =  $obj_data->offset($from)->limit($record_to_show);
		}

        if($search){
			$obj_data =  $obj_data->where('first_name', 'LIKE', "%".$search."%")
			->orWhere('last_name', 'LIKE', "%".$search."%");
		}

		$count = $obj_data->count();
		$arr_data = $obj_data->get()->toArray();
		return ($arr_data) ? $this->build_response('success','Ward Listing.',$arr_data, ['count' => $count]) : $this->build_response('error','No Record Found.',[]);
	}
    
	public function edit(Request $request, $enc_id)
	{
        //dd('hiii')
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