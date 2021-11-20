<?php

Route::group(['prefix'=>''], function()
{
	$module_controller = 'Api\AuthController@';
	$module_slug       = 'process_';

	Route::post('/login', 						['as'	=> $module_slug.'login',
												'uses'	=> $module_controller.'login']);
	Route::post('/register',					['as'	=> $module_slug.'register',
												'uses'	=> $module_controller.'register']);


	Route::post('/login_api/{email}/{pwd}', 	['as'	=> $module_slug.'login_api',
												'uses'	=> $module_controller.'login_api']);

	Route::get('/verify_user/{id}/',	 		['as'	=> $module_slug.'verify_user',
												'uses'	=> $module_controller.'verify_user']);
	
	Route::post('/forgot_password',				['as'	=> $module_slug.'forgot_password',
												'uses'	=> $module_controller.'forgot_password']);
	
	Route::get('/get_country',					['as'	=> $module_slug.'get_country',
												'uses'	=> $module_controller.'get_country']);


	Route::group(['prefix'=>'','middleware'=>'user_auth_check'], function()use($module_slug,$module_controller)
	{	
		$module_controller = 'Api\AuthController@';
		/*Route::post('/register',				    ['as'	=> $module_slug.'register',
													'uses'	=> $module_controller.'register']);
*/

		Route::post('/change_password', 			['as'	=> $module_slug.'change_password',
													'uses'	=> $module_controller.'change_password']);

		Route::get('/profile_details', 		    	['as'	=> $module_slug.'profile_details',
													'uses'	=> $module_controller.'profile_details']);

		Route::post('/update_profile', 		    	['as'	=> $module_slug.'update_profile',
													'uses'	=> $module_controller.'update_profile']);

		Route::get('/profile_details_voter', 		['as'	=> $module_slug.'profile_details_voter',
													'uses'	=> $module_controller.'profile_details_voter']);


		Route::any('/transfer_money_voter',     	['as'	=> $module_slug.'transfer_money_voter',
													'uses'	=> $module_controller.'transfer_money_voter']);

		Route::get('/view_voter_money_detail/{id}', ['as'	=> $module_slug.'view_voter_money_detail',
													'uses'	=> $module_controller.'view_voter_money_detail']);

		Route::any('/votermoney_listing', 		 	['as'	=> $module_slug.'votermoney_listing',
													'uses'	=> $module_controller.'votermoney_listing']);
		

		Route::post('/create', 		 			   ['as'	=> $module_slug.'create',
													'uses'	=> $module_controller.'create']);

		Route::any('/voter_listing', 		 		['as'	=> $module_slug.'voter_listing',
													'uses'	=> $module_controller.'voter_listing']);
		
		
		Route::post('/edit/{id}', 		 			['as'	=> $module_slug.'edit',
													'uses'	=> $module_controller.'edit']);
		Route::get('/view/{id}', 		 			['as'	=> $module_slug.'view',
													'uses'	=> $module_controller.'view']);
		Route::get('/delete/{id}', 		 			['as'	=> $module_slug.'delete',
													'uses'	=> $module_controller.'delete']);


        

		/*Route::any('/subadmin_distributed_money_to_voter/{id}', ['as'	=> $module_slug.'subadmin_distributed_money_to_voter',
													            'uses'	=> $module_controller.'subadmin_distributed_money_to_voter']);
*/

/*
		$module_controller = 'Api\VoterMoneyController@';
		Route::any('/transfer_money_voter',     	['as'	=> $module_slug.'transfer_money_voter',
													'uses'	=> $module_controller.'transfer_money_voter']);

		Route::get('/view_voter_money_detail/{id}', ['as'	=> $module_slug.'view_voter_money_detail',
													'uses'	=> $module_controller.'view_voter_money_detail']);




       */ /*Route::any('/subadmin_distributed_money_to_voter', ['as'	=> $module_slug.'subadmin_distributed_money_to_voter',
													            'uses'	=> $module_controller.'subadmin_distributed_money_to_voter']);
*/


/*
		$module_controller = 'Api\VoterController@';
		Route::post('/create', 		 			    ['as'	=> $module_slug.'create',
													'uses'	=> $module_controller.'create']);
		Route::post('/edit/{id}', 		 			['as'	=> $module_slug.'edit',
													'uses'	=> $module_controller.'edit']);
		Route::get('/view/{id}', 		 			['as'	=> $module_slug.'view',
													'uses'	=> $module_controller.'view']);
		Route::get('/delete/{id}', 		 			['as'	=> $module_slug.'delete',
													'uses'	=> $module_controller.'delete']);
*/
	});

});
