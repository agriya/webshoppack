@extends(Config::get('webshoppack::mail_view'))
@section('email_content')
<div style="padding-bottom:25px; font:normal 13px Arial, Helvetica, sans-serif; color:#333;">Hi Admin,</div>

<div style="padding-bottom:23px; line-height:18px;">
    <p style="margin:0; padding:0 0 12px 0;">New message has been posted.

    <div style="margin-bottom:25px; line-height:18px; padding:10px 20px; background:#fafafa; border:1px solid #eaeaea; border-radius:3px;">
        <p style="margin:10px 0 15px 0; padding:0; font:bold 14px Arial, Helvetica, sans-serif; color:#333;">Details</p>
        <table width="98%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 10px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">From :</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
                <a style="color:#00a1b1; font:bold 12px Arial; text-decoration:none;" href="#">{{ $from_user_details['display_name'] }}</a> ({{ $from_user_details['email'] }})</p></td>
            </tr>
            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 10px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">To :</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
                <a style="color:#00a1b1; font:bold 12px Arial; text-decoration:none;" href="#">{{ $to_user_details['display_name'] }}</a> ({{ $to_user_details['email'] }})</p></td>
            </tr>
            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 10px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Subject:</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">{{{ $message_subject }}}</p></td>
            </tr>
            @if($message_text != "")
	            <tr>
	                <td width="100" valign="top" align="left">
	                	<p style="padding:0; margin:0 0 10px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Message:</p></td>
	                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">{{ nl2br(htmlspecialchars($message_text)) }}</p></td>
	            </tr>
	        @endif
            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 10px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Posted on:</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">{{ $date_posted }}</p></td>
            </tr>
        </table>
    </div>
</div>

<p style="padding-bottom:5px; margin:0; font:normal 12px Arial, Helvetica, sans-serif; color:#333;">Regards</p>
<span style="text-transform:capitalize; margin:0; font:bold 13px Arial, Helvetica, sans-serif; color:#353535;">The {{ Config::get('site.site_name') }} Team</span>
@stop


