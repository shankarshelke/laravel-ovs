<?php


	function login_admin_details()
	{
		
		$auth    = auth()->guard('admin');
		if($auth->user()){
			return $auth->user();
		}
		return null;
	}

	function login_operator_details()
	{
		$auth = [];
		$auth    = auth()->guard('operator');
		
		if($auth->user()){
			return $auth->user();	
		}

		return $auth;
	}

	function is_user_logged_in($type)
	{
		$status = false;
		
		$auth   = auth()->guard($type);

		if ($auth->check())
		{
			$status = true;
		}
		return $status;
	}


	
	function login_user_id($type)
	{
		if($type!=null)
		{
			$auth    = auth()->guard($type);
			if ($auth->user()) 
			{
				return $auth->user()->id;
			}
		}
		return null;
	}

	function login_name($type)
	{
		if($type!=null)
		{
			$auth    = auth()->guard($type);
			if ($auth->user()) 
			{
				return $auth->user()->first_name.' '.$auth->user()->last_name;
			}
		}
		return null;
	}
	
	function get_formated_date($date='', $format=null)
	{

	    if ($format!=null) 
	    {
	        return date($format, strtotime($date));
	    }

	    return date('D d, M Y', strtotime($date));
	}

	function get_admin_access($module='',$sub_module='')
	{
	    
	    // dd($module,$sub_module);
	    $auth = \Auth::guard('admin');

	    $permissions = $auth->user()->permissions;
	    $arr_permissions = unserialize($permissions);
	    // dd($module,$sub_module,$arr_permissions);		
		if($auth->user()->admin_type == 'SUPERADMIN')
		{    
		    
			return true;
		}
		else
		{
			if($auth->user()->admin_type == 'SUBADMIN'){

			    if($arr_permissions)
			    {
			        if(array_key_exists($module, $arr_permissions) && in_array($sub_module, $arr_permissions[$module])){
			            return true;
			            
			        }else{
			        	
			            return false;
			        }
			    }else{
			    	
			        return false;
			    }
			}
		}
	}

	function translateText($text){
		  $translation=TranslateText::translate('en','mr',$text);
		  return $translation;
	  }
	

?>