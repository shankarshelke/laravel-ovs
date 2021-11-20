<?php 

namespace App\Common\Traits;

use Illuminate\Http\Request;
use App\Events\ActivityLogEvent;
use App\Events\NotificationEvent;
use App\Http\Controllers\Controller;
use Flash;
use Session;
use Validator;
 
trait MultiActionTrait
{
    public function multi_action(Request $request)
    {
        $arr_rules = array();
        $arr_rules['multi_action'] = "required";
        $arr_rules['checked_record'] = "required";
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            Session::flash('Please Select '.$this->module_title.' To Perform Multi Actions');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $multi_action = $request->input('multi_action');
        $checked_record = $request->input('checked_record');

        /* Check if array is supplied*/
        if(is_array($checked_record) && sizeof($checked_record)<=0)
        {
            Session::flash('error', 'Problem Occurred, While Doing Multi Action');
            return redirect()->back();
        }

        foreach ($checked_record as $key => $record_id) 
        {  
            if($multi_action=="delete")
            {
               $resDelete = $this->perform_delete(base64_decode($record_id));    
                Session::flash('success', $this->module_title. ' Deleted Successfully');
            } 
            elseif($multi_action=="activate")
            {
               $resActive = $this->perform_unblock(base64_decode($record_id)); 
               Session::flash('success', $this->module_title. ' Activated Successfully');
            }
            elseif($multi_action=="deactivate")
            {
               $resDeactive = $this->perform_block(base64_decode($record_id));   
               Session::flash('success', $this->module_title. ' inactivated Successfully');
            }
            elseif($multi_action=="message")
            {
               $resDeactive = $this->perform_sendmsg(base64_decode($record_id));   
               Session::flash('success', $this->module_title. ' send Successfully');
            }
            elseif($multi_action=="approve")
            {
               $resApprove = $this->perform_unapprove(base64_decode($record_id)); 
               Session::flash('success', $this->module_title. ' Approved Successfully');
            }
            elseif($multi_action=="unapprove")
            {
               $resUnapprove = $this->perform_approve(base64_decode($record_id));   
               Session::flash('success', $this->module_title. ' Unapproved Successfully');
            }
            elseif($multi_action=="verified")
            {
               $resUnapprove = $this->perform_verified(base64_decode($record_id));   
               Session::flash('success', $this->module_title. ' Verified Successfully');
            }
        }

        return redirect()->back();
    }

    public function unprocessed($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_unblock(base64_decode($enc_id)))
        {
            Session::flash('success', str_singular($this->module_title). ' Processed Successfully');
            return redirect()->back();
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.str_singular($this->module_title).' Activation ');
        }

        return redirect()->back();
    }

    public function unblock($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_unblock(base64_decode($enc_id)))
        {
            Session::flash('success', str_singular($this->module_title). ' Activated Successfully');
            return redirect()->back();
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.str_singular($this->module_title).' Activation ');
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
            Session::flash('success', str_singular($this->module_title). ' Inactivated Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.str_singular($this->module_title).' Deactivation ');
        }

        return redirect()->back();
    }

    public function delete($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_delete(base64_decode($enc_id)))
        {
            Session::flash('success', str_singular($this->module_title). ' Deleted Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.str_singular($this->module_title).' Deletion ');
        }

        return redirect()->back();
    }

    public function show($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_show(base64_decode($enc_id)))
        {
            Session::flash('success', str_singular($this->module_title). ' Branch Front Show Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.str_singular($this->module_title).' Front Show ');
        }

        return redirect()->back();
    }

    public function hide($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_hide(base64_decode($enc_id)))
        {
            Session::flash('success', str_singular($this->module_title). ' Branch Front Show Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.str_singular($this->module_title).' Front Show ');
        }

        return redirect()->back();
    }

    public function approve($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_approve(base64_decode($enc_id)))
        {
            Session::flash('success', $this->module_title. ' Approved Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.$this->module_title. ' Approved');
        }

        return redirect()->back();
    }

    public function unapprove($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_unapprove(base64_decode($enc_id)))
        {
            Session::flash('success', $this->module_title. ' Unapproved Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.$this->module_title. ' unapproved');
        }

        return redirect()->back();
    }

    public function verified($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_verified(base64_decode($enc_id)))
        {
            Session::flash('success', $this->module_title. ' Verified Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.$this->module_title. ' verified');
        }

        return redirect()->back();
    }

    public function perform_unblock($id)
    {
        if($id!=null)
        {
            $responce = $this->BaseModel->where('id',$id)->update(['status'=>'1']);
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
            $responce = $this->BaseModel->where('id',$id)->update(['status'=>'0']);
            if($responce)
            {
                return TRUE;
            }  
            return FALSE;          
        }
        return FALSE;
    }

    public function perform_show($id)
    {
        if($id!=null)
        {
            $responce = $this->BaseModel->where('id',$id)->update(['front_status'=>'1']);
            if($responce)
            {
                return TRUE;
            }  
            return FALSE;          
        }
        return FALSE;
    }

    public function perform_hide($id)
    {
        if($id!=null)
        {
            $responce = $this->BaseModel->where('id',$id)->update(['front_status'=>'0']);
            if($responce)
            {
                return TRUE;
            }  
            return FALSE;          
        }
        return FALSE;
    }

    public function perform_approve($id)
    {
        if($id!=null)
        {
            $responce = $this->BaseModel->where('id',$id)->update(['status'=>'1']);
            if($responce)
            {
                return TRUE;
            }  
            return FALSE;          
        }
        return FALSE;
    }

    public function perform_unapprove($id)
    {
        if($id!=null)
        {
            $responce = $this->BaseModel->where('id',$id)->update(['status'=>'2']);
            if($responce)
            {
                return TRUE;
            }  
            return FALSE;          
        }
        return FALSE;
    }
    public function perform_verified($id)
    {
        if($id!=null)
        {
            $responce = $this->BaseModel->where('id',$id)->update(['is_email_verified'=>'1']);
            if($responce)
            {
                return TRUE;
            }  
            return FALSE;          
        }
        return FALSE;
    }

    public function perform_delete($id)
    {
        $delete= $this->BaseModel->where('id',$id)->delete();
        
        if($delete)
        {
            return TRUE;
        }
        return FALSE;
    }
    public function perform_sendmsg($id)
    {
                    $message    =  "Hello Welcome to Voter Management ";
                    $to_number  = "+918888910323";
                   $email_status = $this->SmsService->send_sms($message,$to_number);

    }
  /* ACTION LOGS */
    public function actionLogActive()
    {
        /*-------------------------------------------------------
        |   Activity log Event
        --------------------------------------------------------*/
        $arr_event                     = [];
        $arr_event['ACTIVITY_MESSAGE'] = str_singular($this->module_title).' Activated By '.login_name($this->admin_panel_slug)."";
        $arr_event['IP_ADDRESS']       = $this->ip_address;
        $arr_event['ACTION']           = 'Active';
        $arr_event['MODULE_TITLE']     = $this->module_title;
        $this->save_activity($arr_event);
        /*----------------------------------------------------------------------*/
    }
    
    public function actionLogDeActive()
    {
        /*-------------------------------------------------------
        |   Activity log Event
        --------------------------------------------------------*/
        $arr_event                     = [];
        $arr_event['ACTIVITY_MESSAGE'] = str_singular($this->module_title).' Deactivated By '.login_name($this->admin_panel_slug)."";
        $arr_event['IP_ADDRESS']       = $this->ip_address;
        $arr_event['ACTION']           = 'Inactive';
        $arr_event['MODULE_TITLE']     = $this->module_title;
        $this->save_activity($arr_event);
        /*----------------------------------------------------------------------*/
    }
        
    public function actionLogDelete()
    {
        /*-------------------------------------------------------
        |   Activity log Event
        --------------------------------------------------------*/
        $arr_event                     = [];
        $arr_event['ACTIVITY_MESSAGE'] = str_singular($this->module_title).' Deleted By '.login_name($this->admin_panel_slug)."";
        $arr_event['IP_ADDRESS']       = $this->ip_address;
        $arr_event['ACTION']           = 'Delete';
        $arr_event['MODULE_TITLE']     = $this->module_title;
        $this->save_activity($arr_event);
        /*----------------------------------------------------------------------*/
    }
        
    /* ACTION LOGS */    
}
