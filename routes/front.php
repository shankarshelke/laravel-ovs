<?php
Route::get('/', function () {
    return view('errors.404');
});
$module_controller = "Front\CronController@";

Route::get('/daily_cron/',   				['as'=>'daily_cron', 'uses'=>$module_controller.'complete_bookings']);


Route::group(['middleware' => 'front_general'], function ()
{
	$module_controller = "Front\HomeController@";
	/*Route::get('/', 				['as'=>'home_page', 'uses'=>$module_controller.'index']);*/


	Route::any('/newsletter'   	,	['as'=> 'home_page','uses'=> $module_controller.'store_email']);

	$module_controller = "Front\FrontPagesController@";

	Route::get('/contact_us', 	 			['as'=>'contact_us', 'uses'=>$module_controller.'contact_us']);
	
	Route::get('/about_us', 	 			['as'=>'about_us', 'uses'=>$module_controller.'about_us']);

	Route::post('/process_contact_us', 	 	['as'=>'process_contact_us', 'uses'=>$module_controller.'process_contact_us']);

	Route::get('/terms_conditions', 	 	['as'=>'terms_conditions', 'uses'=>$module_controller.'terms_conditions']);

	Route::get('/privacy_policy', 	 		['as'=>'privacy_policy', 'uses'=>$module_controller.'privacy_policy']);

	Route::get('/guidelines', 			 	['as'=>'guidelines', 'uses'=>$module_controller.'guidelines']);

	Route::get('/investor_information', 	['as'=>'investor_information', 'uses'=>$module_controller.'investor_information']);

	Route::get('/legal_terms', 				['as'=>'legal_terms', 'uses'=>$module_controller.'legal_terms']);

	Route::get('/site_map', 				['as'=>'site_map', 'uses'=>$module_controller.'site_map']);

	$module_controller = "Front\AircraftController@";
	
	Route::get('/listing', 					['as'=>'aircraft_listing', 'uses'=>$module_controller.'index']);

	Route::any('/details/ajax_more_review', ['as'=>'ajax_more_review', 'uses'=>$module_controller.'ajax_more_review']);

	Route::get('/details/{enc_id}', 		['as'=>'aircraft_details', 'uses'=>$module_controller.'details']);

	Route::get('/get_models_by_type/{type}',['as'=>'get_models', 'uses'=>$module_controller.'get_models_by_type']);

	Route::post('/review/{enc_id}', 		['as'=>'review', 'uses'=>$module_controller.'review']);

	Route::post('/request_quotation', 		['as'=>'request_quote', 'uses'=>$module_controller.'request_quotation']);

	
	$module_controller = "Front\AuthController@";

	Route::get('/sign_in', 					['as'=>'sign_in', 'uses'=>$module_controller.'process_login']);
	
	Route::post('/validate_login', 			['as'=>'validate-login', 'uses'=>$module_controller.'validate_login']);
	
	Route::post('/reset_password', 			['as'=>'reset_password', 'uses'=>$module_controller.'reset_password']);
	
	Route::get('/set_password/{enc_id}', 	['as'=>'set_password', 'uses'=>$module_controller.'set_password']);

	Route::post('/save_password/{enc_id}', 	['as'=>'save_password', 'uses'=>$module_controller.'save_password']);
	
	Route::get('/logout', 					['as'=>'logout', 'uses'=>$module_controller.'logout']);

	
	$module_controller = "Front\SignupController@";
	
	Route::get('/signup_operator', 			['as'=>'sign_up', 'uses'=>$module_controller.'signup_operator']);
	
	Route::get('/signup_user', 				['as'=>'signup_user', 'uses'=>$module_controller.'signup_user']);
	
	Route::post('/check_user_email',		['as'=>'check_user_email', 'uses'=>$module_controller.'check_user_email']);
	
	Route::post('/process_signup_user', 	['as'=>'process_signup_user', 'uses'=>$module_controller.'process_signup_user']);
	
	Route::post('/user_resend_resgistraion_mail',['as'=>'process_signup_user', 'uses'=>$module_controller.'user_resend_resgistraion_mail']);

	Route::any('/password/{enc_id}',		['as'=>'email_password', 'uses'=>$module_controller.'password']);
	
	Route::post('/email_save_password/{enc_id}',	['as'=>'email_password', 'uses'=>$module_controller.'email_save_password']);
	
	Route::post('/process_signup_operator', ['as'=>'process_signup_operator', 'uses'=>$module_controller.'process_signup_operator']);

	Route::post('/operator_resend_resgistraion_mail',['as'=>'process_signup_user', 'uses'=>$module_controller.'operator_resend_resgistraion_mail']);

	Route::any('/user_verify_account/{user_id}/{password}', ['as'=>'user_verify_account', 'uses'=>$module_controller.'user_verify_account']);
	
	Route::post('/check_email', 			 ['as'=>'check_email','uses'=>$module_controller.'check_email']);
	
	
	$module_controller = "Front\user\ProfileController@";
	
	Route::get('/edit_profile',      		 ['as'=>'edit_profile', 'uses'=>$module_controller.'edit_profile']);


	$module_controller = "Front\BlogController@";

	Route::get('/blogs', 	 ['as'=>'blogs', 'uses'=>$module_controller.'index']);

	Route::get('/blog_details/{enc_id}', 	 ['as'=>'blog_details', 'uses'=>$module_controller.'blog_details']);

	/* Routes For Logged in Operators */

	Route::group(array('prefix' => 'operator','middleware'=>'operator_auth_check'), function ()
	{
		$module_controller = "Front\operator\ProfileController@";

		Route::get('/',      				['as'=>'profile', 'uses'=>$module_controller.'profile']);

		Route::get('/profile',      		['as'=>'profile', 'uses'=>$module_controller.'profile']);
		
		Route::post('/update_operator',     ['as'=>'update', 'uses'=>$module_controller.'update_operator']);
		
		Route::post('/save_password', 		['as'=>'save_password', 'uses'=>$module_controller.'save_password']);

		Route::post('/send_request', 		['as'=>'send_request', 'uses'=>$module_controller.'send_request']);
		
		Route::post('/newsletter',      		 ['as'=>'newsletter', 'uses'=>$module_controller.'newsletter']);


		Route::group(array('prefix' => 'aircrafts'), function (){

			$module_controller = "Front\operator\AircraftController@";

			Route::any('/',      			['as'=>'aircraft', 'uses'=>$module_controller.'index']);

			Route::get('/add',      		['as'=>'add', 'uses'=>$module_controller.'add']);

			Route::post('/store',     		['as'=>'store', 'uses'=>$module_controller.'store']);

			Route::get('/edit/{id}',		['as'=>'edit', 'uses'=>$module_controller.'edit']);
			
			Route::post('/update/{id}',		['as'=>'update', 'uses'=>$module_controller.'update']);

			Route::get('/search',   		['as'=>'search', 'uses'=>$module_controller.'search']);

			$module_controller = "Front\operator\AvailabilityController@";

			Route::get('/availability/add/{enc_id}',	['as'=>'add', 'uses'=>$module_controller.'add']);
			
			Route::post('/availability/store/{enc_id}',	['as'=>'store', 'uses'=>$module_controller.'store']);

			Route::get('/availability/{enc_id}',		['as'=>'listing', 'uses'=>$module_controller.'index']);

			Route::get('/availability/block/{id?}',	['as' =>'block', 'uses' => $module_controller.'block']);

			Route::get('/availability/unblock/{id?}',['as' =>'unblock', 'uses' => $module_controller.'unblock']);
			
			Route::post('/availability/multi_action',['as' =>'multi_action', 'uses' => $module_controller.'multi_action']);

		});


		$module_controller = "Front\operator\DashboardController@";

		Route::get('/dashboard',      				['as'=>'dashboard', 'uses'=>$module_controller.'dashboard']);

		Route::get('/notifications',      			['as'=>'notifications', 'uses'=>$module_controller.'notifications']);

		Route::get('/pending_bookings',     		['as'=>'pending_bookings', 'uses'=>$module_controller.'pending_bookings']);

		Route::get('/completed_bookings',     		['as'=>'completed_bookings', 'uses'=>$module_controller.'completed_bookings']);

		Route::get('/cancelled_bookings',     		['as'=>'cancelled_bookings', 'uses'=>$module_controller.'cancelled_bookings']);

		Route::get('/requested_quotations',     	['as'=>'requested_quotations', 'uses'=>$module_controller.'requested_quotations']);

		Route::post('/reviews/{res_id}/{enc_id}',   ['as'=>'reviews','uses'=>$module_controller.'reviews']);
		
		Route::get('/reviews_and_ratings',   		['as'=>'reviews_and_ratings', 'uses'=>$module_controller.'reviews_and_ratings']);

		Route::get('/bank_details',   				['as'=>'bank_details', 'uses'=>$module_controller.'bank_details']);

		Route::post('/update_bank_details',   		['as'=>'update_bank_details', 'uses'=>$module_controller.'update_bank_details']);
		
		Route::get('/search',   					['as'=>'search', 'uses'=>$module_controller.'search']);


		$module_controller = "Front\operator\BookingController@";

		Route::get('/view_contract/{enc_id}',   	['as'=>'contract', 'uses'=>$module_controller.'view_contract']);
		
		Route::get('/download_contract/{enc_id}',	['as'=>'download_contract', 'uses'=>$module_controller.'download_contract']);
		
		$module_controller = "Front\operator\TransactionController@";
		
		Route::get('/transactions',      			['as'=>'transactions', 'uses'=>$module_controller.'transactions']);

		Route::post('/request_payment/',      		['as'=>'request_payment', 'uses'=>$module_controller.'request_payment']);

		Route::get('/approve/{id}',      		['as'=>'approve', 'uses'=>$module_controller.'approve']);
		
		Route::get('/reject/{id}',      		['as'=>'reject', 'uses'=>$module_controller.'reject']);
		
	});

	/* Routes For Logged in users */
	
	Route::group(array('prefix' => 'user','middleware'=>'user_auth_check'), function ()
	{
		$module_controller = "Front\user\ProfileController@";
		
		Route::get('/profile',      				['as'=>'profile', 'uses'=>$module_controller.'profile']);
		
		Route::post('/update_user',      			['as'=>'update_user','uses'=>$module_controller.'update_user']);
		
		Route::post('/update_password', 			['as'=>'update_password', 'uses'=>$module_controller.'update_password']);

		Route::post('/send_request', 			['as'=>'send_request', 'uses'=>$module_controller.'send_request']);

		Route::post('/newsletter',      		 ['as'=>'newsletter', 'uses'=>$module_controller.'newsletter']);

		$module_controller = "Front\user\DashboardController@";

		Route::get('/dashboard',      				['as'=>'dashboard', 'uses'=>$module_controller.'dashboard']);

		Route::get('/notifications',      			['as'=>'notifications', 'uses'=>$module_controller.'notifications']);

		Route::get('/pending_bookings',     		['as'=>'pending_bookings', 'uses'=>$module_controller.'pending_bookings']);

		Route::get('/completed_bookings',     		['as'=>'completed_bookings', 'uses'=>$module_controller.'completed_bookings']);

		Route::get('/cancelled_bookings',     		['as'=>'cancelled_bookings', 'uses'=>$module_controller.'cancelled_bookings']);


		Route::get('/requested_quotations',     	['as'=>'requested_quotations', 'uses'=>$module_controller.'requested_quotations']);

		Route::post('/reviews/{res_id}/{enc_id}',   ['as'=>'reviews', 'uses'=>$module_controller.'reviews']);

		Route::get('/reviews_and_ratings',   		['as'=>'reviews_and_ratings', 'uses'=>$module_controller.'reviews_and_ratings']);

		Route::get('/bank_details',   				['as'=>'bank_details', 'uses'=>$module_controller.'bank_details']);

		Route::get('/accept_quotation/{enc_id}',   	['as'=>'accept_quotation', 'uses'=>$module_controller.'accept_quotation']);

		Route::get('/reject_quotation/{enc_id}',   	['as'=>'reject_quotation', 'uses'=>$module_controller.'reject_quotation']);

		Route::post('/update_bank_details',   		['as'=>'update_bank_details', 'uses'=>$module_controller.'update_bank_details']);

		Route::get('/search',   					['as'=>'search', 'uses'=>$module_controller.'search']);


		$module_controller = "Front\user\BookingController@";

		Route::get('/view_contract/{enc_id}',   	['as'=>'contract', 'uses'=>$module_controller.'view_contract']);

		Route::get('/extend_contract',     			['as'=>'extend_contract', 'uses'=>$module_controller.'extend_contract']);

		Route::post('/request_extend_contract/{id}',['as'=>'request_extend_contract', 'uses'=>$module_controller.'request_extend_contract']);

		Route::post('/extend_contract_payment',    	['as'=>'extend_contract_payment', 'uses'=>$module_controller.'extend_contract_payment']);


		Route::post('/submit_contract/{enc_id}',    ['as'=>'submit_contract', 'uses'=>$module_controller.'submit_contract']);

		Route::post('/submit_new_paysleep/{enc_id}',['as'=>'submit_new_paysleep', 'uses'=>$module_controller.'submit_new_paysleep']);

		Route::get('/cancel_book_req/{enc_id}',		['as'=>'cancel_book_req', 'uses'=>$module_controller.'cancel_book_req']);
		
		Route::get('/download_contract/{enc_id}',	['as'=>'download_contract', 'uses'=>$module_controller.'download_contract']);

		$module_controller = "Front\user\TransactionController@";
		
		Route::get('/transactions',      			['as'=>'transactions', 'uses'=>$module_controller.'transactions']);
		Route::post('/send_payment',      			['as'=>'send_payment', 'uses'=>$module_controller.'send_payment']);
		
		
		
	});

	/* Routes For Logged in Users */
});


?>
