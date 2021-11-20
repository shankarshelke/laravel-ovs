<?php 

$web_admin_path = config('app.project.admin_panel_slug');

// ------------Before Login Routes----------------

Route::group(array('prefix' => $web_admin_path,'middleware'=>'admin_auth_check'), function ()
{
	$route_slug = 'admin_';
	$module_controller = 'Admin\AuthController@';

	Route::get('/', 									['as' => $route_slug.'login',
															'uses' => $module_controller.'login']);

	Route::post('/validate_login', 						['as' => $route_slug.'validate',
															'uses' => $module_controller.'validate_login']);

	$module_controller = "Admin\PasswordController@";

	Route::get('forgot_password',						['as' => $route_slug.'forgot_password',
															'uses' => $module_controller.'forgot_password']);

	Route::post('forgot_password/post_email',			['as' => $route_slug.'forgot_password_post_email'
															,'uses' => $module_controller.'postEmail']);

	Route::post('forgot_password/postReset',			['as' => $route_slug.'forgot_password_post_reset'
															,'uses' => $module_controller.'postReset']);

	Route::get('/reset_password/{token?}', 				['uses'=>$module_controller.'get_email'])->name('password.reset');
});


// ----------------------After login routes--------------------------

Route::group(array('prefix' => $web_admin_path,'middleware'=>'auth_admin'), function ()
{
	$route_slug = 'admin_';

	$module_controller = 'Admin\AuthController@';

	Route::get('logout', 				['as' => $route_slug.'logout', 'uses' => $module_controller.'logout']);

	$module_controller = "Admin\DashboardController@";
	Route::get('/dashboard', 			  ['as' =>$route_slug.'index', 'uses' => $module_controller.'index' , 'middleware' => 'module_access_permission']);


	$module_controller = "Admin\SiteSettingController@";
	
	Route::get('site_setting',			  			['as' =>$route_slug.'site_setting', 'uses' => $module_controller.'index' ,'middleware' => 'module_access_permission']);
	
	Route::post('site_setting/update',	  			['as' =>$route_slug.'site_setting', 'uses' => $module_controller.'update','middleware' => 'submodule_access_permission']);

	Route::post('site_setting/update_social_links',	['as' =>$route_slug.'update_social_links', 'uses' => $module_controller.'update_social_links']);

	Route::post('site_setting/rental_settings',		['as' =>$route_slug.'rental_settings', 'uses' => $module_controller.'rental_settings']);

	Route::post('site_setting/update_bank_details',	['as' =>$route_slug.'update_bank_details', 'uses' => $module_controller.'update_bank_details']);
	

	$module_controller = "Admin\AccountSettingController@";

	Route::get('account_setting',		  ['as' =>$route_slug.'account_setting', 'uses' => $module_controller.'index']);

	Route::post('account_setting/update', ['as' =>$route_slug.'account_setting', 'uses' => $module_controller.'update']);

	Route::get('password/change',         ['as' =>$route_slug.'change_password', 'uses' => $module_controller.'change_password']);

	Route::post('password/update',		  ['as' =>$route_slug.'update_password', 'uses' => $module_controller.'update_password']);


	Route::group(array('prefix' => 'voters'), function ()
	{
		$route_slug = 'admin_';

		$module_controller = "Admin\UsersController@";

		Route::get('/',					['as' =>$route_slug.'index', 'uses' => $module_controller.'index', 'middleware' => 'module_access_permission']);

		Route::get('/create',		['as' =>$route_slug.'create', 'uses' => $module_controller.'create', 'middleware' => 'submodule_access_permission']);

		Route::post('/store',		['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

		Route::get('/load_data',		['as' =>$route_slug.'load_d_verifiedata', 'uses' => $module_controller.'load_data']);

		Route::get('/edit/{id}',	  	['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);
		
		Route::get('/view/{id}',    	['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

		Route::post('/multi_action',	['as' =>$route_slug.'users', 'uses' => $module_controller.'multi_action']);

		Route::any('/update/{id?}',['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);
		
		Route::get('/delete/{id}',		['as' =>$route_slug.'delete', 'uses' => $module_controller.'delete']);

		Route::get('/unblock/{id}',		['as' =>$route_slug.'unblock', 'uses' => $module_controller.'unblock']);

		Route::get('/block/{id}',		['as' =>$route_slug.'block', 'uses' => $module_controller.'block']);

		Route::post('get_cities',	 	['as'=>$route_slug.'get_cities', 'uses' => $module_controller.'get_cities']); 

		Route::post('get_list',	 	['as'=>$route_slug.'get_list', 'uses' => $module_controller.'get_list']); 

    	Route::post('get_villages',	 	['as'=>$route_slug.'get_villages','uses' => $module_controller.'get_villages']); 

    	Route::post('get_wards',	 	['as'=>$route_slug.'get_wards','uses' => $module_controller.'get_wards']);

    	Route::post('get_booths',	 	['as'=>$route_slug.'get_booths','uses' => $module_controller.'get_booths']);

    	Route::post('get_list',	 	['as'=>$route_slug.'get_list','uses' => $module_controller.'get_list']);

    	Route::any('get_location/{id}',	 	['as'=>$route_slug.'get_location','uses' => $module_controller.'get_location']);

    	Route::post('send_newsletter',	 	['as'=>$route_slug.'send_newsletter', 'uses' => $module_controller.'send_newsletter']); 

	});


//********************* Accounting Department ******************


Route::group(array('prefix' => 'finance_team'), function () 
	 {
	 	$route_slug = 'admin_';
      	$module_controller = "Admin\FinanceTeamController@";
	 	Route::get('/',				['as' =>$route_slug.'create', 'uses' => 'Admin\FinanceTeamController@index']);

	 	Route::get('/create',		['as' =>$route_slug.'create', 'uses' => $module_controller.'create', 'middleware' => 'submodule_access_permission']);

	 	Route::post('/store',		['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

	 	Route::get('/load_data',	['as' =>$route_slug.'load_data', 'uses' => $module_controller.'load_data']);

	 	Route::get('/edit/{id?}',	['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);

	 	Route::any('/update/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

	 	Route::get('/view/{id}',	['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

		Route::get('/delete/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'delete']);

	 	Route::get('/block/{id?}',	['as' =>$route_slug.'categories', 'uses' => $module_controller.'block']);

		Route::get('/unblock/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'unblock']);
		
	 	Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);

	 	Route::any('get_cities',	 	['as'=>$route_slug.'get_cities', 'uses' => $module_controller.'get_cities']);

	 	Route::any('get_villages',	 	['as'=>$route_slug.'get_villages', 'uses' => $module_controller.'get_villages']);
	 });


//********************* Money Distribution

Route::group(array('prefix' => 'money_distribution'), function () 
	 {
	 	$route_slug = 'admin_';
      	$module_controller = "Admin\MoneyDistributionController@";
	 	Route::get('/',				['as' =>$route_slug.'create', 'uses' => $module_controller.'index']);

	 	Route::get('/create',		['as' =>$route_slug.'create', 'uses' => $module_controller.'create', 'middleware' => 'submodule_access_permission']);

	 	Route::post('/store',		['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

	 	Route::get('/load_data',	['as' =>$route_slug.'load_data', 'uses' => $module_controller.'load_data']);

	 	Route::get('/load_history',	['as' =>$route_slug.'load_data', 'uses' => $module_controller.'load_data']);

	 	Route::get('/edit/{id?}',	['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);

	 	Route::any('/update/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

	 	Route::get('/view/{id}',	['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

	 	Route::get('/history/{id}', ['as' =>$route_slug.'view', 'uses' => $module_controller.'history']);

	 	Route::get('/index_history',['as' =>$route_slug.'view', 'uses' => $module_controller.'index_history']);

		Route::get('/delete/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'delete']);

	 	Route::get('/block/{id?}',	['as' =>$route_slug.'categories', 'uses' => $module_controller.'block']);

		Route::get('/unblock/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'unblock']);
		
	 	Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);

	 	Route::any('get_cities',	 	['as'=>$route_slug.'get_cities', 'uses' => $module_controller.'get_cities']);

	 	Route::any('get_villages',	 	['as'=>$route_slug.'get_villages', 'uses' => $module_controller.'get_villages']);
	 });




Route::group(array('prefix' => 'voter_money_distribution'), function () 
	{
	 	$route_slug = 'admin_';
      	$module_controller = "Admin\VoterMoneyDistributionController@";
	 	Route::get('/',				['as' =>$route_slug.'create', 'uses' => $module_controller.'index']);

	 	Route::get('/create',		['as' =>$route_slug.'create', 'uses' => $module_controller.'create', 'middleware' => 'submodule_access_permission']);

	 	Route::post('/store',		['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

	 	Route::get('/load_data',	['as' =>$route_slug.'load_data', 'uses' => $module_controller.'load_data']);


	 	Route::get('/view/{id}',	['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);


		Route::get('/delete/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'delete']);

	 	Route::get('/block/{id?}',	['as' =>$route_slug.'categories', 'uses' => $module_controller.'block']);

		Route::get('/unblock/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'unblock']);
		
	 	Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);

	 	Route::any('get_cities',	 ['as'=>$route_slug.'get_cities', 'uses' => $module_controller.'get_cities']);

	 	Route::any('get_villages',	 ['as'=>$route_slug.'get_villages', 'uses' => $module_controller.'get_villages']);
 	});




//***************************** User Role Route Start ****************

Route::group(array('prefix' => 'user_role'), function () 
	 {
	 	$route_slug = 'admin_';
      	$module_controller = "Admin\RolesController@";

	 	Route::get('/',				['as' =>$route_slug.'create', 'uses' => $module_controller.'index']);

	 	Route::get('/create',		['as' =>$route_slug.'create', 'uses' => $module_controller.'create', 'middleware' => 'submodule_access_permission']);

	 	Route::post('/store',		['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

	 	Route::get('/load_data',	['as' =>$route_slug.'load_data', 'uses' => $module_controller.'load_data']);

	 	Route::get('/edit/{id?}',	['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);

	 	Route::any('/update/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

	 	Route::get('/view/{id}',	['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

		Route::get('/delete/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'delete']);

	 	Route::get('/block/{id?}',	['as' =>$route_slug.'categories', 'uses' => $module_controller.'block']);

		Route::get('/unblock/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'unblock']);
		
	 	Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);

	 });
//***************************** User Role Route End ****************



//*******************Meetings Route *****************************

	Route::group(array('prefix' => 'meetings'), function ()
	{
		$route_slug = 'admin_';

		$module_controller = "Admin\MeetingsController@";

		Route::get('/',					['as' =>$route_slug.'index', 'uses' => $module_controller.'index', 'middleware' => 'module_access_permission']);

		Route::get('/create',		['as' =>$route_slug.'create', 'uses' => $module_controller.'create', 'middleware' => 'submodule_access_permission']);

		Route::post('/store',		['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

		Route::get('/load_data',    ['as' =>$route_slug.'load_d_verifiedata', 'uses' => $module_controller.'load_data']);

		Route::get('/edit/{id}',	  	['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);

		Route::post('/update/{id?}',['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

		Route::get('/view/{id}',    	['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

		Route::get('/delete/{id}',    	['as' =>$route_slug.'view', 'uses' => $module_controller.'delete']);

		Route::get('/unblock/{id}',	['as' =>$route_slug.'unblock', 'uses' => $module_controller.'unblock']);

		Route::get('/block/{id}',	['as' =>$route_slug.'block', 'uses' => $module_controller.'block']);

		Route::post('/multi_action',['as' =>$route_slug.'users', 'uses' => $module_controller.'multi_action']);

		

	});

	//******************Meetings Route Ended************************


//***********************************Publicity Route Start******************



   Route::group(array('prefix' => 'publicity'), function () 
   {
    $route_slug = 'admin_';
    $module_controller = "Admin\PublicityController@";

    Route::get('/',       ['as' =>$route_slug.'create', 'uses' => $module_controller.'index']);

    Route::get('/create',   ['as' =>$route_slug.'create', 'uses' => $module_controller.'create']);

    Route::post('/store',   ['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

    Route::get('/load_data',  ['as' =>$route_slug.'load_data', 'uses' => $module_controller.'load_data']);

    Route::get('/edit/{id?}', ['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);

    Route::any('/update/{id?}',['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

    Route::get('/view/{id}',  ['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

    Route::get('/delete/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'delete']);

    Route::get('/block/{id?}',  ['as' =>$route_slug.'categories', 'uses' => $module_controller.'block']);

    Route::get('/unblock/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'unblock']);
    
    Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);

   });
//***********************************Publicity Route End******************




//****************** Wards Route Start******************


Route::group(array('prefix' => 'wards'), function () 
   {
    $route_slug = 'admin_';
    $module_controller = "Admin\WardsController@";

    Route::get('/',       ['as' =>$route_slug.'create', 'uses' => $module_controller.'index']);

    Route::get('/create',   ['as' =>$route_slug.'create', 'uses' => $module_controller.'create']);

    Route::post('/store',   ['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

    Route::get('/load_data',  ['as' =>$route_slug.'load_data', 'uses' => $module_controller.'load_data']);

    Route::get('/edit/{id?}', ['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);

    Route::any('/update/{id?}',['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

    Route::get('/view/{id}',  ['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

    Route::get('/delete/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'delete']);

    Route::get('/block/{id?}',  ['as' =>$route_slug.'categories', 'uses' => $module_controller.'block']);

    Route::get('/unblock/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'unblock']);
    
    Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);

    Route::post('get_cities',   ['as'=>$route_slug.'get_cities', 'uses' => $module_controller.'get_cities']); 

      Route::post('get_villages',   ['as'=>$route_slug.'get_villages','uses' => $module_controller.'get_villages']); 

   });
//*********************	Wards Route Ended ***********************






	Route::group(array('prefix' => 'voting_booth'), function ()
	{
		$route_slug = 'admin_';

		$module_controller = "Admin\BoothController@";

		Route::get('/create',		['as' =>$route_slug.'create', 'uses' => $module_controller.'create', 'middleware' => 'submodule_access_permission']);


		Route::get('/create_list',	['as' =>$route_slug.'create_list', 'uses' => $module_controller.'create_list', 'middleware' => 'submodule_access_permission']);

		Route::post('/store',		['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);


		Route::post('/store_list',	['as' =>$route_slug.'store_list', 'uses' => $module_controller.'store_list']);


		Route::get('/',	['as' =>$route_slug.'index', 'uses' => $module_controller.'index']);


		Route::get('/manage_list',	['as' =>$route_slug.'manage_list', 'uses' => $module_controller.'manage_list']);


		Route::get('/view_list/{id}',	['as' =>$route_slug.'view_list', 'uses' => $module_controller.'view_list']);


		Route::get('/edit_list/{id}',['as' =>$route_slug.'edit_list','uses' => $module_controller.'edit_list']);


		Route::get('/index_list',	['as' =>$route_slug.'index_list', 'uses' => $module_controller.'index_list']);

		Route::get('/load_data',    ['as' =>$route_slug.'load_d_verifiedata', 'uses' => $module_controller.'load_data']);

		Route::get('/load_listdata',['as' =>$route_slug.'load_d_verifiedata', 'uses' => $module_controller.'load_listdata']);


		Route::get('/delete/{id}',	['as' =>$route_slug.'delete', 'uses' => $module_controller.'delete']);

		Route::get('/unblock/{id}',	['as' =>$route_slug.'unblock', 'uses' => $module_controller.'unblock']);

		Route::get('/block/{id}',	['as' =>$route_slug.'block', 'uses' => $module_controller.'block']);

		Route::get('/unblock_list/{id}',	['as' =>$route_slug.'unblock_list', 'uses' => $module_controller.'unblock_list']);

		Route::get('/block_list/{id}',	['as' =>$route_slug.'block_list', 'uses' => $module_controller.'block_list']);

		Route::post('/update_list/{id}',	['as' =>$route_slug.'update_list', 'uses' => $module_controller.'update_list']);



		Route::get('/edit/{id}',	['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);
		
		Route::get('/view/{id}',    ['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

		Route::post('/multi_action',['as' =>$route_slug.'users', 'uses' => $module_controller.'multi_action']);

		Route::any('/update/{id?}',['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

		Route::get('/delete_list/{id}',['as' =>$route_slug.'delete_list', 'uses' => $module_controller.'delete_list']);

		Route::post('get_cities',	 	['as'=>$route_slug.'get_cities', 'uses' => $module_controller.'get_cities']); 

		Route::post('get_list',	 	['as'=>$route_slug.'get_list', 'uses' => $module_controller.'get_list']); 

    	Route::post('get_villages',	 	['as'=>$route_slug.'get_villages','uses' => $module_controller.'get_villages']); 

    	Route::post('get_wards',	 	['as'=>$route_slug.'get_wards', 'uses' => $module_controller.'get_wards']);

    	Route::post('get_booths',	 	['as'=>$route_slug.'get_booths', 'uses' => $module_controller.'get_booths']);

	});

	Route::group(array('prefix' => 'my_team'), function () 
	{
		$route_slug = 'admin_';
		$module_controller = "Admin\MyteamController@";

		Route::get('/',				['as' =>$route_slug.'create', 'uses' => $module_controller.'index', 'middleware' => 'module_access_permission']);

		Route::get('/create',		['as' =>$route_slug.'create', 'uses' => $module_controller.'create', 'middleware' => 'submodule_access_permission']);

	
		Route::post('/store',		['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);


		Route::get('/load_data',	['as' =>$route_slug.'load_data', 'uses' => $module_controller.'load_data']);

		Route::get('/view/{id?}',	['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

		Route::get('/edit/{id?}',	['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);

		Route::get('/edit_permission/{id?}',['as' =>$route_slug.'edit_permission', 'uses' => $module_controller.'edit_permission']);

		Route::post('/update/{id?}',['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

		Route::post('/update_permissions/{id?}',['as' =>$route_slug.'update_permissions', 'uses' => $module_controller.'update_permissions']);

		Route::post('/permissions/{id?}', ['as' =>$route_slug.'permissions', 'uses' => $module_controller.'permissions']);

		Route::get('/block/{id?}',	['as' =>$route_slug.'categories', 'uses' => $module_controller.'block']);

		Route::get('/unblock/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'unblock']);

		Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);

		Route::get('/delete/{id?}',	['as' =>$route_slug.'delete', 'uses' => $module_controller.'delete']);


	});

	Route::group(array('prefix' => 'notification'), function() 
	{
		$route_slug = 'admin_';

		$module_controller = "Admin\NotificationController@";

		Route::get('/', ['as'=>$route_slug.'user', 'uses'=>$module_controller.'index', 'middleware' => 'module_access_permission']);

		Route::get('/load_data', ['as'=>$route_slug.'user', 'uses'=>$module_controller.'load_data']);

		Route::get('/delete/{id}',['as' =>$route_slug.'delete', 'uses' => $module_controller.'delete']);

		Route::post('/multi_action',['as' =>$route_slug.'services', 'uses' => $module_controller.'multi_action']);

		Route::post('/read',['as' =>$route_slug.'read', 'uses' => $module_controller.'read']);

		Route::post('/multi_action',  ['as' =>$route_slug.'multi_action', 'uses' => $module_controller.'multi_action']);

	});


	Route::group(array('prefix' => 'contact_enquiry'), function () 
	{
		$route_slug = 'admin_';

		$module_controller = "Admin\ContactEnquiryController@";		
		
		Route::get('/',['as' =>$route_slug.'index', 'uses' => $module_controller.'index']);

		Route::any('/load_data',      ['as' =>$route_slug.'load', 'uses' => $module_controller.'load_data']);

		Route::get('/view/{id}',	  ['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

		Route::get('/reply/{id}',	  ['as' =>$route_slug.'reply', 'uses' => $module_controller.'reply']);

		Route::get('/delete/{id}',	  ['as' =>$route_slug.'delete', 'uses' => $module_controller.'delete']);

		Route::post('/send_reply/{enc_id}',  ['as' =>$route_slug.'send_reply', 'uses' => $module_controller.'send_reply']);

		Route::post('/multi_action',  ['as' =>$route_slug.'multi_action', 'uses' => $module_controller.'multi_action']);

	});

	Route::group(array('prefix' => 'email_template'), function () 
	{
		$route_slug = 'admin_';

		$module_controller = "Admin\EmailTemplateController@";		

		Route::get('/',				  ['as' =>$route_slug.'index', 'uses' => $module_controller.'index', 'middleware' => 'module_access_permission']);

		Route::get('/load_data',	  ['as' =>$route_slug.'load', 'uses' => $module_controller.'load_data']);

		Route::post('/store',		  ['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

		Route::get('/edit/{id}',	  ['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);

		Route::post('/update/{id}',	  ['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

		Route::post('/preview',	      ['as' =>$route_slug.'preview', 'uses' => $module_controller.'preview']);

		Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);


	});

	// Route::group(array('prefix' => 'blogs'), function () 
	// {
	// 	$route_slug = 'admin_';
	// 	$module_controller = "Admin\BlogsController@";

	// 	Route::get('/',				['as' =>$route_slug.'create', 'uses' => $module_controller.'index']);

	// 	Route::get('/create',		['as' =>$route_slug.'create', 'uses' => $module_controller.'create']);

	// 	Route::post('/store',		['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

	// 	Route::get('/load_data',	['as' =>$route_slug.'load_data', 'uses' => $module_controller.'load_data']);

	// 	Route::get('/edit/{id?}',	['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);

	// 	Route::any('/update/{id?}',['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

	// 	Route::get('/view/{id}',	['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

	// 	Route::get('/delete/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'delete']);

	// 	Route::get('/block/{id?}',	['as' =>$route_slug.'categories', 'uses' => $module_controller.'block']);

	// 	Route::get('/unblock/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'unblock']);
		
	// 	Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);

	// });
	// Route::group(array('prefix' => 'roles_and_permissions'), function () 
	// {
	// 	$route_slug = 'admin_';
	// 	$module_controller = "Admin\Roles_and_PermissionController@";

	// 	Route::get('/',				['as' =>$route_slug.'create', 'uses' => $module_controller.'index']);

	// 	Route::get('/create',		['as' =>$route_slug.'create', 'uses' => $module_controller.'create']);

	// 	Route::post('/store',		['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

	// 	Route::get('/load_data',	['as' =>$route_slug.'load_data', 'uses' => $module_controller.'load_data']);

	// 	Route::get('/edit/{id?}',	['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);

	// 	Route::any('/update/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

	// 	Route::get('/view/{id}',	['as' =>$route_slug.'view', 'uses' => $module_controller.'view']);

	// 	Route::get('/delete/{id?}', ['as' =>$route_slug.'update', 'uses' => $module_controller.'delete']);

	// 	Route::get('/block/{id?}',	['as' =>$route_slug.'categories', 'uses' => $module_controller.'block']);

	// 	Route::get('/unblock/{id?}',['as' =>$route_slug.'categories', 'uses' => $module_controller.'unblock']);
		
	// 	Route::post('/multi_action',['as' =>$route_slug.'categories', 'uses' => $module_controller.'multi_action']);

	// });

	// Route::group(array('prefix' => 'newsletters'), function ()
	// {
	// 	$route_slug = 'admin_';
		
	// 	$module_controller = "Admin\NewsletterController@";

	// 	Route::get('/create',		    	['as' => $route_slug.'create', 'uses' => $module_controller.'create']);

	// 	Route::post('/store',		    	['as' => $route_slug.'store', 'uses' => $module_controller.'store']);

	// 	Route::get('/edit/{id}',			['as' => $route_slug.'edit', 'uses' => $module_controller.'edit']);

	// 	Route::post('/update/{id}',			['as' => $route_slug.'update', 'uses' => $module_controller.'update']);

	// 	Route::get('/',						['as' => $route_slug.'newsletter_template', 'uses' => $module_controller.'newsletter_template']);

	// 	Route::get('/load_template_data',	['as' => $route_slug.'load_template_data', 'uses' => $module_controller.'load_template_data']);

	// 	Route::get('/activate/{id}',  		['as' => $route_slug.'activate', 'uses' => $module_controller.'activate']);

	// 	Route::get('/deactivate/{id}',		['as' => $route_slug.'deactivate', 'uses' => $module_controller.'deactivate']);

	// 	Route::get('/delete/{id}',	  		['as' => $route_slug.'delete', 'uses' => $module_controller.'delete']);
		
	// 	Route::post('/multi_action',  		['as' => $route_slug.'multi_action', 'uses' => $module_controller.'multi_action']);

	// 	Route::get('/send_newsletter',		['as' => $route_slug.'index', 'uses' => $module_controller.'index']);

	// 	Route::get('/load_data', 			['as' => $route_slug.'load_data', 'uses' => $module_controller.'load_data']);

	// 	Route::post('/send',  				['as' => $route_slug.'send_email', 'uses' => $module_controller.'send_email']);
	// });

	/*Route::group(array('prefix' => 'newsletters'), function ()
	{
		$route_slug = 'admin_';
		
		$module_controller = "Admin\NewsletterController@";
		
		Route::get('/', 						['as' =>$route_slug.'index', 'uses' => $module_controller.'index']);

		Route::get('/load_data', 				['as' =>$route_slug.'load_data', 'uses' => $module_controller.'load_data']);

		Route::get('/template/create',		    ['as' =>$route_slug.'create', 'uses' => $module_controller.'create']);

		Route::post('/template/store',		    ['as' =>$route_slug.'store', 'uses' => $module_controller.'store']);

		Route::get('/template/edit/{id}',		['as' =>$route_slug.'edit', 'uses' => $module_controller.'edit']);

		Route::post('/template/update/{id}',	['as' =>$route_slug.'update', 'uses' => $module_controller.'update']);

		Route::get('/template',					['as' =>$route_slug.'newsletter_template', 'uses' => $module_controller.'newsletter_template']);

		Route::get('/load_template_data',	    ['as' =>$route_slug.'load_template_data', 'uses' => $module_controller.'load_template_data']);

		Route::get('/activate/{id}',  			['as' =>$route_slug.'activate', 'uses' => $module_controller.'activate']);

		Route::get('/deactivate/{id}',			['as' =>$route_slug.'deactivate', 'uses' => $module_controller.'deactivate']);

		Route::get('/delete/{id}',	  			['as' =>$route_slug.'delete', 'uses' => $module_controller.'delete']);
		
		Route::post('/multi_action',  			['as' =>$route_slug.'multi_action', 'uses' => $module_controller.'multi_action']);
		Route::post('/send',  					['as' =>$route_slug.'send_email', 'uses' => $module_controller.'send_email']);
	});*/

});