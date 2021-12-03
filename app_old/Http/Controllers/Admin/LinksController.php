<?
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Common\Traits\MultiActionTrait;

use App\Models\LinksModel;
use Validator;
use Session;
use DataTables;
use Response;
use DB;

class LinksController extends Controller
{
use MultiActionTrait;
    function __construct()
    {
        $this->arr_view_data                = [];
        $this->admin_panel_slug             = config('app.project.admin_panel_slug');
        $this->admin_url_path               = url(config('app.project.admin_panel_slug'));
        $this->module_url_path              = $this->admin_url_path."/links";
        $this->module_title                 = "Links";
        $this->module_view_folder           = "admin.links";
        $this->module_icon                  = "fa fa-user";
        $this->auth                         = auth()->guard('admin');
        $this->BaseModel                    = new LinksModel();
        
    }

    public function index()
    {
        //dd( $this->arr_view_data);

        $arr_teams = [];

        $obj_teams = $this->BaseModel->get();

        if($obj_teams)
        {//dd($obj_teams);
            $arr_teams = $obj_teams->toArray();
        }
        
        $this->arr_view_data['page_title']          = "Manage ".$this->module_title;
        $this->arr_view_data['parent_module_icon']  = "fa-home";
        $this->arr_view_data['parent_module_title'] = "Dashboard";
        $this->arr_view_data['parent_module_url']   = url('/').'/admin/dashboard';
        $this->arr_view_data['module_icon']         = $this->module_icon;
        $this->arr_view_data['module_title']        = "Manage ".$this->module_title;
        $this->arr_view_data['module_url_path']     = $this->module_url_path;
        $this->arr_view_data['admin_url_path']      = $this->admin_url_path;
        $this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
        $this->arr_view_data['arr_teams']            = $arr_teams;

//dd($this->arr_view_data);


        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

 

 public function create()
    {
    
       
        $this->arr_view_data['parent_module_icon']   = "fa-home";
        $this->arr_view_data['parent_module_title']  = "Dashboard";
        $this->arr_view_data['parent_module_url']    = url('/').'/admin/dashboard';
        $this->arr_view_data['page_title']           = 'Create '.str_singular($this->module_title);
        $this->arr_view_data['page_icon']            = $this->module_icon;
        $this->arr_view_data['module_title']         = 'Manage '.$this->module_title;
        $this->arr_view_data['sub_module_title']     = 'Create '.$this->module_title;
        $this->arr_view_data['sub_module_icon']      = 'fa fa-plus';
        $this->arr_view_data['module_icon']          = $this->module_icon;
        $this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
        $this->arr_view_data['module_url_path']      = $this->module_url_path;
        $this->arr_view_data['module_url']           = $this->module_url_path;
        // dd($this->arr_view_data);
        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }

    public function store(Request $request)
    { 
        
       // dd($request->all());
        $arr_rules      = array();
        $status         = false;

        $arr_rules['links']             = "required";
        $arr_rules['banners']           = "required";
        
        
        $validator = validator::make($request->all(),$arr_rules);

        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    

        $arr_data = [];
        $arr_data['links']              = $request->input('links', null);
        $arr_data['banners']            = $request->input('banners', null);
     
      
         // dd($arr_data);


            $user = $this->BaseModel->create($arr_data);
            if($user)
            {
//dd($user);
                Session::flash('success', str_singular($this->module_title).' created successfully.');
            return redirect($this->module_url_path.'/create');
        }
        Session::flash('error', 'Error while creating '.str_singular($this->module_title).'.');
        return redirect($this->module_url_path.'/create');

    }
}


