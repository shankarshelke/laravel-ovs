<?php

namespace App\Http\Controllers\Front\operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TempAircraftModel;
use App\Models\AircraftModel;
use App\Models\AircraftOwnerModel;
use App\Models\CountryModel;
use App\Models\AmenitiesModel;
use App\Models\EquipmentModel;
use App\Models\AircraftTypeModel;
use App\Models\AircraftImagesModel;
use App\Models\AvailabilityModel;
use App\Common\Traits\MultiActionTrait;
use Auth;
use Validator;
use Session;
use File;
use DB;
use Image;

class AvailabilityController extends Controller
{
    use MultiActionTrait;
    function __construct()
    {
        $this->arr_view_data        = [];
        $this->module_title         = "Home";
        $this->module_view_folder   = "front.operator.availability.";
        $this->TempAircraftModel    = new TempAircraftModel();
        $this->AircraftModel        = new AircraftModel();
        $this->AircraftOwnerModel   = new AircraftOwnerModel();
        $this->AmenitiesModel       = new AmenitiesModel();
        $this->CountryModel         = new CountryModel();
        $this->EquipmentModel       = new EquipmentModel();
        $this->AircraftTypeModel    = new AircraftTypeModel();
        $this->AircraftImagesModel  = new AircraftImagesModel();
        $this->AvailabilityModel    = new AvailabilityModel();

        $this->ip_address        = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false; 
        $this->operator_auth     = auth()->guard('operator'); 

        $this->aircraft_images_base_img_path   = base_path().config('app.project.img_path.aircraft_image');
        $this->tmp_aircraft_images_base_img_path= base_path().'/uploads/aircraft_owner/temp_aircraft_image/';
        $this->tmp_aircraft_images_public_img_path= url('/').'/uploads/aircraft_owner/temp_aircraft_image/';
        $this->aircraft_images_public_img_path = url('/').config('app.project.img_path.aircraft_image');
    }

    public function index($enc_id)
    {
        $id = base64_decode($enc_id);

        if(!is_numeric($id)){
            Session::flash('error', 'Oops, Something went wrong!');
            return redirect()->back();
        }

        $operator_id = $this->operator_auth->user()->id;

        $arr_aircraft = $arr_aircraft_type = $arr_data_reviews  = [];
        $page_link = '';

        $obj_availability = $this->AvailabilityModel->where('aircraft_id', $id)
                                                    ->whereHas('get_aircraft_details')
                                                    ->with(['get_aircraft_details'=>function($q){
                                                        $q->with(['get_aircraft_type']);
                                                    }])
                                                    ->paginate(15);

        if($obj_availability)
        {
            $arr_availability = $obj_availability->toArray();
            $page_link    = $obj_availability->links();
        }

        $this->arr_view_data['module_title']        = " My Aircrafts";
        $this->arr_view_data['sub_module_title']    = " Availability";
        $this->arr_view_data['module_url_path']     =  url('/').'/operator/aircrafts';
        $this->arr_view_data['page_link']           = $page_link;
        $this->arr_view_data['arr_availability']    = $arr_availability;
        $this->arr_view_data['enc_id']              = $enc_id;
        $this->arr_view_data['page_title']          = 'Aircraft Listing';

        return view($this->module_view_folder.'index',$this->arr_view_data);
    }

    public function add(Request $request, $enc_id)
    {
        $id = base64_decode($enc_id);

        if(!is_numeric($id)){
            Session::flash('error', 'Oops, Something went wrong!');
            return redirect()->back();
        }

        $is_exists = $this->AircraftModel->where('id', $id)->first();

        if(!$is_exists){
            Session::flash('error', 'Oops, Something went wrong!');
            return redirect()->back();
        }

        $arr_availability = [];

        $obj_avail = $this->AvailabilityModel->where('aircraft_id', $id)->get();

        if($obj_avail->count() > 0){
            $arr_availability = $obj_avail->toArray();
        }

        $operator_id = $this->operator_auth->user()->id;

        $this->arr_view_data['module_title']        = " My Aircrafts";
        $this->arr_view_data['sub_module_title']    = " Availability";
        $this->arr_view_data['module_url_path']     =  url('/').'/operator/aircrafts';
        $this->arr_view_data['enc_id']              = $enc_id;
        $this->arr_view_data['arr_availability']    = $arr_availability;
        $this->arr_view_data['page_title']          = 'Add availability';

        return view($this->module_view_folder.'add',$this->arr_view_data);
    }

    public function store(Request $request, $enc_id)
    {
        $id = base64_decode($enc_id);
        if(!is_numeric($id)){
            Session::flash('error', 'Oops, Something went wrong!');
            return redirect()->back();
        }

        $operator = $this->operator_auth->check();

        $arr_rules['from_date']     = "required";
        $arr_rules['to_date']       = "required";

        $validator = validator::make($request->all(),$arr_rules);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $from   = date('Y-m-d', strtotime($request->input('from_date')));
        $to     = date('Y-m-d', strtotime($request->input('to_date')));
        
        $date = new \DateTime($from);
        $now = new \DateTime();
        
        if($date < $now) {
            Session::flash('error', 'Error, Today and Past dates not allowed!');
            return redirect()->back();
        }

        $is_exists = $this->AvailabilityModel->where('aircraft_id', $id)
                                            ->where(function($query) use ($from,$to){
                                                $query->whereBetween('from_date', [$from, $to]);
                                                $query->orWhereBetween('to_date', [$from, $to]);
                                             })
                                             /*
                                            ->whereBetween('from_date', [$from, $to])
                                            ->OrWhereBetween('to_date', [$from, $to])*/
                                            ->get();

        if($is_exists->count() > 0){
            $min = $max = '';
            foreach($is_exists as $row){
                $min = (strtotime($min) >= strtotime($row->from_date) || $min == '') ? $row->from_date :$min;
                $max = (strtotime($max) <= strtotime($row->to_date) || $max == '') ? $row->to_date : $max;
            }

            $msg = ' Aircraft is already available between '.date('d M Y',strtotime($min)).' - '.date('d M Y',strtotime($max));

            Session::flash('error', $msg);
            return redirect()->back();
        }else{
            $arr_ins['aircraft_id']     = $id;
            $arr_ins['from_date']       = $from;
            $arr_ins['to_date']         = $to;

            $status = $this->AvailabilityModel->create($arr_ins);

            if($status){
                Session::flash('success', ' Availability added successfully!');
                return redirect()->to(url('/').'/operator/aircrafts/availability/'.$enc_id);
            }else{
                Session::flash('error', 'Oops, Error while inserting data!');
                return redirect()->back();
            }
        }


    }
    public function unblock($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_unblock(base64_decode($enc_id)))
        {
            Session::flash('success', ' Aircraft status changed Successfully');
            return redirect()->back();
        }
        else
        {
            Session::flash('error', 'Problem Occured While changing status ');
        }

        return redirect()->back();
    }
    public function block($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_block(base64_decode($enc_id)))
        {
            Session::flash('success',' Aircraft status changed Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While changing status ');
        }

        return redirect()->back();
    }


    public function perform_unblock($id)
    {
        if($id!=null)
        {
            $responce = $this->AvailabilityModel->where('id',$id)->update(['is_available'=>'YES']);
            if($responce)
            {
                return TRUE;
            }
            return FALSE;            
        }
        return FALSE;
    }

    public function perform_block($id)
    {   
        if($id!=null)
        {
            $responce = $this->AvailabilityModel->where('id',$id)->update(['is_available'=>'NO']);
            if($responce)
            {
                return TRUE;
            }  
            return FALSE;          
        }
        return FALSE;
    }



}