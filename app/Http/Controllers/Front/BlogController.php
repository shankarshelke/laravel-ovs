<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BlogsModel;

use Validator;
use Session;
use DB;

class BlogController extends Controller
{
    function __construct()
    {
        $this->arr_view_data      = [];
        $this->module_title       = "Home";
        $this->module_view_folder = "front.blogs.";
        $this->blogs    = new BlogsModel();
        $this->common_url         = url('/');
        $this->ip_address         = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;  
    }

    public function index()
    {
        $arr_blogs = $arr_recent_blogs = [];

        $obj_blogs = $this->blogs->where('status','1')->paginate(4);
        if($obj_blogs)
        {
            $arr_blogs = $obj_blogs->toArray();  
            $page_link = $obj_blogs->links();         
        }
        /*dd($arr_blogs);*/
        $obj_recent_blogs = $this->blogs->where('status','1')->orderBy('created_at','desc')->limit(5)->get();

        if($obj_recent_blogs)
        {
            $arr_recent_blogs = $obj_recent_blogs->toArray();         
        }

       /*  $obj_blogs_by_type = $this->blogs->with(['type'])->get();

        if($obj_blogs_by_type)
        {
            $arr_blogs_by_type = $obj_blogs_by_type->toArray();         
        }*/

      /*  dd($arr_recent_blogs);*/
        $blogs_base_img_path = base_path().config('app.project.img_path.blogs_image');
        $blogs_public_img_path =url('/').config('app.project.img_path.blogs_image');

        $this->arr_view_data['page_title'] = 'Home';
        $this->arr_view_data['arr_blogs']  = $arr_blogs;
        $this->arr_view_data['arr_recent_blogs']  = $arr_recent_blogs;
        $this->arr_view_data['page_link']  = $page_link;
        $this->arr_view_data['blogs_base_img_path']  = $blogs_base_img_path;
        $this->arr_view_data['blogs_public_img_path']  = $blogs_public_img_path;

      	return view($this->module_view_folder.'index',$this->arr_view_data);
    }
    public function blog_details(Request $request,$enc_id)
    {
        $id = base64_decode($enc_id);

        $obj_blogs = $this->blogs->where('id',$id)->first();
        if($obj_blogs)
        {
            $arr_blogs = $obj_blogs->toArray();  

        }
        /*dd($arr_blogs);*/
        $obj_recent_blogs = $this->blogs->where('status','1')->orderBy('created_at','desc')->limit(5)->get();

        if($obj_recent_blogs)
        {
            $arr_recent_blogs = $obj_recent_blogs->toArray();         
        }

        $blogs_base_img_path = base_path().config('app.project.img_path.blogs_image');
        $blogs_public_img_path =url('/').config('app.project.img_path.blogs_image');

        $this->arr_view_data['page_title'] = 'Home';
        $this->arr_view_data['arr_blogs']  = $arr_blogs;
        $this->arr_view_data['arr_recent_blogs']  = $arr_recent_blogs;
        $this->arr_view_data['blogs_base_img_path']  = $blogs_base_img_path;
        $this->arr_view_data['blogs_public_img_path']  = $blogs_public_img_path;

        return view($this->module_view_folder.'blog_details',$this->arr_view_data);
    }
}
