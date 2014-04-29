@extends('webshoppack::mail')
@section('email_content')
<div style="padding-bottom:25px; font:normal 14px Arial, Helvetica, sans-serif; color:#333;">Hi {{ $display_name }},</div>

<div style="padding-bottom:23px; line-height:18px;">
	@if($product_status == 'Ok')
        <p style="margin:0; padding:0 0 12px 0; font:normal 14px Arial, Helvetica, sans-serif; color:#4e5253;">Thank you for posting your product titled <a href="{{ $view_url }}" style="color:#00a1b1; font:bold 13px Arial; text-decoration:none;">"{{ $product_name }}"</a>.</p>

	@elseif($product_status == 'ToActivate')
        <p style="margin:0; padding:0 0 12px 0; font:normal 14px Arial, Helvetica, sans-serif; color:#4e5253;">Thank you for submission your product titled <a href="{{ $view_url }}" style="color:#00a1b1; font:bold 13px Arial; text-decoration:none;">"{{ $product_name }}"</a>.</p>

		<p style="margin:0; padding:0 0 12px 0; font:bold 14px Arial, Helvetica, sans-serif; color:#858889;">We will review the product and notify through mail when approved and published.</p>

	@elseif($product_status == 'NotApproved')
        <p style="margin:0; padding:0 0 12px 0; font:bold 14px Arial, Helvetica, sans-serif; color:#858889;">Your product has been disapproved.</p>
    @endif

    <div style="margin-bottom:25px; line-height:18px; padding:10px 20px; background:#fafafa; border:1px solid #eaeaea; border-radius:3px;">
        <p style="margin:10px 0 15px 0; padding:0; font:bold 14px Arial, Helvetica, sans-serif; color:#333;">Product Details</p>
        <table width="98%" cellspacing="0" cellpadding="0" border="0">

            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Product name :</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
                	<a style="text-decoration:none; color:#1386bf" href="{{ $view_url }}">{{{ $product_name }}}</a></p></td>
            </tr>

            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Code :</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
                	<a style="text-decoration:none; color:#1386bf" href="{{ $view_url }}">{{ $product_code }}</a></p></td>
            </tr>

            @if($user_notes != '')
	            <tr>
	                <td width="100" valign="top" align="left">
	                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Notes :</p></td>
	                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
	                	{{  nl2br(htmlspecialchars($user_notes)) }}</p></td>
	            </tr>
	        @endif

        </table>
    </div>

</div>

<p style="padding-bottom:5px; margin:0; font:normal 12px Arial, Helvetica, sans-serif; color:#333;">Regards,</p>
<span style="text-transform:capitalize; margin:0; font:bold 13px Arial, Helvetica, sans-serif; color:#353535;">The {{ \Config::get('webshoppack::package_name') }} Team</span>
@stop
