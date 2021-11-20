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

use Auth;
use Validator;
use Session;
use File;
use DB;
use Image;

class AircraftController extends Controller
{
    function __construct()
    {
        $this->arr_view_data        = [];
        $this->module_title         = "Home";
        $this->module_view_folder   = "front.operator.";
        $this->TempAircraftModel    = new TempAircraftModel();
        $this->AircraftModel        = new AircraftModel();
        $this->AircraftOwnerModel   = new AircraftOwnerModel();
        $this->AmenitiesModel       = new AmenitiesModel();
        $this->CountryModel         = new CountryModel();
        $this->EquipmentModel       = new EquipmentModel();
        $this->AircraftTypeModel    = new AircraftTypeModel();
        $this->AircraftImagesModel  = new AircraftImagesModel();

        $this->common_url        = url('/');
        $this->operator_url_path = url(config('app.project.operator_panel_slug'));
        $this->module_url_path   = $this->operator_url_path."/aircraft";
        $this->ip_address        = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false; 
        $this->operator_auth     = auth()->guard('operator'); 

        $this->aircraft_images_base_img_path   = base_path().config('app.project.img_path.aircraft_image');
        $this->tmp_aircraft_images_base_img_path= base_path().'/uploads/aircraft_owner/temp_aircraft_image/';
        $this->tmp_aircraft_images_public_img_path= url('/').'/uploads/aircraft_owner/temp_aircraft_image/';
        $this->aircraft_images_public_img_path = url('/').config('app.project.img_path.aircraft_image');
    }

    public function index(Request $request)
    {
           /* Search  */
        $search = '';
        if(\Request::has('search')&& \Request::get('search')!='')
        {
            $search = \Request::get('search');
        }
        /* Search  */
        $operator_id = $this->operator_auth->user()->id;

        $arr_aircraft = $arr_aircraft_type = $arr_data_reviews  = [];
        $page_link ='';
        $obj_aircraft_type = $this->AircraftTypeModel->get();;

        if($obj_aircraft_type)
        {
            $arr_aircraft_type = $obj_aircraft_type->toArray();
        }

        //$obj_aircraft =$this->AircraftModel->where('aircraft_owner_id', $operator_id);
     

        $obj_aircraft =$this->AircraftModel->where('aircraft_owner_id', $operator_id)
                                            ->with(['get_aircraft_type'])
                                            ->with(['get_image'])
                                            ->with(['get_reviews'=>function($q){
                                                        $q->where('review_to' , 'AIRCRAFT' );
                                                    }
                                                ])
                                            ->orderBy('created_at','DESC');

        if($search!='' && isset($search))
        {

            $obj_aircraft= $obj_aircraft->whereHas('get_aircraft_type',function($q)use($search){
                                             $q->where('model_name', 'LIKE',"%".$search."%");
                                        });
        }

        $obj_aircraft = $obj_aircraft->paginate(9);

        if($obj_aircraft)
        {
            $arr_aircraft = $obj_aircraft->toArray(); 
            $page_link    = $obj_aircraft->links(); 
        }

        $this->arr_view_data['page_link']           = $page_link;
        $this->arr_view_data['arr_aircraft']        = $arr_aircraft;
        $this->arr_view_data['arr_aircraft_type']   = $arr_aircraft_type;
        $this->arr_view_data['module_title']        = " My Aircrafts";
        $this->arr_view_data['page_title']          = 'Aircraft Listing';
        $this->arr_view_data['aircraft_images_base_img_path']   = $this->aircraft_images_base_img_path;
        $this->arr_view_data['aircraft_images_public_img_path'] = $this->aircraft_images_public_img_path;

        return view($this->module_view_folder.'index',$this->arr_view_data);
    }

    public function add(Request $request)
    {
        //dd($this->tmp_aircraft_images_base_img_path, $this->aircraft_images_public_img_path);
        $arr_aircraft = $aircraft_models = $arr_countries = [];
        $obj_aircraft = $this->TempAircraftModel->where('unique_id',Session::getId())
                                                ->where('form_type','add')
                                                ->with(['get_amenities'])
                                                ->first();

        if($obj_aircraft){
            $arr_aircraft = $obj_aircraft->toArray();             
        }

        if( isset($obj_aircraft->type_name) && $obj_aircraft->type_name != '' ){
            $obj_models = $this->AircraftTypeModel->where('type', $obj_aircraft->type_name)->get();
        }else{
            $obj_models = $this->AircraftTypeModel->select('*')->get();
        }

        if($obj_models->count() > 0){
            $aircraft_models = $obj_models->toArray();
        }

        $obj_countries = $this->CountryModel->get();

        if($obj_countries->count() > 0){
            $arr_countries = $obj_countries->toArray();
        }

        $this->arr_view_data['module_title']            = " Add Aircraft";
        $this->arr_view_data['tmp_img_public_path']     = $this->tmp_aircraft_images_public_img_path;
        $this->arr_view_data['tmp_img_base_path']       = $this->tmp_aircraft_images_base_img_path;
        $this->arr_view_data['arr_aircraft']            = $arr_aircraft;
        $this->arr_view_data['aircraft_models']         = $aircraft_models;
        $this->arr_view_data['arr_countries']           = $arr_countries;
        $this->arr_view_data['page_title']              = 'Home';
    	return view($this->module_view_folder.'add_aircraft',$this->arr_view_data);
    }

    public function store(Request $request)
    {
        $operator = $this->operator_auth->check();
        $arr_rules = [];
        // dd($request->all());    
        if($request->page == "first_page")
        {
            $arr_rules['aircraft_model']    = 'required';
            $arr_rules['aircraft_type']     = 'required';
            
            /*$arr_rules['image']             = 'required|array|min:1|mimes:png,jpg,jpeg';*/
        }
        elseif($request->page == "second_page")
        {
            $arr_rules['oper_capability']   = 'required';
            $arr_rules['price']             = 'required';
            $arr_rules['quantity']          = 'required'; 
            $arr_rules['cost_less_50']      = 'required';
            $arr_rules['cost_greater_50']   = 'required';
        }
        elseif($request->page == "third_page")
        {
            $arr_rules['min_gaurantee_hr'] = 'required';
            $arr_rules['default_location'] = 'required';
            $arr_rules['registration_no']  = 'required';              
        }
        elseif($request->page == "fourth_page")
        {
           /* $arr_rules['engine']    = 'required';
            $arr_rules['props']     = 'required';
            $arr_rules['avionics']  = 'required';
            $arr_rules['equipment'] = 'required';           */
        }

        $arr_data = $response = $arr_aircraft = $arr_amenities = $arr_equipment = $arr_equipment_data = $aircraft_data = $arr_images = [];

        $is_exist = $aircrat_id = '';

        $operator_id = $this->operator_auth->user()->id;

        if($request->page == "first_page")
        {
            $validator = Validator::make($request->all(),$arr_rules);

            if($validator->fails()) 
            {
                return json_encode(['errors'=> $validator->errors()->getMessages(),'code'=>422,'status'=>'fail']);
            }


            $arr_data['form_type']         = 'add';
            $arr_data['description']       = $request->input('description', null);
            $arr_data['aircraft_owner_id'] = $operator_id;
            $arr_data['model_name']        = $request->input('aircraft_model', null);
            $arr_data['type_name']         = $request->input('aircraft_type');

            if($request->has('file'))
            {
                $form_data = $request->all();

                foreach($request->input('file') as $index => $input_img)
                {
                    $encoded_image  = base64_decode($input_img);
                    $file_extension = explode('/',explode(';', $input_img)[0])[1];
                    $filename       = sha1(uniqid().uniqid()) . '.' . $file_extension;

                    if(in_array($file_extension,['png','jpg','jpeg']))
                    {
                        $file_name      = time().uniqid().'.'.$file_extension;
                        $image_file     = $input_img;

                        $destination_path1  = 'uploads/aircraft_owner/temp_aircraft_image/thumb_255x170/';
                        $destination_path2  = 'uploads/aircraft_owner/temp_aircraft_image/thumb_350x255/';
                        $destination_path3  = 'uploads/aircraft_owner/temp_aircraft_image/thumb_690x345/';
                        $destination_path4  = 'uploads/aircraft_owner/temp_aircraft_image/thumb_320x205/';
                        $destination_path5  = 'uploads/aircraft_owner/temp_aircraft_image/thumb_225x145/';

                        $image1 = Image::make($image_file);
                        $image1->resize(255,170);
                        $image1->save($destination_path1.$file_name);

                        $image2 = Image::make($image_file);
                        $image2->resize(350,255);
                        $image2->save($destination_path2.$file_name);

                        $image3 = Image::make($image_file);
                        $image3->resize(690,345);
                        $image3->save($destination_path3.$file_name);

                        $image4 = Image::make($image_file);
                        $image4->resize(320,205);
                        $image4->save($destination_path4.$file_name);

                        $image5 = Image::make($image_file);
                        $image5->resize(225,205);
                        $image5->save($destination_path5.$file_name);

                        $image1 = Image::make($image_file);
                        $isUpload = $image1->save($this->tmp_aircraft_images_base_img_path.$file_name);

                        $arr_images[] = $file_name;
                    }
                    else
                    {
                        $status = 'fail';
                        $customMsg = 'Invalid File Type.';
                        $resp = array('status' => $status,'customMsg'=> $customMsg);
                        return response()->json($resp);
                    }
                }
            }
            else
            {
                $status = 'fail';
                $customMsg = 'Please select atleast one image.';
                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);
            }

            if(!empty($arr_images)){
                $arr_data['images'] = serialize($arr_images);
            }

            if($this->TempAircraftModel->where('unique_id',Session::getId())->first())
            {
                $status = $this->TempAircraftModel->where('unique_id', Session::getId())->update($arr_data);

                $status = 'success';
                $customMsg = 'Record updated successfully';

                $resp = array('status' => $status,'customMsg'=> $customMsg);
            }
            else
            {
                $arr_data['unique_id'] = Session::getId();
                $status = $this->TempAircraftModel->create($arr_data);

                $status = 'success';
                $customMsg = 'Record created successfully.';

                $resp = array('status' => $status,'customMsg'=> $customMsg);   
            }

            return response()->json($resp);
        }
        elseif($request->page == "second_page")
        {
            $other_charges      =  $request->input('other_charges');
            $arr_data['quantity']               = $request->input('quantity', null);
            $arr_data['price_per_hour']         = (float) $request->input('price');
            $arr_data['less_mgh_cost']          = $request->input('cost_less_50', null);
            $arr_data['more_mgh_cost']          = $request->input('cost_greater_50', null);
            $arr_data['other_charges']          = isset($other_charges) ? $other_charges :'0';
            $arr_data['operational_capability']     = json_encode($request->input('oper_capability'));

            $status = $this->TempAircraftModel->where('unique_id', Session::getId() )->update($arr_data);

            if($status)
            {
                $status = 'success';
                $customMsg = 'record Updated Successfully ';

                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);
            }
            else
            {
                $status = 'fail';
                $customMsg = 'error while updating';
                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);
            }
        }

        elseif($request->page == "third_page")
        {
            $id = $request->session()->get('aircraft_id');

            $arr_data['min_gaurantee_hours']    = $request->input('min_gaurantee_hr');
            $arr_data['registration_no']        = $request->input('registration_no');
            $arr_data['default_location']       = $request->input('default_location');
            $arr_data['positioning']            = $request->input('positioning', null);
            $arr_data['base_of_operation']      = $request->input('base_of_operation', null);
            $arr_data['lat']                    = $request->input('lat', null);
            $arr_data['lng']                    = $request->input('lng', null);

            $status = $this->TempAircraftModel->where('unique_id',Session::getId())->update($arr_data);

            if( $status )
            {
                $status = 'success';
                $customMsg = 'record Updated Successfully ';

                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);

            }else {
                $status = 'fail';
                $customMsg = 'error while updating';
                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);
            }
        }

        elseif($request->page == "fourth_page")
        {
            $data = $arr_amenity_data =[];

            $operator_id = $this->operator_auth->user()->id;

            $id = $request->session()->get('aircraft_id');
            $amenities = $request->input('avionics');
            $equipments = $request->input('equipment');

            $arr_data['engine']           = $request->input('engine');
            $arr_data['props']            = $request->input('props');
            $arr_amenities['name']        = $amenities_name = isset($amenities) ? $amenities :'';
            $arr_amenities['aircraft_id'] = $id;
            $arr_equipment['name']        = $equipment_name = isset($equipments) ? $equipments :'';
            $arr_equipment['aircraft_id'] = $id;

            $status = $this->TempAircraftModel->where('unique_id',Session::getId())->update($arr_data);

            if( $status )
            {   
                $obj_temp_aircraft = $this->TempAircraftModel->where('unique_id',Session::getId())->first();

                if($obj_temp_aircraft)
                {
                    $arr_temp_aircraft = $obj_temp_aircraft->toArray();

                    $aircraft_data['aircraft_owner_id']      = $operator_id;
                    $aircraft_data['name']                   = isset($arr_temp_aircraft['name'])? $arr_temp_aircraft['name']:'';
                    
                    $aircraft_data['description']            = isset($arr_temp_aircraft['description'])? $arr_temp_aircraft['description']:'';
                    
                    $aircraft_data['type_name']              = isset($arr_temp_aircraft['type_name'])? $arr_temp_aircraft['type_name']:'';
                    
                    $aircraft_data['operational_capability'] = isset($arr_temp_aircraft['operational_capability'])? $arr_temp_aircraft['operational_capability']:'';
                    
                    $aircraft_data['price_per_hour']         = isset($arr_temp_aircraft['price_per_hour'])? $arr_temp_aircraft['price_per_hour']:'';
                    
                    //$aircraft_data['capacity']               = isset($arr_temp_aircraft['capacity'])? $arr_temp_aircraft['capacity']:'';
                    
                    $aircraft_data['model_id']               = isset($arr_temp_aircraft['model_name'])? $arr_temp_aircraft['model_name']:'';
                    
                    $aircraft_data['quantity']               = isset($arr_temp_aircraft['quantity'])? $arr_temp_aircraft['quantity']:'';

                    $aircraft_data['base_of_operation']      = isset($arr_temp_aircraft['base_of_operation'])? $arr_temp_aircraft['base_of_operation']:'';
                    
                    $aircraft_data['min_gaurantee_hours']    = isset($arr_temp_aircraft['min_gaurantee_hours'])? $arr_temp_aircraft['min_gaurantee_hours']:'';

                    $aircraft_data['less_mgh_cost']          = isset($arr_temp_aircraft['less_mgh_cost'])? $arr_temp_aircraft['less_mgh_cost']:'';

                    $aircraft_data['more_mgh_cost']          = isset($arr_temp_aircraft['more_mgh_cost'])? $arr_temp_aircraft['more_mgh_cost']:'';
                    
                    $aircraft_data['other_charges']          = isset($arr_temp_aircraft['other_charges'])? $arr_temp_aircraft['other_charges']:'';
                    
                    $aircraft_data['default_location']       = isset($arr_temp_aircraft['default_location'])? $arr_temp_aircraft['default_location']:'';
                    
                    $aircraft_data['registration_no']        = isset($arr_temp_aircraft['registration_no'])? $arr_temp_aircraft['registration_no']:'';
                    
                    $aircraft_data['positioning']            = isset($arr_temp_aircraft['positioning'])? $arr_temp_aircraft['positioning']:'';
                    
                    $aircraft_data['performance']            = isset($arr_temp_aircraft['performance'])? $arr_temp_aircraft['performance']:'';
                    
                    $aircraft_data['engine_capability']      = isset($arr_temp_aircraft['engine_capability'])? $arr_temp_aircraft['engine_capability']:'';

                    $aircraft_data['engine']                 = isset($arr_temp_aircraft['engine'])? $arr_temp_aircraft['engine']:'';
                    
                    $aircraft_data['props']                  = isset($arr_temp_aircraft['props'])? $arr_temp_aircraft['props']:'';

                    $aircraft_data['type_name']              = isset($arr_temp_aircraft['type_name'])? $arr_temp_aircraft['type_name']:'';


                    

                    $update_aircraft_status = $this->AircraftModel->create($aircraft_data);

                    $last_aircraft_details = $this->AircraftModel->where('id', $update_aircraft_status->id)->first();

                    /*
                    |
                    |Send notification
                    |
                    */
                    $userDetails = login_operator_details();
                    $arr_userDetails = $userDetails->toArray();
                    $first_name = isset($arr_userDetails['first_name'])?$arr_userDetails['first_name']:'';
                    $last_name = isset($arr_userDetails['last_name'])?$arr_userDetails['last_name']:'';
                    
                    $username = $first_name.' '.$last_name;
                    
                    $ARR_NOTIFICATIOn = [];
                    $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
                    $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
                    $ARR_NOTIFICATION_DATA['sender_id']              = $operator_id;
                    $ARR_NOTIFICATION_DATA['sender_type']            = 'aircraft_owner';
                    $ARR_NOTIFICATION_DATA['title']                  = 'Aircraft Added';
                    $ARR_NOTIFICATION_DATA['description']            = 'New aircraft added by the '.$username;
                    $ARR_NOTIFICATION_DATA['redirect_url']           = '/admin/aircraft/view/'.base64_encode($update_aircraft_status->id);
                    $ARR_NOTIFICATION_DATA['status']                 = 0;
                    $ARR_NOTIFICATION_DATA['notification_type']      = 'general';
                    
                    $this->save_notification($ARR_NOTIFICATION_DATA);
                    if($update_aircraft_status)
                    {
                        $ARR_NOTIFICATIOn = [];
                        $ARR_NOTIFICATION_DATA['receiver_id']            = $operator_id;
                        $ARR_NOTIFICATION_DATA['receiver_type']          = 'aircraft_owner';
                        $ARR_NOTIFICATION_DATA['sender_id']              = config('app.project.admin_id');
                        $ARR_NOTIFICATION_DATA['sender_type']            = 'admin';
                        $ARR_NOTIFICATION_DATA['title']                  = 'Aircraft Added';
                        $ARR_NOTIFICATION_DATA['description']            = 'Hello '.$username.' your new Aircarft has been added successfully. ';
                        $ARR_NOTIFICATION_DATA['redirect_url']           = '';
                        $ARR_NOTIFICATION_DATA['status']                 = 0;
                        $ARR_NOTIFICATION_DATA['notification_type']      = 'general';

                        $this->save_notification($ARR_NOTIFICATION_DATA);
                    }

                    if($arr_temp_aircraft['images'] != '')
                    {
                        $arr_tmp_imgs = unserialize($arr_temp_aircraft['images']);
                        foreach($arr_tmp_imgs as $row){
                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.$row, $this->aircraft_images_base_img_path.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.$row);
                            }

                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.'thumb_225x145/'.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.'thumb_225x145/'.$row, $this->aircraft_images_base_img_path.'thumb_225x145/'.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.'thumb_225x145/'.$row);
                            }

                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.'thumb_255x170/'.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.'thumb_255x170/'.$row, $this->aircraft_images_base_img_path.'thumb_255x170/'.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.'thumb_255x170/'.$row);
                            }

                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.'thumb_320x205/'.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.'thumb_320x205/'.$row, $this->aircraft_images_base_img_path.'thumb_320x205/'.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.'thumb_320x205/'.$row);
                            }

                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.'thumb_350x255/'.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.'thumb_350x255/'.$row, $this->aircraft_images_base_img_path.'thumb_350x255/'.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.'thumb_350x255/'.$row);
                            }
                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.'thumb_690x345/'.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.'thumb_690x345/'.$row, $this->aircraft_images_base_img_path.'thumb_690x345/'.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.'thumb_690x345/'.$row);
                            }
                            $this->AircraftImagesModel->create(['aircraft_id'=> $last_aircraft_details->id, 'images'=>$row]);
                        }
                    }

                    $arr_amenity   = explode(',',$amenities_name);

                    $arr_equipment = explode(',',$equipment_name);
                  
                    foreach ($arr_amenity as $amenity ) 
                    {
                        $data['name']        = $amenity;
                        $data['aircraft_id'] = $last_aircraft_details->id;
                        $amenities_status    = $this->AmenitiesModel->create($data);
                    }

                    foreach ($arr_equipment as $equipment ) 
                    {
                        $equipment_data['name']        = $equipment;
                        $equipment_data['aircraft_id'] = $last_aircraft_details->id;
                        $equipment_status = $this->EquipmentModel->create($equipment_data);
                    }

                    if( $update_aircraft_status )
                    {
                        $this->TempAircraftModel->where('unique_id',Session::getId())->delete();
                        $status = 'success';
                        $customMsg = 'record Updated Successfully ';
                        $resp = array('status' => $status,'customMsg'=> $customMsg);
                        return response()->json($resp);
                    } else {
                        $status = 'fail';
                        $customMsg = 'error while updating aircraft details';
                        $resp = array('status' => $status,'customMsg'=> $customMsg);
                        return response()->json($resp);     
                    }
                }
                else
                {
                    $status = 'fail';
                    $customMsg = 'error while updating';
                    $resp = array('status' => $status,'customMsg'=> $customMsg);
                    return response()->json($resp);
                }

            }else {
                $status = 'fail';
                $customMsg = 'error while updating';
                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);
            }

        }
    }

    public function edit($enc_id)
    {
        $id = base64_decode($enc_id);

        if(!is_numeric($id))
        {
            Session::flash('error','Oops, Something went wrong!');
            return redirect()->back();
        }

        $obj_aircraft = $this->AircraftModel->where('id', $id)
                                            ->with([
                                                    'get_aircraft_type',
                                                    'get_equipments',
                                                    'get_amenities',
                                                    'get_all_images',
                                                ])
                                            ->first();

        if($obj_aircraft){
            $arr_aircraft = $obj_aircraft->toArray();
        }

        if( isset($obj_aircraft->type_name) && $obj_aircraft->type_name != '' ){
            $obj_models = $this->AircraftTypeModel->where('type', $obj_aircraft->type_name)->get();
        }else{
            $obj_models = $this->AircraftTypeModel->select('*')->get();
        }

        if($obj_models->count() > 0){
            $aircraft_models = $obj_models->toArray();
        }

        $obj_countries = $this->CountryModel->get();

        if($obj_countries->count() > 0){
            $arr_countries = $obj_countries->toArray();
        }

        $this->arr_view_data['img_public_path']     = $this->aircraft_images_public_img_path;
        $this->arr_view_data['img_base_path']       = $this->aircraft_images_base_img_path;
        $this->arr_view_data['enc_id']              = base64_encode($arr_aircraft['id']);
        $this->arr_view_data['arr_aircraft']        = $arr_aircraft;
        $this->arr_view_data['aircraft_models']     = $aircraft_models;
        $this->arr_view_data['sub_module_title']    = " Edit Aircraft";
        $this->arr_view_data['module_title']        = " My Aircraft";
        $this->arr_view_data['module_url_path']     =  url('/').'/operator/aircrafts';
        $this->arr_view_data['arr_countries']       = $arr_countries;
        $this->arr_view_data['page_title']          = 'Home';

        return view($this->module_view_folder.'edit_aircraft',$this->arr_view_data);
    }

    public function update($enc_id, Request $request)
    {
        $air_id = base64_decode($enc_id);
        
        if(!is_numeric($air_id)){
            $resp = array('status' => 'fail','customMsg'=> 'Something went wrong!');
            return response()->json($resp);
        }

        $operator = $this->operator_auth->check();
        $arr_rules = [];

        if($request->page == "first_page")
        {
            $arr_rules['aircraft_model']    = 'required';
            $arr_rules['aircraft_type']     = 'required';
            $arr_rules['aircraft_model']    = 'required';
        }
        elseif($request->page == "second_page")
        {
            $arr_rules['aircraft_type']     = 'required';
            $arr_rules['oper_capability']   = 'required';
            $arr_rules['price']             = 'required';
        }
        elseif($request->page == "third_page")
        {
            $arr_rules['quantity']         = 'required';
            $arr_rules['cost_less_50']     = 'required';
            $arr_rules['cost_greater_50']  = 'required';
            $arr_rules['other_charges']    = 'required';
            $arr_rules['min_gaurantee_hr'] = 'required';
            $arr_rules['default_location'] = 'required';
            $arr_rules['registration_no']  = 'required';              
        }
        elseif($request->page == "fourth_page")
        {
            /*$arr_rules['engine']    = 'required';
            $arr_rules['props']     = 'required';
            $arr_rules['avionics']  = 'required';
            $arr_rules['equipment'] = 'required';       */    
        }

        $arr_data = $response = $arr_aircraft = $arr_amenities = $arr_equipment = $arr_equipment_data = $aircraft_data = $arr_images = [];

        $is_exist = $aircrat_id = '';

        $operator_id = $this->operator_auth->user()->id;

        if($request->page == "first_page")
        {
            $validator = Validator::make($request->all(),$arr_rules);

            if($validator->fails()) 
            {
                return json_encode(['errors'=> $validator->errors()->getMessages(),'code'=>422,'status'=>'fail']);
            }

            $arr_data['description']       = $request->input('description', null);
            $arr_data['model_name']        = $request->input('aircraft_model', null);
            $arr_data['type_name']         = $request->input('aircraft_type');
            $arr_data['form_type']         = 'update';    
            $arr_data['aircraft_owner_id'] = $operator_id;

            if($request->has('file'))
            {
                $form_data = $request->all();

                foreach($request->input('file') as $index => $input_img)
                {
                    $encoded_image  = base64_decode($input_img);
                    $file_extension = explode('/',explode(';', $input_img)[0])[1];
                    $filename       = sha1(uniqid().uniqid()) . '.' . $file_extension;

                    if(in_array($file_extension,['png','jpg','jpeg']))
                    {
                        $file_name      = time().uniqid().'.'.$file_extension;
                        $image_file     = $input_img;

                        $destination_path1  = 'uploads/aircraft_owner/temp_aircraft_image/thumb_255x170/';
                        $destination_path2  = 'uploads/aircraft_owner/temp_aircraft_image/thumb_350x255/';
                        $destination_path3  = 'uploads/aircraft_owner/temp_aircraft_image/thumb_690x345/';
                        $destination_path4  = 'uploads/aircraft_owner/temp_aircraft_image/thumb_320x205/';
                        $destination_path5  = 'uploads/aircraft_owner/temp_aircraft_image/thumb_225x145/';

                        $image1 = Image::make($image_file);
                        $image1->resize(255,170);
                        $image1->save($destination_path1.$file_name);

                        $image2 = Image::make($image_file);
                        $image2->resize(350,255);
                        $image2->save($destination_path2.$file_name);

                        $image3 = Image::make($image_file);
                        $image3->resize(690,345);
                        $image3->save($destination_path3.$file_name);

                        $image4 = Image::make($image_file);
                        $image4->resize(320,205);
                        $image4->save($destination_path4.$file_name);

                        $image5 = Image::make($image_file);
                        $image5->resize(225,205);
                        $image5->save($destination_path5.$file_name);

                        $image1 = Image::make($image_file);
                        $isUpload = $image1->save($this->tmp_aircraft_images_base_img_path.$file_name);

                        $arr_images[] = $file_name;
                    }
                    else
                    {
                        $status = 'fail';
                        $customMsg = 'Invalid File Type.';
                        $resp = array('status' => $status,'customMsg'=> $customMsg);
                        return response()->json($resp);
                    }
                }
            }
            else
            {
                $status = 'fail';
                $customMsg = 'Please select atleast one image.';
                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);
            }

            if(!empty($arr_images)){
                $arr_data['images'] = serialize($arr_images);
            }

            if($this->TempAircraftModel->where('unique_id',Session::getId())->first())
            {
                $status = $this->TempAircraftModel->where('unique_id', Session::getId())->update($arr_data);

                $status = 'success';
                $customMsg = 'Record updated successfully';

                $resp = array('status' => $status,'customMsg'=> $customMsg);
            }
            else
            {
                $arr_data['unique_id'] = Session::getId();
                $status = $this->TempAircraftModel->create($arr_data);

                $status = 'success';
                $customMsg = 'Record created successfully.';

                $resp = array('status' => $status,'customMsg'=> $customMsg);   
            }

            return response()->json($resp);
        }
        elseif($request->page == "second_page")
        {
            
            
            $other_charges                          = $request->input('other_charges');
            $arr_data['quantity']                   = $request->input('quantity', null);
            $arr_data['operational_capability']     = json_encode($request->input('oper_capability'));
            $arr_data['other_charges']              = isset($other_charges) ? $other_charges :'0';
            $arr_data['price_per_hour']             = (float) $request->input('price');
            $arr_data['less_mgh_cost']              = $request->input('cost_less_50', null);
            $arr_data['more_mgh_cost']              = $request->input('cost_greater_50', null);
            //$arr_data['capacity']                   = $request->input('capacity');
           
            $status = $this->TempAircraftModel->where('unique_id', Session::getId() )->update($arr_data);

            if($status)
            {
                $status = 'success';
                $customMsg = 'record Updated Successfully ';

                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);
            }
            else
            {
                $status = 'fail';
                $customMsg = 'error while updating';
                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);
            }
        }

        elseif($request->page == "third_page")
        {
            $id = $request->session()->get('aircraft_id');

            $arr_data['min_gaurantee_hours']    = $request->input('min_gaurantee_hr');
            $arr_data['registration_no']        = $request->input('registration_no');
            $arr_data['positioning']            = $request->input('positioning', null);
            $arr_data['base_of_operation']      = $request->input('base_of_operation', null);
            $arr_data['lat']                    = $request->input('lat', null);
            $arr_data['lng']                    = $request->input('lng', null);

            $status = $this->TempAircraftModel->where('unique_id',Session::getId())->update($arr_data);

            if( $status )
            {
                $status = 'success';
                $customMsg = 'record Updated Successfully ';

                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);

            }else {
                $status = 'fail';
                $customMsg = 'error while updating';
                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);
            }
        }

        elseif($request->page == "fourth_page")
        {
            $data = $arr_amenity_data = [];

            $operator_id = $this->operator_auth->user()->id;

            $id = $request->session()->get('aircraft_id');

            $arr_data['engine']           = $request->input('engine');
            $arr_data['props']            = $request->input('props');
            $arr_amenities['name']        = $amenities_name = $request->input('avionics');
            $arr_amenities['aircraft_id'] = $air_id;
            $arr_equipment['name']        = $equipment_name = $request->input('equipment');
            $arr_equipment['aircraft_id'] = $air_id;

            $status = $this->TempAircraftModel->where('unique_id',Session::getId())->update($arr_data);

            if( $status )
            {   
                $obj_temp_aircraft = $this->TempAircraftModel->where('unique_id',Session::getId())->first();

                if($obj_temp_aircraft)
                {
                    $arr_temp_aircraft = $obj_temp_aircraft->toArray();

                    $aircraft_data['aircraft_owner_id']      = $operator_id;

                    $aircraft_data['name']                   = isset($arr_temp_aircraft['name'])? $arr_temp_aircraft['name']:'';

                    $aircraft_data['description']            = isset($arr_temp_aircraft['description'])? $arr_temp_aircraft['description']:'';

                    $aircraft_data['type_name']              = isset($arr_temp_aircraft['type_name'])? $arr_temp_aircraft['type_name']:'';

                    $aircraft_data['operational_capability'] = isset($arr_temp_aircraft['operational_capability'])? $arr_temp_aircraft['operational_capability']:'';

                    $aircraft_data['price_per_hour']         = isset($arr_temp_aircraft['price_per_hour'])? $arr_temp_aircraft['price_per_hour']:'';

                    //$aircraft_data['capacity']               = isset($arr_temp_aircraft['capacity'])? $arr_temp_aircraft['capacity']:'';

                    $aircraft_data['model_id']               = isset($arr_temp_aircraft['model_name'])? $arr_temp_aircraft['model_name']:'';

                    $aircraft_data['quantity']               = isset($arr_temp_aircraft['quantity'])? $arr_temp_aircraft['quantity']:'';

                    $aircraft_data['base_of_operation']      = isset($arr_temp_aircraft['base_of_operation'])? $arr_temp_aircraft['base_of_operation']:'';

                    $aircraft_data['min_gaurantee_hours']    = isset($arr_temp_aircraft['min_gaurantee_hours'])? $arr_temp_aircraft['min_gaurantee_hours']:'';

                    $aircraft_data['less_mgh_cost']          = isset($arr_temp_aircraft['less_mgh_cost'])? $arr_temp_aircraft['less_mgh_cost']:'';

                    $aircraft_data['more_mgh_cost']          = isset($arr_temp_aircraft['more_mgh_cost'])? $arr_temp_aircraft['more_mgh_cost']:'';

                    $aircraft_data['other_charges']          = isset($arr_temp_aircraft['other_charges'])? $arr_temp_aircraft['other_charges']:'';

                    $aircraft_data['default_location']       = isset($arr_temp_aircraft['default_location'])? $arr_temp_aircraft['default_location']:'';

                    $aircraft_data['lat']                    = isset($arr_temp_aircraft['lat'])? $arr_temp_aircraft['lat']:'';

                    $aircraft_data['lng']                    = isset($arr_temp_aircraft['lng'])? $arr_temp_aircraft['lng']:'';

                    $aircraft_data['registration_no']        = isset($arr_temp_aircraft['registration_no'])? $arr_temp_aircraft['registration_no']:'';

                    $aircraft_data['positioning']            = isset($arr_temp_aircraft['positioning'])? $arr_temp_aircraft['positioning']:'';

                    $aircraft_data['performance']            = isset($arr_temp_aircraft['performance'])? $arr_temp_aircraft['performance']:'';

                    $aircraft_data['engine_capability']      = isset($arr_temp_aircraft['engine_capability'])? $arr_temp_aircraft['engine_capability']:'';

                    $aircraft_data['engine']                 = isset($arr_temp_aircraft['engine'])? $arr_temp_aircraft['engine']:'';

                    $aircraft_data['props']                  = isset($arr_temp_aircraft['props'])? $arr_temp_aircraft['props']:'';

                    $update_aircraft_status = $this->AircraftModel->where('id',$air_id)->update($aircraft_data);

                    $last_aircraft_details = $this->AircraftModel->where('id', $air_id)->first();

                    /*
                    |
                    |Send notification
                    |
                    */
                    $userDetails = login_operator_details();
                    $arr_userDetails = $userDetails->toArray();
                    $first_name = isset($arr_userDetails['first_name'])?$arr_userDetails['first_name']:'';
                    $last_name = isset($arr_userDetails['last_name'])?$arr_userDetails['last_name']:'';
                    
                    $username = $first_name.' '.$last_name;
                   
                    $ARR_NOTIFICATIOn = [];
                    $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
                    $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
                    $ARR_NOTIFICATION_DATA['sender_id']              = $operator_id;
                    $ARR_NOTIFICATION_DATA['sender_type']            = 'aircraft_owner';
                    $ARR_NOTIFICATION_DATA['title']                  = 'Aircraft Updated';
                    $ARR_NOTIFICATION_DATA['description']            = 'Aircraft updates by the '.$username;
                    $ARR_NOTIFICATION_DATA['redirect_url']           = '/admin/aircraft/view/'.base64_encode($air_id);
                    $ARR_NOTIFICATION_DATA['status']                 = 0;
                    $ARR_NOTIFICATION_DATA['notification_type']      = 'general';
                    $this->save_notification($ARR_NOTIFICATION_DATA);

                    if($update_aircraft_status)
                    {
                        $ARR_NOTIFICATIOn = [];
                        $ARR_NOTIFICATION_DATA['receiver_id']            = $operator_id;
                        $ARR_NOTIFICATION_DATA['receiver_type']          = 'aircraft_owner';
                        $ARR_NOTIFICATION_DATA['sender_id']              = config('app.project.admin_id');
                        $ARR_NOTIFICATION_DATA['sender_type']            = 'admin';
                        $ARR_NOTIFICATION_DATA['title']                  = 'Aircraft updated';
                        $ARR_NOTIFICATION_DATA['description']            = 'Hello '.$username.' your Aircarft : '.$aircraft_data['name'].' has been updated successfully. ';
                        $ARR_NOTIFICATION_DATA['redirect_url']           = '';
                        $ARR_NOTIFICATION_DATA['status']                 = 0;
                        $ARR_NOTIFICATION_DATA['notification_type']      = 'general';
                        $this->save_notification($ARR_NOTIFICATION_DATA);
                    }

                    if($arr_temp_aircraft['images'] != '')
                    {
                        $this->AircraftImagesModel->where('aircraft_id', $air_id)->delete();
                        $arr_tmp_imgs = unserialize($arr_temp_aircraft['images']);
                        foreach($arr_tmp_imgs as $row){
                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.$row, $this->aircraft_images_base_img_path.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.$row);
                            }

                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.'thumb_225x145/'.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.'thumb_225x145/'.$row, $this->aircraft_images_base_img_path.'thumb_225x145/'.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.'thumb_225x145/'.$row);
                            }

                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.'thumb_255x170/'.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.'thumb_255x170/'.$row, $this->aircraft_images_base_img_path.'thumb_255x170/'.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.'thumb_255x170/'.$row);
                            }

                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.'thumb_320x205/'.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.'thumb_320x205/'.$row, $this->aircraft_images_base_img_path.'thumb_320x205/'.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.'thumb_320x205/'.$row);
                            }

                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.'thumb_350x255/'.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.'thumb_350x255/'.$row, $this->aircraft_images_base_img_path.'thumb_350x255/'.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.'thumb_350x255/'.$row);
                            }
                            if($row != '' && file_exists($this->tmp_aircraft_images_base_img_path.'thumb_690x345/'.$row))
                            {
                                $move = File::move($this->tmp_aircraft_images_base_img_path.'thumb_690x345/'.$row, $this->aircraft_images_base_img_path.'thumb_690x345/'.$row);
                                File::delete($this->tmp_aircraft_images_base_img_path.'thumb_690x345/'.$row);
                            }

                            $this->AircraftImagesModel->create(['aircraft_id'=> $last_aircraft_details->id, 'images'=>$row]);
                        }
                    }

                    $arr_amenity   = explode(',',$amenities_name);
                    $arr_equipment = explode(',',$equipment_name);

                    $this->AmenitiesModel->where('aircraft_id', $air_id)->delete();

                    foreach ($arr_amenity as $amenity ) 
                    {
                        $data['name']        = $amenity;
                        $data['aircraft_id'] = $last_aircraft_details->id;
                        $amenities_status    = $this->AmenitiesModel->create($data);
                    }

                    $this->EquipmentModel->where('aircraft_id',$air_id)->delete();
                    
                    foreach ($arr_equipment as $equipment ) 
                    {
                        $equipment_data['name']        = $equipment;
                        $equipment_data['aircraft_id'] = $last_aircraft_details->id;
                        $equipment_status = $this->EquipmentModel->create($equipment_data);
                    }

                    if( $update_aircraft_status )
                    {
                        $this->TempAircraftModel->where('unique_id',Session::getId())->delete();
                        $status = 'success';
                        $customMsg = 'Record Updated Successfully ';
                        $resp = array('status' => $status,'customMsg'=> $customMsg);
                        return response()->json($resp);
                    } else {
                        $status = 'fail';
                        $customMsg = 'Error while updating aircraft details';
                        $resp = array('status' => $status,'customMsg'=> $customMsg);
                        return response()->json($resp);     
                    }
                }
                else
                {
                    $status = 'fail';
                    $customMsg = 'error while updating';
                    $resp = array('status' => $status,'customMsg'=> $customMsg);
                    return response()->json($resp);
                }

            }else {
                $status = 'fail';
                $customMsg = 'error while updating';
                $resp = array('status' => $status,'customMsg'=> $customMsg);
                return response()->json($resp);
            }

        }
    }

}