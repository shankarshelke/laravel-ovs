<?php 

namespace App\Common\Services;
use Illuminate\Http\Request;
use App\Models\EmailTemplateModel;
use Session;
use Mail;
use Auth;
use URL;

class MailService
{
	public function __construct()
	{
		
	}	
	
	public function get_email_template($email_template_id = false)
	{
		$arr_template_data = [];

		$obj_email_template = EmailTemplateModel::where('id',$email_template_id)->first();

		if($obj_email_template)
		{
			$arr_template_data = $obj_email_template->toArray();
		}
		return $arr_template_data;
	}

	public function send_mail($arr_email,$content)
	{

        $from_email     = isset($arr_email['from_mail']) ? $arr_email['from_mail'] : '';
        $project_name   = config('app.project.name');
        // dd($project_name);      
        try{
                $send_mail = Mail::send(array(), array(),function($message) use($arr_email,$content,$from_email,$project_name)
            	{
            		$message->from($from_email,$project_name);
            		$message->to($arr_email['to_email_id'],isset($arr_email['to_name'])?$arr_email['to_name']:'')
            		->subject($arr_email['subject'])
            		->setBody($content, 'text/html');
            	});

                
            if(count(Mail::failures()) > 0){
                    return false;
                }
                else
                {
                  return true;
                }
            }    

        catch(\Exception $e){
            dd($e);
        }


    	return $send_mail;
    }

    public function send_conatct_enquiry_reply($arr_data = null)
    {
        $content     = '';
        $arr_enquiry = $arr_email = [];
        $full_name   = isset($arr_data['name'])?$arr_data['name']:'';
        $email       = isset($arr_data['email_id'])?$arr_data['email_id']:'';
        $message     = isset($arr_data['message'])?$arr_data['message']:'';
        $reply       = isset($arr_data['reply'])?$arr_data['reply']:'';

        $arr_template            = $this->get_email_template('1');
        $content                 = isset($arr_template['template_html'])?$arr_template['template_html']:'';
        $content                 = str_replace("##SUBJECT##",strip_tags($arr_template['template_subject']),$content);
        $content                 = str_replace("##USERNAME##",strip_tags($full_name),$content);
        $content                 = str_replace("##MESSAGE##",strip_tags($message),$content);
        $content                 = str_replace("##REPLY##",strip_tags($reply),$content);
        $content                 = view('admin.email.general',compact('content'))->render();
        $content                 = html_entity_decode($content);

        $arr_email['from_mail'] =  isset($arr_template['template_from_mail']) ? $arr_template['template_from_mail'] : '';
        $arr_email['to_email_id']   = $email;
        $arr_email['subject']    = isset($arr_template['template_subject'])?$arr_template['template_subject']:'';
        $arr_email['to_name']    = $full_name;
        $arr_email['full_name']  = $full_name;

        $status                  = $this->send_mail($arr_email,$content);

        return $status;
    }

    public function send_user_registration_detail($arr_data = array())
    {
        $arr_email = $arr_email_template = [];
        if(isset($arr_data['to_email_id']))
        {
            // dd('here');
            $arr_email['to_email_id']      = isset($arr_data['to_email_id'])?$arr_data['to_email_id']:'';
            //$templated_id                  = isset($arr_data['email_template_id'])?$arr_data['email_template_id']:'';
            $arr_email['to_email_id']      = isset($arr_data['to_email_id'])?$arr_data['to_email_id']:'';
            $arr_email['to_name']          = isset($arr_data['to_user_name'])?$arr_data['to_user_name']:'';
            // $arr_email['verification_url'] = isset($arr_data['verification_url'])?$arr_data['verification_url']:'';
            $arr_email_template            = $this->get_email_template('6');
            // dd($arr_email_template);
            if($arr_email_template)
            {           
                $subject = isset($arr_email_template['template_subject']) ? $arr_email_template['template_subject'] : ''; 
                $content = isset($arr_email_template['template_html']) ? $arr_email_template['template_html'] : '';
                $content = str_replace("##USERNAME##",$arr_data['to_user_name'],$content);
                $content = str_replace("##PASSWORD##",$arr_data['password'],$content);
                $content = str_replace("##SUBJECT##",$subject,$content);
                $content = str_replace("##USER_ID##",$arr_email['to_email_id'],$content);
                $content = str_replace("##USER_TYPE##",$arr_data['role'],$content);
                $content = str_replace("##FIRST_NAME##",$arr_data['first_name'],$content);
                $content = str_replace("##LOGIN_LINK##",$arr_data['login_url'],$content);
                $content = str_replace("##PROJECT_NAME##",config('app.project.name'), $content);
                // $content = str_replace("##SITE_URL##",$arr_data['verification_url'],$content); 
                $content = view('admin.email.general', compact('content'))->render();
                $content = html_entity_decode($content);
                $arr_email['subject']   = isset($subject)?$subject:'';
                $arr_email['from_mail'] = isset($arr_email_template['template_from_mail']) ? $arr_email_template['template_from_mail'] : '';
                $send_mail = $this->send_mail($arr_email,$content);
               // dd($send_mail);
                return $send_mail;
            }
            return FALSE;   
        }
        return false;
    }


    public function send_user_registration_email($arr_data = array())
    {
        $arr_email = [];
        if(isset($arr_data['to_email_id']))
        {
            $arr_email['webadmin_id'] = isset($arr_data['webadmin_id'])?$arr_data['webadmin_id']:'';

            $arr_email['to_email_id']      = isset($arr_data['to_email_id'])?$arr_data['to_email_id']:'';
            $arr_email['to_name']          = isset($arr_data['to_user_name'])?$arr_data['to_user_name']:'';
            $arr_email['verification_url'] = isset($arr_data['verification_url'])?$arr_data['verification_url']:'';
            $arr_email['user_type']        = isset($arr_data['role'])?$arr_data['role']:'';
            $email_subject  = config('app.project.name').' : '.'Welcome to '.config('app.project.name');

            $arr_email_template = [];

            $arr_email_template = $this->get_email_template('14');
            
            if($arr_email_template)
            {           
                $subject = isset($arr_email_template['template_subject']) ? $arr_email_template['template_subject'] : ''; 

                $content = isset($arr_email_template['template_html']) ? $arr_email_template['template_html'] : '';

                $content = str_replace("##FIRST_NAME##",$arr_data['first_name'], $content);
                $content = str_replace("##USER_TYPE##",$arr_email['user_type'],$content);
                $content = str_replace("##WEBADMIN_ID##",$arr_email['webadmin_id'],$content);

                $content = str_replace("##PROJECT_NAME##",config('app.project.name'), $content);
                $content = str_replace("##LINK##",$arr_email['verification_url'], $content);
                $content = view('admin.email.general', compact('content'))->render();
                $content = html_entity_decode($content);
                // dd($content);

                $arr_email['subject']= isset($subject)? $subject:'';

                $arr_email['from_mail'] =  isset($arr_email_template['template_from_mail']) ? $arr_email_template['template_from_mail'] : '';
         
                $send_mail = $this->send_mail($arr_email,$content);
                return $send_mail;
            }
            return FALSE;   
        }
        
        return false;
    }

    
    public function send_user_registration_email_for_api($arr_data = array())
    {   
        $arr_email = [];
        if(isset($arr_data['to_email_id']))
             {
            $arr_email['to_email_id']      = isset($arr_data['to_email_id'])?$arr_data['to_email_id']:'';
            $arr_email['username']         = isset($arr_data['username'])?$arr_data['username']:'';;
            $email_subject  = config('app.project.name').' : '.'Welcome to '.config('app.project.name');

    //  dd('hello');
            $arr_email_template = [];

            $arr_email_template = $this->get_email_template('14');
            
            if($arr_email_template)
            {           
                $subject = isset($arr_email_template['template_subject']) ? $arr_email_template['template_subject'] : ''; 

                $content = isset($arr_email_template['template_html']) ? $arr_email_template['template_html'] : '';

               // $content = str_replace("##FIRST_NAME##",$arr_data['first_name'], $content);
                $content = str_replace("##USERNAME##",$arr_data['username'],$content);
                $content = str_replace("##PROJECT_NAME##",config('app.project.name'), $content);
                $content = str_replace("##LINK##",$arr_data['link'],$content);
                // $content = str_replace("##LINK##",$arr_data['verification_url'], $content);
               // $content = view('api.email.general', compact('content'))->render();
                
                $content = html_entity_decode($content);
// dd($content);
                
                $arr_email['subject']= isset($subject)? $subject:'';

                $arr_email['from_mail'] =  isset($arr_email_template['template_from_mail']) ? $arr_email_template['template_from_mail'] : '';

                $send_mail = $this->send_mail($arr_email,$content);
                return $send_mail;
            }
            return FALSE;   
        }
        
        return false;
    }

    public function send_mail_newsletter($arr_mail_data = FALSE)
    {
        if(isset($arr_mail_data) && sizeof($arr_mail_data)>0)
        {
            $arr_email_template = [];
            
            $obj_email_template = new EmailTemplateModel();

            $obj_email_template = $obj_email_template
                                        ->where('id',$arr_mail_data['email_template_id'])
                                        ->first();
            
            if($obj_email_template)
            {
                $arr_email_template = $obj_email_template->toArray();
                $user               = $arr_mail_data['user'];
                
                if(isset($arr_email_template['template_html']))
                {
                    $content = $arr_email_template['template_html'];
                    
                    if(isset($arr_mail_data['arr_built_content']) && sizeof($arr_mail_data['arr_built_content'])>0)
                    {
                        foreach($arr_mail_data['arr_built_content'] as $key => $data)
                        {
                            $content = str_replace("##".$key."##",$data,$content);
                        }
                    }
               
                    $content = view('admin.email.general',compact('content'))->render();
                    $content = html_entity_decode($content);
                    
                    if(isset($user['email']) && $user['email']!='')
                    {
                        $send_mail = Mail::send(array(),array(), function($message) use($user,$arr_email_template,$content){
                            $name = isset($user['first_name']) ? $user['first_name']:"";
                            $message->from($arr_email_template['template_from_mail'], $arr_email_template['template_from']);
                            $message->to($user['email'], $name)
                            //$message->to('faca@pachilly.com', $name )
                                      ->subject($arr_email_template['template_subject'])
                                      ->setBody($content, 'text/html');
                        });
                     return true;
                    }
                    return false;
                }
            }
        }
        return false;    
    }

    /* Api this function is used to send an otp to reset password for maerchanr */
    public function send_forget_password_email($arr_data = array())
    {
        // dd('here');
        $arr_email =  $arr_email_template = [];
        if(isset($arr_data['to_email_id']))
        {
            $arr_email['to_email_id']      = isset($arr_data['to_email_id'])?$arr_data['to_email_id']:'';
            $templated_id                  = isset($arr_data['email_template_id'])?$arr_data['email_template_id']:'';
            $otp_code                      = isset($arr_data['otp_code'])?$arr_data['otp_code']:'';
            $arr_email['to_email_id']      = isset($arr_data['to_email_id'])?$arr_data['to_email_id']:'';
            $arr_email['to_name']          = isset($arr_data['to_user_name'])?$arr_data['to_user_name']:'';
            $arr_email['verification_url'] = isset($arr_data['verification_url'])?$arr_data['verification_url']:'';
            $arr_email_template            = $this->get_email_template('3');
            if($arr_email_template)
            {           
                $subject = isset($arr_email_template['template_subject']) ? $arr_email_template['template_subject'] : ''; 
                $content = isset($arr_email_template['template_html']) ? $arr_email_template['template_html'] : '';
                $content = str_replace("##OTP##",$otp_code, $content);
                $content = str_replace("##USERNAME##",$arr_data['to_user_name'],$content);
                $content = str_replace("##SUBJECT##",$subject,$content);
                $content = str_replace("##SITE_URL##",$arr_data['verification_url'],$content); 
                $content = view('admin.email.general', compact('content'))->render();
                $content = html_entity_decode($content);
                $arr_email['subject']= isset($subject)? $subject:'';
                $arr_email['from_mail'] =  isset($arr_email_template['template_from_mail']) ? $arr_email_template['template_from_mail'] : '';
                $send_mail = $this->send_mail($arr_email,$content);
                return $send_mail;
            }
            return FALSE;   
        }
        return false;
    }

    /* Api this function is used to send an otp to reset password for email */
    public function send_forget_password_email_for_api($arr_data = array())
    {
        $arr_email = $arr_email_template = [];
        if(isset($arr_data['to_email_id']))
        {
            $arr_email['to_email_id']      = isset($arr_data['to_email_id'])?$arr_data['to_email_id']:'';
            $templated_id                  = isset($arr_data['email_template_id'])?$arr_data['email_template_id']:'';
            $otp_code                      = isset($arr_data['otp'])?$arr_data['otp']:'';
            $arr_email['to_email_id']      = isset($arr_data['to_email_id'])?$arr_data['to_email_id']:'';
            $arr_email['to_name']          = isset($arr_data['to_user_name'])?$arr_data['to_user_name']:'';
            // $arr_email['verification_url'] = isset($arr_data['verification_url'])?$arr_data['verification_url']:'';
            $arr_email_template            = $this->get_email_template('19');
            if($arr_email_template)
            {           
                $subject = isset($arr_email_template['template_subject']) ? $arr_email_template['template_subject'] : ''; 
                $content = isset($arr_email_template['template_html']) ? $arr_email_template['template_html'] : '';
                $content = str_replace("##OTP##",$otp_code, $content);
                $content = str_replace("##USERNAME##",$arr_data['to_user_name'],$content);
                $content = str_replace("##SUBJECT##",$subject,$content);
                // $content = str_replace("##SITE_URL##",$arr_data['verification_url'],$content); 
                $content = view('admin.email.general', compact('content'))->render();
                $content = html_entity_decode($content);
                $arr_email['subject']   = isset($subject)?$subject:'';
                $arr_email['from_mail'] = isset($arr_email_template['template_from_mail']) ? $arr_email_template['template_from_mail'] : '';
                $send_mail = $this->send_mail($arr_email,$content);
                return $send_mail;
            }
            return FALSE;   
        }
        return false;
    }
}