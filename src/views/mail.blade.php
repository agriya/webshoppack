<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<div style="background:#fff; padding:33px;">
                <table width="700" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td bgcolor="#fff" style="background:#fff;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="69">
                                <tr>
                                    <td style="padding:14px 14px 14px 3px; width:146px; height:31px; background:#fff;" align="left" valign="middle">
                                        <a href="{{ url('/') }}" style="border:0; color:#fff; font-size:18px; text-decoration:none;">
                                        <img style="border:0" width="140" height="39" src="{{ URL::asset('/images/mails/logo.png') }}" /></a>
                                    </td>
									<td style="padding:8px 0 8px 8px;" valign="top" align="right">
										<table border="0" cellpadding="0" cellspacing="0" style="margin:15px 3px 0 8px;">
											<tr>
												<td align="left" valign="middle" height="26">
													<p style="background:#dbdbdb; border:1px solid #d9d9d9; box-shadow:1px 1px 0px #8c8c8c; -moz-box-shadow:1px 1px 0px #8c8c8c; -webkit-box-shadow:1px 1px 0px #8c8c8c; margin:0; padding:5px;">
													<a href="{{ url('/users/login') }}" style="font:bold 11px 'Arial'; color:#383838; text-decoration:none; padding:0 7px">Login</a>
													</p>
												</td>
											</tr>
										</table>
									</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
				<table width="700" cellspacing="0" cellpadding="0" bgcolor="#fff" style="border:1px solid #d9d9d9; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; box-shadow:0px 0px 2px 2px #f2f2f2; -moz-box-shadow:0px 0px 2px 2px #f2f2f2; -webkit-box-shadow:0px 0px 2px 2px #f2f2f2;">
					<tr>
						<td bgcolor="#fff" style="background:#fff;">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td style="padding:30px; font:14px 'Arial'; color:#383838;">
										@yield('email_content')
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td bgcolor="#fff" style="height:63px; background:#fff;">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="63">
								<tr>
									<td style="color:#383838; text-align:center; font:normal 11px 'Arial'; padding:0 10px;">
										<p style="margin:0; padding:17px 2px 4px 2px; border-top:1px solid #c6c6c6;">For any help you can contact our customer support at <a href="mailto:support{{ '@'.Config::get('site.site_name') }}.com" style="color:#00a1b1; font-weight:bold; text-decoration:none;">support{{ '@'.Config::get('site.site_name') }}.com</a>
										</p>
										<p style="margin:0; padding:2px 2px 20px;">Be sure to add
											<a href="mailto:noreply@test.com" style="color:#00a1b1; font-weight:bold; text-decoration:none;">noreply{{ '@'.Config::get('site.site_name') }}.com</a>
											to your address book or safe sender list so our emails get to your inbox.
										</p>
										<p style="margin:0; padding:2px 2px 10px;">&copy;2013
										  <a href="#" style="color:#383838; font-weight:bold; text-decoration:none;">{{ Config::get('site.site_name') }} Inc.</a>
											All rights reserved.
										</p>
										<p style="margin:0 auto; padding:0 0 15px 0; width:115px;"><span style="float:left;">Follow us on</span><a style="margin:0 7px; text-decoration:none; width:16px; height:16px;overflow:hidden; display:inline-block;" href="#" target="_blank"><img style="border:0" width="16" height="16" src="{{ URL::asset('/images/mails/twitter.png') }}" alt="twitter" /></a><a style="text-decoration:none; width:16px; height:16px;overflow:hidden;  text-decoration:none; display:inline-block;" href="#" target="_blank"><img style="border:0" width="16" height="16" src="{{ URL::asset('/images/mails/facebook.png') }}" alt="Facebook" /></a></p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
