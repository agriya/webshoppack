@extends(Config::get('webshoppack::mail_view'))
@section('email_content')
<div style="padding-bottom:25px; font:normal 13px Arial, Helvetica, sans-serif; color:#333;">Hi {{ $to_user_details['display_name'] }},</div>

<div style="padding-bottom:23px; line-height:18px;">
    <div style="margin:0; padding:0 0 12px 0;">A message has been posted to you from
        <a style="color:#00a1b1; font:bold 13px Arial; text-decoration:none;" href="#">{{ $from_user_details['display_name'] }}</a>.
    </div>
</div>

<p style="padding-bottom:5px; margin:0; font:normal 12px Arial, Helvetica, sans-serif; color:#333;">Regards</p>
<span style="text-transform:capitalize; margin:0; font:bold 13px Arial, Helvetica, sans-serif; color:#353535;">The {{ Config::get('site.site_name') }} Team</span>
@stop


