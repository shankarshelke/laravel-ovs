<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Models\ReviewsModel;
use App\Models\AircraftModel;
use App\Models\AmenitiesModel;
use App\Models\EquipmentModel;
use App\Models\AircraftTypeModel;
use App\Models\AvailabilityModel;
use App\Models\AircraftOwnerModel;
use App\Models\AircraftImagesModel;
use App\Models\ReservationModel;
use App\Models\QuotationModel;
use App\Models\UsersModel;
use App\Models\CountryModel;
use App\Common\Services\MailService;
use App\Models\SiteSettingModel;
use Illuminate\Pagination\Paginator;


use Auth;
use Validator;
use Session;
use DB;

class AircraftController extends Controller
{
    function __construct()
    {
        $this->arr_view_data        = [];
        $this->module_title         = "Home";
        $this->module_view_folder   = "front.";
        $this->common_url           = url('/');
        $this->ip_address           = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false; 
        $this->ReviewsModel         = new ReviewsModel();
        $this->SiteSettingModel     = new SiteSettingModel();
        $this->AircraftModel        = new AircraftModel();
        $this->AmenitiesModel       = new AmenitiesModel();
        $this->EquipmentModel       = new EquipmentModel();
        $this->MailService          = new MailService();
        $this->AircraftTypeModel    = new AircraftTypeModel();
        $this->AvailabilityModel    = new AvailabilityModel();
        $this->AircraftOwnerModel   = new AircraftOwnerModel();
        $this->AircraftImagesModel  = new AircraftImagesModel();
        $this->QuotationModel       = new QuotationModel();
        $this->ReservationModel     = new ReservationModel();
        $this->UsersModel           = new UsersModel();
        $this->CountryModel         = new CountryModel();

        $this->user_auth            = auth()->guard('users');
        $this->operator_auth        = auth()->guard('operator');
        $this->user_profile_base_img_path   = base_path().config('app.project.img_path.user_profile_image');
        $this->user_profile_public_img_path = url('/').config('app.project.img_path.user_profile_image');
        $this->user_license_base_img_path   = base_path().config('app.project.user_driving_license');
        $this->user_license_img_path        = url('/').config('app.project.user_driving_license');

        $this->aircraft_images_base_img_path   = base_path().config('app.project.img_path.aircraft_image');
        $this->aircraft_images_public_img_path = url('/').config('app.project.img_path.aircraft_image');
    }

    public function index(Request $request)
    {   
        $search = $type = $model = $aircraft_quantity = $country_name = '';
        $arr_aircraft = $arr_aircraft_type = $arr_data_reviews  = $arr_countries = [];
        $page_link ='';

        if($request->has('pickup_location') && $request->input('pickup_location') !='' )
        {
            $pickup_location = $request->input('pickup_location') ;
        }

        if($request->has('drop_location') && $request->input('drop_location') !='' )
        {
            $drop_location = $request->input('drop_location') ;
        }

        $obj_aircraft_type = $this->AircraftTypeModel->get();

        if($obj_aircraft_type)
        {
            $arr_aircraft_type = $obj_aircraft_type->toArray();
        }

        $obj_aircraft = $this->AircraftModel->whereHas('get_availablity', function($q){
                                                $q->whereDate('from_date','>', date('Y-m-d'));
                                            })
                                                
                                            ->with(['get_aircraft_type'])
                                            ->with(['get_reservation'])
                                            ->with(['get_availablity'])
                                            ->with(['get_image'])
                                            ->with(['get_reviews'=>function($q) {
                                                    $q->where('review_to' , 'AIRCRAFT' );
                                                }]
                                            );

        if(($request->has('location') && $request->input('location') !='') && ($request->has('lat') && $request->has('lng') && $request->lat != '' && $request->lng != '' ) )
        {
            $obj_aircraft = $obj_aircraft->selectRaw('*, ( 6367 * acos( cos( radians('.$request->lat.') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$request->lng.') ) + sin( radians('.$request->lat.') ) * sin( radians( lat ) ) ) ) AS distance')
                                         ->orderBy("distance", 'ASC');
                                        //->whereRaw("{$qry} < ?", ['1500000']);
        }else{
            $obj_aircraft = $obj_aircraft->orderBy('created_at','DESC');
        }

        if($request->has('key') && $request->input('key') !='' )
        {
            $key = $request->input('key');
            $obj_aircraft = $obj_aircraft->whereHas('get_aircraft_type',function($q) use($key){
                if(isset($key) && $key != '')
                {
                    $q->where('model_name', 'LIKE', '%'.$key.'%');
                }
            });
        }
        /*if($request->has('key') && $request->input('key') !='' )
        {
            $obj_aircraft = $obj_aircraft->where('name', 'LIKE', '%'.$request->input('key').'%');
        }*/

        if($request->has('aircraft_type') && $request->input('aircraft_type') !='' )
        {
            $obj_aircraft = $obj_aircraft->where('type_name', 'LIKE', '%'.$request->input('aircraft_type').'%');
        }

        if($request->has('model') && $request->input('model') !='' )
        {
           $obj_aircraft = $obj_aircraft->where('model_id', $request->input('model'));
        }

        if($request->has('op_capability') && $request->input('op_capability') !='' )
        {
            $obj_aircraft = $obj_aircraft->where('operational_capability', 'LIKE', '%'.$request->input('op_capability').'%');
        }

        if($request->has('base_operation') && $request->input('base_operation') !='' )
        {
            $obj_aircraft = $obj_aircraft->where('base_of_operation', 'LIKE', '%'.$request->input('base_operation').'%');
        }

        if($request->has('no_of_aircraft') && $request->input('no_of_aircraft') !='' && $request->input('no_of_aircraft') > 0)
        {
            $obj_aircraft = $obj_aircraft->where('quantity', '>=', $request->input('no_of_aircraft'));
        }

        if($request->has('pickup_date') && $request->input('pickup_date') !='' && $request->has('return_date') && $request->input('return_date') !='')
        {
            $pickup_date = $request->input('pickup_date');
            $return_date = $request->input('return_date');
            $obj_aircraft = $obj_aircraft->whereHas('get_availablity',function($q) use($pickup_date, $return_date){
                $q->where(function($q2) use($pickup_date, $return_date){
                    $q2->where('from_date', '<=', $pickup_date)->where('status',"AVAILABLE");
                    $q2->where('to_date', '>=', $return_date)->where('status',"AVAILABLE");
                });
            });
        }elseif($request->has('pickup_date') && $request->input('pickup_date') !='')
        {
            $pickup_date = $request->input('pickup_date');
            $obj_aircraft = $obj_aircraft->whereHas('get_availablity',function($q) use($pickup_date){
                if(isset($pickup_date) && $pickup_date != '')
                {
                    $q->where('from_date','<=', $pickup_date)->where('status',"AVAILABLE");
                    $q->where('to_date', '>=', $pickup_date)->where('status',"AVAILABLE");
                }
            });
        }elseif($request->has('return_date') && $request->input('return_date') !='')
        {
            $return_date = $request->input('return_date');
            $obj_aircraft = $obj_aircraft->whereHas('get_availablity',function($q) use($return_date){
                if(isset($return_date) && $return_date != '')
                {
                    $q->where( 'to_date','>=', $return_date )->where('status','AVAILABLE');
                    $q->where( 'from_date','<=', $return_date )->where('status',"AVAILABLE");
                }
            });
        }

        $obj_aircraft = $obj_aircraft->paginate(9)->appends(request()->input());

        if($obj_aircraft)
        {
            $arr_pagination     = clone $obj_aircraft;
            $arr_aircraft       = $obj_aircraft->toArray();
        }

        $obj_countries = $this->CountryModel->get();

        if($obj_countries->count() > 0)
        {
            $arr_countries = $obj_countries->toArray();
        }

        $this->sub_module_title                   = "Listing";
        $this->arr_view_data['country_name']      = $request->input('country_name','');
        $this->arr_view_data['page_link']         = $arr_pagination;
        $this->arr_view_data['arr_aircraft']      = $arr_aircraft;
        $this->arr_view_data['arr_aircraft_type'] = $arr_aircraft_type;
        $this->arr_view_data['page_title']        = 'Aircraft Listing';
        $this->arr_view_data['arr_countries']     = $arr_countries;
        
        $this->arr_view_data['module_home']        = "home";
        $this->arr_view_data['common_url']         = $this->common_url;
        $this->arr_view_data['sub_module_title']   = "Aircraft Listing";

        $this->arr_view_data['aircraft_images_base_img_path']   = $this->aircraft_images_base_img_path;
        $this->arr_view_data['aircraft_images_public_img_path'] = $this->aircraft_images_public_img_path;

        return view($this->module_view_folder.'aircraft_listing',$this->arr_view_data);
    }

    public function details($enc_id)
    {
        $arr_bookings = [];
        
        $user = $this->user_auth->user();
        $user_id = isset($user->id) ? $user->id : '';

        if($enc_id == '')
        {
            Session::flash('error','Oops, Something wents wrong!');
            return redirect()->back();
        }

        $id   = base64_decode($enc_id);
        $date = Date('Y-m-d');
        
        $obj_data = $this->AircraftModel
                          ->with(['get_reviews'=>function($q)
                                { $q->where('review_to' , 'AIRCRAFT' );}])
                          /*->whereHas('get_availablity1', function($q) use($date){
                                $q->WhereDate('from_date','>=', $date);
                          })*/
                          ->with(['get_availablity1'=>function($q)
                                { $q->where('is_available','YES');}])
                          ->where('id',$id)
                          ->first();

        if(!$obj_data)
        {
            Session::flash('error','Oops, Something wents wrong!');
            return redirect()->back();
        }

        if($obj_data)
        {
            $arr_data = $obj_data->toArray();
            $similar = $arr_data['type_name'];
            $aircraft_id = $arr_data['id'];
        }
        //dd($arr_data);
        $obj_data_amenities = $this->AmenitiesModel->where('aircraft_id',$id)->get();

        if($obj_data_amenities)
        {
            $arr_data_amenities = $obj_data_amenities->toArray();
        }

        $obj_data_equipments = $this->EquipmentModel->where('aircraft_id',$id)->get();

        if($obj_data_equipments)
        {
            $arr_data_equipments = $obj_data_equipments->toArray();
        }

        $obj_data_reviews = $this->ReviewsModel->with(['user','owner','aircraft'])->where('review_to','AIRCRAFT')->where('aircraft_id',$id)->where('status','1')->orderBy('created_at','DESC')->paginate(5);

        if($obj_data_reviews)
        {
            $arr_data_reviews = $obj_data_reviews->toArray();
        }


        $arr_aircraft = $arr_aircraft_type = [];
        $page_link ='';
        $obj_aircraft_type = $this->AircraftTypeModel->where('id', $arr_data['model_id'])->first();

        if($obj_aircraft_type)
        {
            $arr_aircraft_type = $obj_aircraft_type->toArray();
        }

        $obj_similar_aircraft =$this->AircraftModel->with(['get_reviews'=>function($q){ $q->where('review_to','AIRCRAFT');}])
                                                   ->with(['get_image','get_aircraft_type'])
                                                   ->where('type_name',$similar)
                                                   ->where('id','!=',$aircraft_id)
                                                   ->get();

        if($obj_similar_aircraft)
        {
            $arr_similar_aircraft = $obj_similar_aircraft->toArray();
        }   
        
        $obj_aircraft_images = $this->AircraftImagesModel->where('aircraft_id',$id)->get();

        if($obj_aircraft_images)
        {
            $arr_aircraft_images = $obj_aircraft_images->toArray();
        }

        if(!empty($arr_similar_aircraft))
        {
            $this->arr_view_data['arr_similar_aircraft'] = $arr_similar_aircraft;
        }
        $obj_quotation = $this->QuotationModel->where('aircraft_id',$id)
                                              ->where('user_id',$user_id)
                                              ->where('status','!=','REJECTED')
                                              ->get();
        if($obj_quotation)
        {
            $quotation_count = $obj_quotation->count();
        }

        $count_review = $this->ReservationModel->where('aircraft_id',$id)
                                                  ->where('user_id',$user_id)
                                                  ->where('status','COMPLETED')
                                                  ->count();

        $arr_res = $this->ReservationModel->where('aircraft_id',$id)
                                                  ->where('user_id',$user_id)
                                                  ->where('status','COMPLETED') 
                                                  ->first();
      /*  if($arr_res)
        {
            $arr_res = $get_reservation->toArray();
        }*/

        $obj_bookings = $this->ReservationModel->select('pickup_date', 'return_date')->where('aircraft_id',$id)->get();
        if($obj_bookings)
        {
            $arr_bookings = $obj_bookings->toArray();
        }

        $this->arr_view_data['quotation_count']     = $quotation_count;
        $this->arr_view_data['arr_aircraft_images'] = $arr_aircraft_images;
        $this->arr_view_data['count_review']        = $count_review;
        $this->arr_view_data['arr_res']             = isset($arr_res) ? $arr_res :'';
        $this->arr_view_data['arr_data']            = $arr_data;
        $this->arr_view_data['arr_data_amenities']  = $arr_data_amenities;
        $this->arr_view_data['arr_data_reviews']    = $arr_data_reviews;
        $this->arr_view_data['arr_data_equipments'] = $arr_data_equipments;
        $this->arr_view_data['page_link']           = $page_link;
        $this->arr_view_data['arr_aircraft']        = $arr_aircraft;
        $this->arr_view_data['arr_bookings']        = $arr_bookings;
        $this->arr_view_data['arr_aircraft_type']   = $arr_aircraft_type;
        $this->arr_view_data['page_title']          = 'Aircraft Details';

        $this->arr_view_data['module_home']         = "home";
        $this->arr_view_data['common_url']          = $this->common_url;
        $this->arr_view_data['module_url']          = url('/').'/listing';
        $this->arr_view_data['module_title']        = "Listing";
        $this->arr_view_data['sub_module_title']    = "Aircraft Details";

        $this->arr_view_data['user_profile_base_img_path']   = $this->user_profile_base_img_path;
        $this->arr_view_data['user_profile_public_img_path'] = $this->user_profile_public_img_path;

        $this->arr_view_data['aircraft_images_base_img_path']   = $this->aircraft_images_base_img_path;
        $this->arr_view_data['aircraft_images_public_img_path'] = $this->aircraft_images_public_img_path;

        return view($this->module_view_folder.'aircraft_details',$this->arr_view_data);
    }

    public function get_models_by_type($type)
    {
        $obj_models = $this->AircraftTypeModel->select('id','model_name')->where('type', $type)->get();

        if($obj_models->count() > 0)
        {
            $html = '';
            foreach($obj_models as $model){
                $html .= '<option value="'.$model->id.'"> '.$model->model_name.' </option>';
            }
            $resp = array('status' => 'success','html'=> $html,'customMsg'=> 'Records found.');
            return response()->json($resp);
        }else{
            $resp = array('status' => 'fail','customMsg'=> 'Records not found.');
            return response()->json($resp);
        }
    }

    public function review(Request $request, $enc_id)
    {
        $user = $this->user_auth->user();
        $user_id = $user->id;
        $user_ID = $user->user_id;

        $aircraft_id    = base64_decode($enc_id);
        $arr_rules      = $arr_credentials =  array();
        $status         = false;

        $arr_rules['rating']             = "required";
        $arr_rules['review_description'] = "required";

        $validator = Validator::make($request->all(),$arr_rules);
        
        if($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput();
        }   
        
        $rating   = $request->input('rating');
        $reviews  = $request->input('review_description');

        $is_reservation_completed = $this->ReservationModel->where('user_id',$user_id )->where('aircraft_id',$aircraft_id)->where('status','COMPLETED')->first();

        if($is_reservation_completed)
        {
            $obj_data  = $this->UsersModel->where('user_id',$user_ID)->first();
            if($obj_data) 
            {
                $arr_data = $obj_data->toArray();
                $id = (isset($arr_data['id']) ? $arr_data['id'] : '');
                $arr_data['review_from_id']   = $id;
                $arr_data['review_to_id']     = $aircraft_id;
                $arr_data['aircraft_id']      = $aircraft_id;
                $arr_data['reviews']          = $reviews;
                $arr_data['review_to']        = 'AIRCRAFT';
                $arr_data['ratings']          = $rating;

                $obj_create     = $this->ReviewsModel->create($arr_data);
                if($obj_create)
                { 
                    Session::flash('success',' Review saved Successfully.');
                    return redirect()->back();    
                }
                else
                {
                    Session::flash('error','Something went wrong');
                    return redirect()->back();
                }
            }
            else
            {
                Session::flash('error','Something went wrong');
                return redirect()->back();
            }
        }

        Session::flash('error', 'Sorry ! you are not applicable to review.');
        return redirect()->back();
    }   
    
    public function request_quotation(Request $request)
    {  
        $arr_rules['aircraft_id']       = "required";
        $arr_rules['user_id']           = "required";
        $arr_rules['pickup_date']       = "required";
        $arr_rules['return_date']       = "required";
        $arr_rules['pickup_loaction']   = "required";
        //$arr_rules['return_location']   = "required";

        $validator = Validator::make($request->all(),$arr_rules);
        
        if($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput();
        }

        $aircraft_id        = $request->input('aircraft_id');
        $user_id            = $request->input('user_id');
        $pickup_date        = $request->input('pickup_date');
        $return_date        = $request->input('return_date');
        //$return_date        = '2019-05-11';
        $pickup_loaction    = $request->input('pickup_loaction');
        $return_location    = $request->input('return_location');

        $is_valid_availability = $this->AvailabilityModel->where('aircraft_id', $aircraft_id)
                                                        ->where(function($q) use($pickup_date){
                                                            $q->whereRaw("'$pickup_date' >= Date(from_date)");
                                                            $q->whereRaw("'$pickup_date' <= Date(to_date)");
                                                        })
                                                        ->where(function($q) use($return_date){
                                                            $q->whereRaw("'$return_date' >= Date(from_date)");
                                                            $q->whereRaw("'$return_date' <= Date(to_date)");
                                                        })
                                                        ->count();
                                                        
        if(!$is_valid_availability > 0){
            Session::flash('error',' Invalid Date Selection. Please select the dates within available dates.');
            return redirect()->back();
        }

        $aircraft = $this->AircraftModel->with(['get_aircraft_type'])->where('id', $aircraft_id )->first();

        if($aircraft)
        {
            $arr_aircraft  = $aircraft->toArray();
            $owner_id      = isset($arr_aircraft['aircraft_owner_id']) ? $arr_aircraft['aircraft_owner_id'] : 'N/A';
            $aircraft_name = isset($arr_aircraft['get_aircraft_type']['model_name']) ? $arr_aircraft['get_aircraft_type']['model_name'] : 'N/A';
        }
        $owner   = $this->AircraftOwnerModel->where('id', $owner_id )->first();    
        if($owner)
        {
            $arr_owner = $owner->toArray();
        }
        $user    = $this->UsersModel->where('id', $user_id )->first(); 
        if($user)
        {
            $arr_user   = $user->toArray();
            $first_name = isset($arr_user['first_name']) ? $arr_user['first_name'] : 'N/A';
            $last_name  = isset($arr_user['last_name']) ? $arr_user['last_name'] : 'N/A';
            $user_name  = $first_name.' '.$last_name;
        }


        $arr_data['rfq_id']              = 'RFQ-'.mt_rand();
        $arr_data['aircraft_id']         =  $aircraft_id; 
        $arr_data['owner_id']            =  $owner_id;
        $arr_data['user_id']             =  $user_id; 
        $arr_data['from_date']           =  $pickup_date; 
        $arr_data['to_date']             =  $return_date; 
        $arr_data['pickup_location']     =  $pickup_loaction; 
        $arr_data['return_location']     =  isset($return_location) ? $return_location : 'NULL';
        $arr_data['status']              =  "REQUESTED"; 
        $obj_create     = $this->QuotationModel->create($arr_data);

        if($obj_create)
        { 
            $quotation_id = $this->QuotationModel->where('rfq_id', $arr_data['rfq_id'] )->first();   
            if($quotation_id)
            {
                $arr_quote_id = $quotation_id->toArray();
                $quote_id     = isset($arr_quote_id['id']) ? $arr_quote_id['id'] : 'N/A';
            }
            $admin_data = $this->SiteSettingModel->where('id','1')->first();
            if($admin_data)
            {
                $admin_email = $admin_data['site_email_address'];
            }
            $arr_email['email_id']          = $admin_email;
            $arr_email['rfq_id']            = $arr_data['rfq_id'];
            $arr_email['aircraft_name']     = $aircraft_name;
            $arr_email['user_name']     = $user_name;

        /*  $arr_email['reply'] = $admin_reply*/;

            $email_status = $this->MailService->send_quotation_request($arr_email);


            /*Notificcation to ADMIN*/
                $ARR_NOTIFICATIOn = [];
                $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
                $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
                $ARR_NOTIFICATION_DATA['sender_id']              = $user_id;
                $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
                $ARR_NOTIFICATION_DATA['title']                  = 'Quotation Request';
                $ARR_NOTIFICATION_DATA['description']            = $user_name.' has requested for Quotation of '.$aircraft_name;
                $ARR_NOTIFICATION_DATA['redirect_url']           = '/admin/quotation/view/'.base64_encode($quote_id);
                $ARR_NOTIFICATION_DATA['status']                 = 0;
                $ARR_NOTIFICATION_DATA['notification_type']      = 'reservation';
                $this->save_notification($ARR_NOTIFICATION_DATA);
            /*Notificcation to ADMIN*/
            /*Notificcation to OPERATOR*/
                $ARR_NOTIFICATIOn = [];
                $ARR_NOTIFICATION_DATA['receiver_id']            = $owner_id;
                $ARR_NOTIFICATION_DATA['receiver_type']          = 'aircraft_owner';
                $ARR_NOTIFICATION_DATA['sender_id']              = $user_id;
                $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
                $ARR_NOTIFICATION_DATA['title']                  = 'Quotation Request';
                $ARR_NOTIFICATION_DATA['description']            = 'A charter has requested for Quotation of '.$aircraft_name;
                $ARR_NOTIFICATION_DATA['redirect_url']           = 'operator/requested_quotations?search='.$arr_data['rfq_id'];
                $ARR_NOTIFICATION_DATA['status']                 = 0;
                $ARR_NOTIFICATION_DATA['notification_type']      = 'reservation';
                $this->save_notification($ARR_NOTIFICATION_DATA);
            /*Notificcation to OPERATOR*/
            Session::flash('success',' Quotation Request Sent Successfully.');
            return redirect()->back();
        }

        else
        {
            Session::flash('error','Something went wrong');
            return redirect()->back();
        }

    }
     public function ajax_more_review(Request $request)
    {
        $pageNumber    = trim($request->input('page'));
        $id    = $request->input('product_id');
        
        
        $finalBuilt = array();
        $status     = 'fail';
        $userMsg    = '';
        
        $itemPerPage = ('5');
       
        $position = (($pageNumber - 1) * $itemPerPage);
       
        $obj_data_reviews = $this->ReviewsModel->with(['user','owner','aircraft'])
                                               ->where('review_to','AIRCRAFT')
                                               ->where('aircraft_id',$id)
                                               ->where('status','1')
                                               ->orderBy('created_at','DESC')
                                               ->offset($position)
                                               ->limit($itemPerPage)
                                               ->get();

        if($obj_data_reviews)
        {
            $arr_data_reviews = $obj_data_reviews->toArray();
        }
     

        if(count($arr_data_reviews))
        {
            $status = 'done';
            $i = 0;
            foreach($arr_data_reviews as $row)
            {
                $link = '';

                if(isset($row['user']['profile_image']) && ($row['user']['profile_image'])!='')
                {
                     $fileURL = '';
                     // /$fileURL =  $this->user_profile_base_img_path; 
              
                     $profile_img_url = get_resized_image($row['user']['profile_image'], $this->user_profile_base_img_path,'100','100');      
                                                                    
                }else{
                 $profile_img_url  = url('/').'/images/front/about-blue-icon-3.png'; 
               }

                if(isset($row['user']['first_name']) && ($row['user']['last_name'])){
                    $name =   $row['user']['first_name'].$row['user']['last_name'];//.'=='.$row['id'];
                }else{
                    $name = 0;
                }

                if(isset($row['reviews']) && ($row['reviews'])){
                     $review = $row['reviews']; 
                }else{
                     $review = 0;
                }

                if(isset($row['ratings']) && ($row['ratings'])){
                     $rating = $row['ratings']; 
                }else{
                     $rating = 0;
                }
                 if(isset($row['created_at']) && ($row['created_at'])){
                     $time = get_formated_date($row['created_at']); 
                }else{
                     $time = 0;
                }

                $listValues[$i]['profile_img']        = $profile_img_url;
                $listValues[$i]['cname']              = $name;
                $listValues[$i]['reviews']            = $review;
                $listValues[$i]['ratings']            = $rating;
                $listValues[$i]['time']               = $time;
             
                $i++;
            }
            $finalBuilt = $listValues;
             
        }
        else
        {
            $status = 'done';
            $userMsg = 'No more result found';
        }
        $resp = array('status'  => $status,'userMsg' => $userMsg,'_tokenArrList' => $finalBuilt);
        return response()->json($resp);
    }
}