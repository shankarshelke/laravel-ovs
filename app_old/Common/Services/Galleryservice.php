<?php

namespace App\Common\Services;

use App\Models\UsersModel;
use App\Models\TempUsersModel;
use App\Models\CountryModel;
use App\Models\ContestantModel;
use App\Models\GalleryModel;
use App\Models\VoteManagementModel;
use App\Models\FollowersModel;
use App\Models\SongsTokenModel;
use App\Common\Services\SmsService;

use Validator;

use Auth;
use JWTAuth;
use Hash;

class Galleryservice
{
	function __construct()
	{
		$this->UserModel = new UsersModel();
		$this->TempUserModel = new TempUsersModel();
		$this->CountryModel = new CountryModel();
		$this->ContestantModel = new ContestantModel();
		$this->GalleryModel = new GalleryModel();
		$this->VoteManagementModel = new VoteManagementModel();
		$this->FollowersModel = new FollowersModel();
		$this->SongsTokenModel = new SongsTokenModel();
		$this->SmsService	= new SmsService();
		$this->MailService	= new MailService();
		$this->auth      = auth()->guard('api_users');
		$this->sid = config('');
		$this->user_song_thumbnail_path = base_path().config('app.project.img_path.user_song_thumbnail');
		$this->user_song_thumbnail_url  = url('/').config('app.project.img_path.user_song_thumbnail');
		$this->user_user_profile_image_url  = url('/').config('app.project.img_path.user_profile_image');
	}	
	
	
    public function store_recorded_songs($request)
    {   
    	$arr_rule = $arr_data = [];
    	$file_name = "";
    	$arr_rule['song_url']   	   = "required";
    	$arr_rule['song_thumbnail']    = "required";
    	$arr_rule['song_name']    	   = "required";
    	$arr_rule['song_artist_name']  = "required";
			
			
		$validator = Validator::make($request->all(), $arr_rule);

		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Please fill all the required field.';
			$arr_responce['data']	= [];

			if($validator->errors())
			{
				$arr_responce['msg'] =$validator->errors()->first();
			}
			
			return $arr_responce;
		}

		$arr_data['song_url']  			= $request->input('song_url');
		$arr_data['song_url']  			= $request->input('song_url');
		$arr_data['song_thumbnail']  	= $request->input('song_thumbnail');
		$arr_data['song_name'] 	    	= $request->input('song_name');
		$arr_data['song_artist_name']   = $request->input('song_artist_name');
		$arr_data['user_id']			= $request->input('user_id');

		if($request->hasFile('song_thumbnail'))
		{
			$file_name = $request->input('song_thumbnail');
			$file_extension = strtolower($request->file('song_thumbnail')->getClientOriginalExtension());

			if(in_array($file_extension,['jpg','jpeg','png']))
			{
				$file_name = sha1(uniqid().$file_name.uniqid()).'.'.$file_extension;
				$isUpload = $request->file('song_thumbnail')->move($this->user_song_thumbnail_path,$file_name);
				if($isUpload)
				{
					$arr_data['song_thumbnail']  = isset($file_name)?$file_name : "";
				}
			}

		}

		$res = $this->GalleryModel->create($arr_data);

		if($res)
		{
			$arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'Song record added successfully.';
			$arr_responce['data']	= [];
			return $arr_responce;	
		}

		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops,Something went wrong,please try again later.';
		$arr_responce['data']	= [];
		return $arr_responce;

	}

	public function get_recorded_songs($request)
	{
		$obj_user_songs = $arr_user_songs = $arr_data = [];
		$user_id = $request->input('user_id');


		if(isset($user_id) && $user_id != "")
		{
			$obj_user_songs = $this->UserModel->where('id',$user_id)
												->with(['get_songs' => function($q){
													$q->select('id', 'song_url', 'song_thumbnail', 'song_name', 'song_artist_name', 'user_id','is_contestant_song', 'created_at');
													$q->orderBy('created_at','DESC');
												}])
												->first();

				$obj_songs = $obj_user_songs->get_songs->map(function($data){
				$data->song_thumbnail = $this->user_song_thumbnail_url.$data->song_thumbnail;

				return $data;
			});

 
		}

		if($obj_user_songs)
		{
			$arr_user_songs = $obj_user_songs->toArray();
			
			$arr_data['username'] = isset($arr_user_songs['username'])? $arr_user_songs['username'] : "";
			$arr_data['profile_image'] = $this->user_user_profile_image_url.$arr_user_songs['profile_image'];
			$arr_data['full_name'] = "";
			if(isset($arr_user_songs['first_name']) && $arr_user_songs['first_name'] !="")
			{
				$arr_data['full_name'] .= $arr_user_songs['first_name'].' ';
			}
			if(isset($arr_user_songs['last_name']) && $arr_user_songs['last_name'] !="")
			{
				$arr_data['full_name'] .= $arr_user_songs['last_name'];
			}

			$arr_data['followers']   ="0";
		    $arr_data['following']   ="0";
		    $arr_data['video_count'] ="0";
		    $arr_data['tokens'] 	 ="10";
		    $arr_data['songs_list']  = $arr_user_songs['get_songs'];
		   
			$arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'User details with there song list';
			$arr_responce['data']	= $arr_data;
			return $arr_responce;

		}

		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops, User not found.';
		$arr_responce['data']	= [];
		return $arr_responce;
		
	}

	public function enter_to_contest($request)
	{    
		$arr_data = [];
		$userdata = "";
		$user_id = $request->input('user_id');
		$gallery_id = $request->input('gallery_id');
		$no_of_token = '3';



		if(isset($user_id) && $user_id!="")
		{
			$objuser = $this->ContestantModel->where('user_id',$user_id)->first(['tokens','plan_id']);
			if($objuser)
			{
				$userdata = $objuser->toArray();
			}


			if(isset($userdata['tokens']) && $userdata['tokens'] !="")
			{  
				if($userdata['tokens']>=3)
				{	
					if(isset($gallery_id) && $gallery_id !="")
					{	
						$arr_data['gallery_id'] = $gallery_id;
						$arr_data['no_of_token'] = $no_of_token;


						$res_token = $this->SongsTokenModel->create($arr_data);
						
						if($res_token)
						{
							$uddatedtoken = $userdata['tokens'] - '3';
							$resdata = $this->ContestantModel->where('user_id',$user_id)->update(['tokens'=>$uddatedtoken]);
							if($resdata)
							{
								$resdata = $this->GalleryModel->where('id',$gallery_id)->update(['is_contestant_song'=>'1']);
								$arr_responce['status'] = 'success';
								$arr_responce['msg']	= 'Your video has been submitted to contest';
								$arr_responce['data']	= [];
								return $arr_responce;				
							}

						}
					}
					$arr_responce['status'] = 'error';
					$arr_responce['msg']	= 'gallery id required.';
					$arr_responce['data']	= [];
					return $arr_responce;

				}
				$arr_responce['status'] = 'error';
				$arr_responce['msg']	= 'You do not have sufficient token, please purchase token.';
				$arr_responce['data']	= [];
				return $arr_responce;

			}
			

			

			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'sorry, song id not found.';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
		
		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops, User not found.';
		$arr_responce['data']	= [];
		return $arr_responce;

	}


	public function watch_and_vote_list($request)
	{
		$obj_songs = $arr_songs = $arr_data = [];
		$obj_songs = $this->GalleryModel->with(['get_user_details' => function($q){
												$q->select('id','username', 'first_name');
											   },])
										     ->get();

			    $obj_songs_new = $obj_songs->map(function($data){
				$data->song_thumbnail = $this->user_song_thumbnail_url.$data->song_thumbnail;

				return $data;
			});

		if($obj_songs)
		{
			$arr_songs = $obj_songs->toArray();
			
			$arr_data['songs_list']  = $arr_songs;
		   
			$arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'Available songs list';
			$arr_responce['data']	= $arr_data;
			return $arr_responce;

		}

		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops, No songs available.';
		$arr_responce['data']	= [];
		return $arr_responce;
		
	}

	public function contestant_song_details($request)
	{
		$user_id = $request->input('user_id');
		$gallery_id = $request->input('gallery_id');


		if(isset($user_id) && $user_id!="")
		{
			if(isset($gallery_id) && $gallery_id !="")
			{
				$obj_songs = $this->GalleryModel->where('id',$gallery_id)->with(['get_user_details' => function($q){
												$q->select('id','username', 'first_name');
											   },])
										     ->get();

			    $obj_songs_new = $obj_songs->map(function($data){
				$data->song_thumbnail = $this->user_song_thumbnail_url.$data->song_thumbnail;

				return $data;
				});

				if($obj_songs)
				{
					$arr_songs = $obj_songs->toArray();
					
					//dd($arr_songs);
					if(!empty($arr_songs)){
						$arr_data['songs_list']  = $arr_songs;
						$arr_responce['status'] = 'success';
						$arr_responce['msg']	= 'Song details';
						$arr_responce['data']	= !empty($arr_songs) ? array_values($arr_songs)[0] : '' ;
						return $arr_responce;
					}
					else
					{
						$arr_responce['status'] = 'error';
						$arr_responce['msg']	= 'No details available';
						$arr_responce['data']	= [];
						return $arr_responce;
					}

				}
						
			}	
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'sorry, song id not found.';
			$arr_responce['data']	= [];
			return $arr_responce;
		}

		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops, User not found.';
		$arr_responce['data']	= [];
		return $arr_responce;

	}

	public function add_rating($request)
	{
		$arr_rule = $arr_data = [];
    	$file_name = "";
    	$arr_rule['gallery_id']   	   = "required";
    	$arr_rule['ratings']    = "required";
    	
		$validator = Validator::make($request->all(), $arr_rule);

		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Please fill all the required field.';
			$arr_responce['data']	= [];

			if($validator->errors())
			{
				$arr_responce['msg'] =$validator->errors()->first();
			}
			
			return $arr_responce;
		}

		$arr_data['gallery_id']  = $request->input('gallery_id');
		$arr_data['ratings']  	 = $request->input('ratings');
		$arr_data['message']  	 = $request->input('message');
		$arr_data['vote_from_user']  	 = $request->input('user_id');
		$user_id  	 = $request->input('user_id');
		$gallery_id  = $request->input('gallery_id');

		if(isset($user_id) && $user_id!="")
		{
			if(isset($gallery_id) && $gallery_id !="")
			{
				$obj_songs = $this->GalleryModel->where('id',$gallery_id)->first();
				$songdata = $obj_songs->toArray();
				
				$arr_data['vote_to_user'] = $songdata['user_id'];
				
				$resvote = $this->VoteManagementModel->create($arr_data);
				if($resvote)
				{
					$songs = [];
					$rating = $this->VoteManagementModel->where('gallery_id', $gallery_id)->avg('ratings');
					if($rating)
					{
						$this->GalleryModel->where('id',$gallery_id)->update(['avg_rating' => $rating]);
					}
					
					$arr_responce['status'] = 'success';
					$arr_responce['msg']	= 'Rating done successfully.';
					$arr_responce['data']	= [];
					return $arr_responce;
				}
			}
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'sorry, song id not found.';
			$arr_responce['data']	= [];
			return $arr_responce;

	   	}

	   	$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops, User not found.';
		$arr_responce['data']	= [];
		return $arr_responce;

	}

	public function follow_unfollow($request)
	{
		$arr_rule = $arr_data = [];
    	$file_name = "";
    	
    	$arr_rule['follow_status']     = "required";
    	$arr_rule['gallery_id']     = "required";
    	
    	$validator = Validator::make($request->all(), $arr_rule);

		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Please fill all the required field.';
			$arr_responce['data']	= [];

			if($validator->errors())
			{
				$arr_responce['msg'] =$validator->errors()->first();
			}
			
			return $arr_responce;
		}

		$arr_data['followers_id']    = $request->input('user_id');
		
		$user_id         = $request->input('user_id');
		$gallery_id      = $request->input('gallery_id');

		$obj_gallery_rec = $this->GalleryModel->where('id',$gallery_id)->first();
	    
	    if($obj_gallery_rec)
	    {
	    	$following_to = $obj_gallery_rec->user_id;
		}

		$arr_data['following_to']    = $following_to;
		
		$cntFollwers 	 = $this->FollowersModel->where('followers_id',$user_id)->where('following_to',$following_to)->count();

	    if($cntFollwers > 0)
	    {
	    	$resUnfollow = $this->FollowersModel->where('followers_id',$user_id)->where('following_to',$following_to)->delete();
	    	if($resUnfollow)
	    	{
	    		$followerscnt = $this->FollowersModel->where('following_to',$user_id)->count();
	    		$followingscnt= $this->FollowersModel->where('followers_id',$user_id)->count();
	    		
	    		$res = $this->UserModel->where('id',$user_id)->update(['followers'=>$followerscnt,'followings'=>$followingscnt]);

	    		$arr_responce['status'] = 'success';
				$arr_responce['msg']	= 'User unfollow successfully';
				$arr_responce['data']	= [];
				return $arr_responce;
		    }
	    }
	    else
	    {	
	    	$resUnfollow = $this->FollowersModel->create($arr_data);
	    	if($resUnfollow)
	    	{  
	    		$followerscnt = $this->FollowersModel->where('following_to',$user_id)->count();
	    	    $followingscnt= $this->FollowersModel->where('followers_id',$user_id)->count();

	    		$res = $this->UserModel->where('id',$user_id)->update(['followers'=>$followerscnt,'followings'=>$followingscnt]);
	    		
	    		$arr_responce['status'] = 'success';
				$arr_responce['msg']	= 'User follow successfully';
				$arr_responce['data']	= [];
				return $arr_responce;
		    }

	    }

	    $arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops, User not found.';
		$arr_responce['data']	= [];
		return $arr_responce;

	}



}

?>