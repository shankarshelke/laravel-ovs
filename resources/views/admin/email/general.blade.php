<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>:: Voter Management ::</title>
</head>
<body style="background:#f5f6f8; margin:0px; padding:0px; font-size:12px; font-family:Arial, Helvetica, sans-serif; line-height:21px; color:#666; text-align:justify;">
    <div style="max-width:630px;width:100%;margin:0 auto;">
        <div style="padding:0px 15px;">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                       <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                               <td style="padding:42px 0 8px 0; color: #858585; font-size:13px; letter-spacing: 1px;"><span style="font-size:11px;color#858585;margin-top:2px;vertical-align:top;">@</span>{{ $arr_global_site_setting['site_name'] or '' }}</td>
                               <td style="padding:42px 0 8px 0;"><div style="float:right; padding-right:15px;"> 
                               {{-- <img src="{{ url('/').'/'.config('app.project.img_path.business_logo') }}" alt="link" style="vertical-align:middle;margin:-1px 2px 0 0;"/> --}}<a href="{{ url('/') }}" style="text-decoration:none; color: #88ba7b; font-size:13px;" > visit website </a> </div></td>
                           </tr>
                       </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#FFFFFF" style="padding:0px;box-shadow:0 0 12px #e8e8e8;-webkit-box-shadow:0 0 12px #e8e8e8;-moz-box-shadow:0 0 12px #e8e8e8;border-radius:4px 4px 0 0;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="background:#e2e2e2;height: 115px; text-align:center;border-radius:4px 4px 0 0;">
                                            <a href="#" style="line-height:101px; text-decoration:none; letter-spacing: 3px; color:#fff;font-size:22px; font-weight:200;display: block;margin: 10px;" >
                                                <img src="{{ url('/').config('app.project.img_path.business_logo') }}" alt="logo" />
                                                {{-- <img src="https://192.168.1.63/voter_management/assets/logo.png" alt="logo" /> --}}
                                                
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr><td height="40"></td></tr>
                        <tr>
                            <td style="color: #545454 !important;font-size: 15px;padding: 12px 30px;">
                                {!! $content or '' !!}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:center; color:#8aba7d; font-size:13px;">
                            {{ isset($arr_global_site_setting['site_email_address']) ? $arr_global_site_setting['site_email_address']:'' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:center; color:#7a7a7a; font-size:14px; padding:30px 0;">Get in touch if you have any question regarding our new project. Feel free<br/> to contect us 24/7. We are here to help.</td>
                        </tr>
                        <tr>
                            <td style="text-align:center; color:#7a7a7a; font-size:14px; padding-bottom:20px;"> All the best, <br/> @ {{ $arr_global_site_setting['site_name'] or '' }}</td>
                        </tr>                        
                     </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="font-size:12px; color:#a2a2a3; padding:25px 0 30px;">You are receving this newsletter because you subscribed<br/> to our mailing list via:{{ url('/') }}</td> 
                                <td style="padding-top:7px;text-align:right;"><a href="{{ $arr_global_site_setting['fb_url'] or '' }}" target="_blank"> <img src="{{ url('/') }}/assets/emailer-fb.jpg" alt="fb-icon" /> </a></td>
                                <td style="padding-top:7px;text-align:right;"><a href="{{ $arr_global_site_setting['twitter_url'] or ''}}" target="_blank"> <img src="{{ url('/') }}/assets/emailer-tw.jpg" alt="fb-icon" /> </a></td>
                                <td style="padding-top:7px;text-align:right;"><a href="{{ $arr_global_site_setting['gmail_url'] or ''}}" target="_blank"> <img src="{{ url('/') }}/assets/emailer-google.jpg" alt="fb-icon" /> </a></td>
                            </tr>
                            <tr style="height:100px;"></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>