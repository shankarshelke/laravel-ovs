<?php  

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FinanceTeamModel;
use App\Models\MoneyDistributionModel;
use App\Models\WebAdmin;
use Validator;
use Session;
use DB;


class FinanceTeamController extends Controller
{

	function __construct()
	{
		// dd('in controller');
		//$this->Galleryservice =  new Galleryservice();
		$this->BaseModel					= new FinanceTeamModel();
		$this->MoneyDistributionModel		= new MoneyDistributionModel();
        $this->WebAdmin                     = new WebAdmin();
	}

	

	public function create(Request $request)
	{
        $arr_rules['full_name']      	= "required"; 
        $arr_rules['email']      	    = "required"; 
        $arr_rules['contact']      	    = "required"; 
        $arr_rules['password']      	= "required"; 
        $arr_rules['ward_id']      	    = "required"; 
		
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails())
		{	
            $errors = $validator->errors()->messages();
			return $this->build_response('error','Validation Error',[$errors]);
		}
		$name = explode(' ', trim($request->input('full_name', null)));
		if(count($name) == 2){
			$first_name = $name[0];
			$last_name = $name[1];
		}else if(count($name) == 3){
			$first_name = $name[0];
			$last_name = $name[2];
		}else{
			$first_name = $name[0];
			$last_name = '';
		}

        $arr_data['email']          = $request->input('email', null);
        $arr_data['full_name']     = $request->input('full_name', null);
        $arr_data['first_name']     = $first_name;
        $arr_data['last_name']      = $last_name;
        $arr_data['role']           = $request->input('role', 'Accounting');
        $arr_data['admin_type']     = 'SUBADMIN';
        $arr_data['password']       = bcrypt($request->input('password', null));
        $arr_data['contact']        = $request->input('contact', null);
        $arr_data['address']        = $request->input('address', null);
		$arr_response = $this->WebAdmin->create($arr_data);
        if($this->WebAdmin){
            $dara_arr = [
                'subadmin_id' => $arr_response->id,
                'ward' => $request->input('ward_id', null),
            ];
            $this->BaseModel->create($dara_arr);
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

    public function finance_team_listing(Request $request)
	{
		$additonal_data = $rep_data = [];
		$obj_data = $this->BaseModel->select('finance_team.*','web_admin.first_name', 'web_admin.last_name', DB::raw("CONCAT(web_admin.first_name,' ',web_admin.last_name) as full_name"))
                    ->join('web_admin','finance_team.subadmin_id','=','web_admin.id')
                    ->withCount(['get_distribution_amount as total_amount' => function($q){
                        $q->select(DB::raw('SUM(amount) as total_amount'));
                    }])
                    ->withCount(['get_voter_distribution_amount as total_distribution_amt' => function($q){
                        $q->select(DB::raw('SUM(amount) as total_distribution_amt'));
                    }]);

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

		$arr_data = $obj_data->get()->toArray();
		if($arr_data)
		{
			$total_amount = array_sum(array_column($arr_data, 'total_amount'));
			$total_distribution_amt = array_sum(array_column($arr_data, 'total_distribution_amt'));
			$rep_data['total_amount'] = number_format($total_amount);
			$rep_data['remening_amount'] = number_format(($total_amount - $total_distribution_amt));
			foreach ($arr_data as $key => $value) {
				$value['total_amount'] = number_format($value['total_amount']);
				$rep_data['data'][] = $value;
			}
			return $this->build_response('success','Finance Team Listing.',$rep_data);
		}
		else
		{
			return $this->build_response('error','No Record Found.',[]);
		}
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