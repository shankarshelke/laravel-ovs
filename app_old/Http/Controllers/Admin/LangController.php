<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use App\Common\Services\SmsService;

class LangController extends Controller
{
	public function __construct()
    {
        $this->SmsService = new SmsService();
    }

    public function index($lang)
    { 
        // dd(Config::get('languages'));
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('applocale', $lang);
            // dd(session('applocale'));
        }
        //return Redirect::to(url('/'));
        return Redirect::back();
    }

    public function change_currency($currency)
    {
		$arr_currency = config('app.project.currency');

		$key = array_search($currency, array_column($arr_currency, 'name'));

		if($key !== false)
		{
	        Session::put('currency_title', $arr_currency[$key]['title']);
	        Session::put('currency', $arr_currency[$key]['name']);
	        Session::put('currency_symbol', $arr_currency[$key]['symbol']);

	        $updated_currency = currency_conversion_api();
	        $arr_updated_currency = (array) $updated_currency->rates;

	        Session::put('dollar_value', $arr_updated_currency[$arr_currency[0]['name']]);
	        Session::put('euro_value', $arr_updated_currency[$arr_currency[1]['name']]);
		}

		return redirect()->back();
    }

    public function send_test_sms()
    {
    	if($this->SmsService->send_sms("Helloo, This is test message", '+91 95611 59777')){
    		dd("Message Sent!");
    	}else{
    		dd("Couldn't send message!");
    	}
    }
}