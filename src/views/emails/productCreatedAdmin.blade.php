@extends('webshoppack::mail')
@section('email_content')
<div style="padding-bottom:25px; font:normal 14px Arial, Helvetica, sans-serif; color:#333;">Hi Admin,</div>

<div style="padding-bottom:23px; line-height:18px;">

    @if($product_details['product_status'] == 'Ok')
    	<p style="margin:0; padding:0 0 12px 0;">New product has been posted.</p>
        <p style="margin:0; padding:0 0 12px 0; font:normal 14px Arial, Helvetica, sans-serif; color:#4e5253;">User has been posting product titled <a href="{{ $product_details['view_url'] }}" style="color:#00a1b1; font:bold 13px Arial; text-decoration:none;">"{{ $product_details['product_name'] }}"</a>.</p>

    @elseif($product_details['product_status'] == 'ToActivate')
    	<p style="margin:0; padding:0 0 12px 0;">A product has been submitted for approval.</p>
        <p style="margin:0; padding:0 0 18px 0; color:#858889; font:bold 14px Arial, Helvetica, sans-serif;">Please review the product details and publish it.</p>

	@elseif($product_details['product_status'] == 'NotApproved')
        <p style="margin:0; padding:0 0 12px 0; font:bold 14px Arial, Helvetica, sans-serif; color:#858889;">A product has been disapproved.</p>
    @endif

    <div style="margin-bottom:25px; line-height:18px; padding:10px 20px; background:#fafafa; border:1px solid #eaeaea; border-radius:3px;">
        <p style="margin:10px 0 15px 0; padding:0; font:bold 14px Arial, Helvetica, sans-serif; color:#333;">Seller Details</p>
        <table width="98%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Name:</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
                <a style="text-decoration:none; color:#1386bf" href="#">{{ $user_details['display_name'] }}</a></p></td>
            </tr>

            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Email:</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">{{ $user_details['email'] }}</p></td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom:25px; line-height:18px; padding:10px 20px; background:#fafafa; border:1px solid #eaeaea; border-radius:3px;">
        <p style="margin:10px 0 15px 0; padding:0; font:bold 14px Arial, Helvetica, sans-serif; color:#333;">Product Details</p>
        <table width="98%" cellspacing="0" cellpadding="0" border="0">
        	<tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Code :</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
                	<a style="text-decoration:none; color:#1386bf" href="{{ $product_details['view_url'] }}">{{ $product_details['product_code'] }}</a></p></td>
            </tr>

            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">URL slug :</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
                	<a style="text-decoration:none; color:#1386bf" href="{{ $product_details['view_url'] }}">{{ $product_details['url_slug'] }}</a></p></td>
            </tr>

            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Status :</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
                	{{ $product_details['product_status_lang'] }}</p></td>
            </tr>

            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Product name :</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
                	<a style="text-decoration:none; color:#1386bf" href="{{ $product_details['view_url'] }}">{{{ $product_details['product_name'] }}}</a></p></td>
            </tr>

            <tr>
                <td width="100" valign="top" align="left">
                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Is free product :</p></td>
                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
                	{{ $product_details['is_free_product'] }}</p></td>
            </tr>
            @if($product_details['is_free_product'] == 'No')
                @if($product_details['product_price'] != '')
		            <tr>
		                <td width="100" valign="top" align="left">
		                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Product price :</p></td>
		                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
		                	{{ $product_details['product_price_currency'] }} {{ $product_details['product_price'] }}</p></td>
		            </tr>
		        @endif
		        @if($product_details['product_discount_price'] > 0)
		            <tr>
		                <td width="100" valign="top" align="left">
		                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Product discount price :</p></td>
		                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
		                	{{ $product_details['product_price_currency'] }} {{ $product_details['product_discount_price'] }}</p></td>
		            </tr>
		        @endif
		    @endif

            @if($product_details['product_highlight_text'] != '')
	            <tr>
	                <td width="100" valign="top" align="left">
	                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Summary :</p></td>
	                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
	                	{{  nl2br(htmlspecialchars($product_details['product_highlight_text'])) }}</p></td>
	            </tr>
	        @endif

             @if($product_details['demo_url'] != '')
	            <tr>
	                <td width="100" valign="top" align="left">
	                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Demo URL :</p></td>
	                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
	                	{{ $product_details['demo_url'] }}</p></td>
	            </tr>
	        @endif

	        @if($product_details['product_notes'] != '')
	            <tr>
	                <td width="100" valign="top" align="left">
	                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Product notes :</p></td>
	                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
	                	{{  nl2br(htmlspecialchars($product_details['product_notes'])) }}</p></td>
	            </tr>
	        @endif

            @if($product_details['product_tags'] != '')
				<tr>
	                <td width="100" valign="top" align="left">
	                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Tags :</p></td>
	                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
	                	{{{ $product_details['product_tags'] }}}</p></td>
	            </tr>
            @endif

            @if($product_details['category'] != '')
				<tr>
	                <td width="100" valign="top" align="left">
	                	<p style="padding:0; margin:0 0 15px 0; color:#8c8c8c; font:bold 12px Arial, Helvetica, sans-serif;">Category :</p></td>
	                <td valign="top" align="left"><p style="padding:0;margin:0 0 15px 0;color:#1a1a1a; font:normal 12px Arial, Helvetica, sans-serif;">
	                	{{{ $product_details['category'] }}}</p></td>
	            </tr>
            @endif

        </table>
    </div>
</div>

<p style="padding-bottom:5px; margin:0; font:normal 12px Arial, Helvetica, sans-serif; color:#333;">Regards</p>
<span style="text-transform:capitalize; margin:0; font:bold 13px Arial, Helvetica, sans-serif; color:#353535;">The {{ \Config::get('webshoppack::package_name') }} Team</span>
@stop


