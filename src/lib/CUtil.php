<?php namespace Agriya\Webshoppack;
//Common Utils
class CUtil
{
	public static function getUserDetails($user_id)
	{
		$user_fields = \Config::get('webshoppack::user_fields');
		$user_id_field = \Config::get('webshoppack::user_id_field');
		$user_table = \Config::get('webshoppack::user_table');

		$user_details = array();
		$fields = '';
		foreach($user_fields AS $key => $field) {
			if($fields != '') {
				$fields .= ', ';			}
			$fields .= $field.' AS '.$key;
		}
		$user_info = \DB::select('SELECT '.$fields.' FROM '.$user_table.' WHERE '.$user_id_field.' = \''.$user_id.'\'');
		if(count($user_info) > 0) {
			foreach($user_info AS $user_det) {
				$user_details['user_code'] = CUtil::setUserCode($user_id);
				if(isset($user_det->fname)) {
					$user_details['first_name'] = $user_det->fname;
				}
				if(isset($user_det->lname)) {
					$user_details['last_name'] = $user_det->lname;
				}
				if(isset($user_det->email)) {
					$user_details['email'] = $user_det->email;
				}
				if(isset($user_det->fname) && isset($user_det->lname)) {
					$user_details['display_name'] = ucfirst($user_det->fname).' '.ucfirst(substr($user_det->lname, 0, 1));
				}
			}
		}
		$user_details['profile_url'] = \URL::to(\Config::get('webshopauthenticate::uri'))."/".$user_details['user_code'];//."-". strtolower(str_replace(" ","", $user_details['first_name']));
		return $user_details;
	}

	public static function generateRandomUniqueCode($prefix_code, $table_name, $field_name)
	{
		if($table_name == 'users')
			$unique_code = $prefix_code.mt_rand(10000000,99999999);
		else
			$unique_code = $prefix_code.mt_rand(100000,999999);
		$code_count = 	\DB::table($table_name)->whereRaw($field_name." = ? ", array($unique_code))->count();
		if($code_count > 0)
		{
			return CUtil::generateRandomUniqueCode($prefix_code, $table_name, $field_name);
		}
		else
		{
			return $unique_code;
		}
		return $unique_code;
	}

	public static function DISP_IMAGE($cfg_width = 0, $cfg_height = 0, $img_width = 0, $img_height = 0, $as_array = false)
	{
		$img_attrib = array('width'=>'', 'height'=>'');

		if ($cfg_width > 0 AND $cfg_height > 0 AND ($cfg_width < $img_width) AND ($cfg_height < $img_height))
			{
				$tmpHeight = ( $cfg_width / $img_width ) * $img_height;

				if( $tmpHeight <= $cfg_height )
					{
						$attr = " width=\"".$cfg_width."\"";
						$img_attrib['width'] = $cfg_width;
					}
				else
					{
						$height = $tmpHeight - ( $tmpHeight - $cfg_height );
						$attr = " height=\"".$height."\"";
						$img_attrib['height'] = $height;
					}
			}
		else if ($cfg_width > 0 AND $cfg_width < $img_width)
			{
				$attr = " width=\"".$cfg_width."\"";
				$img_attrib['width'] = $cfg_width;
			}
		else if ($cfg_height > 0 AND $cfg_height < $img_height)
			{
				$attr = " height=\"".$cfg_height."\"";
				$img_attrib['height'] = $cfg_height;
			}
		else
			{
				$attr = "";
			}

		if ($as_array)
			{
				return $img_attrib;
			}

		return $attr;
	}

	public static function TPL_DISP_IMAGE($cfg_width = 0, $cfg_height = 0, $img_width = 0, $img_height = 0)
	{
		$attr = "";
		if ($cfg_width > 0 && $cfg_height > 0 && ($cfg_width < $img_width) && ($cfg_height < $img_height))
		{
			$tmpHeight = ( $cfg_width / $img_width ) * $img_height;
			if( $tmpHeight <= $cfg_height )
			{
				$attr = " width=".$cfg_width;
			}
			else
			{
				$height = $tmpHeight - ( $tmpHeight - $cfg_height );
				$attr = " height=".$height;
			}
		}
		else if ($cfg_width > 0 && $cfg_width < $img_width)
		{
			$attr = " width=".$cfg_width;
		}
		else if ($cfg_height > 0 && $cfg_height < $img_height)
		{
			$attr = " height=".$cfg_height;
		}
		else
		{
			$attr = "";
		}
		return $attr;
	}

	public static function chkAndCreateFolder($folderName)
	{
		$folder_arr = explode('/', $folderName);
		$folderName = '';
		foreach($folder_arr as $key=>$value)
			{
				$folderName .= $value.'/';
				if($value == '..' or $value == '.')
					continue;
				if (!is_dir($folderName))
					{
						mkdir($folderName);
						@chmod($folderName, 0777);
					}
			}
	}

	public static function getBaseAmountToDisplay($price, $currency, $return_as_arr = false)
	{
		$currency_symbol = "USD";
		$currency_symbol_font = "$";

		$currency_details = CUtil::chkIsValidCurrency($currency);
		if(count($currency_details) > 0)
		{
			$currency_symbol = $currency_details["currency_code"];
			$currency_symbol_font = $currency_details["currency_symbol"];
			if($currency_symbol == "INR")
				$currency_symbol_font = "<em class=\"clsWebRupe\">".$currency_details["currency_symbol"]."</em>";
		}
		$formatted_amt = "";
		$formatted_amt = number_format ($price, 2, '.','');
		$formatted_amt = str_replace(".00", "", $formatted_amt);
		$formatted_amt = str_replace("Rs.", "", $formatted_amt);

		if($return_as_arr)
			return compact('currency_symbol','formatted_amt');
		else
			return "<small>".$currency_symbol. '</small> <strong>' . $formatted_amt.'</strong>';
	}

	/**
	 * CUtil::chkIsValidCurrency()
	 * added by periyasami_145at11
	 *
	 * @param mixed $currency_code
	 * @return
	 */
	public static function chkIsValidCurrency($currency_code)
	{
		$details = array();
		$selected_currency_code = CurrencyExchangeRate::whereRaw('currency_code= ? AND status = "Active" AND display_currency = "Yes" ', array($currency_code))->first();
		if(count($selected_currency_code))
		{
			$details['country'] = $selected_currency_code['country'];
			$details['currency_code'] = $selected_currency_code['currency_code'];
			$details['exchange_rate'] = $selected_currency_code['exchange_rate'];
			$details['currency_symbol'] = $selected_currency_code['currency_symbol'];
		}
		return $details;
	}

	public static function convertBaseCurrencyToUSD($amount, $base_currency = "", $exchange_rate_allow = false)
	{
		if($amount == "")
			$amount = "0";

		if(doubleval($amount) > 0)
		{
			$amt = $amount;
			if($base_currency != "USD")
			{
				$currency_details = CUtil::chkIsValidCurrency($base_currency);

				if(count($currency_details) > 0)
				{
					$exchange_rate = doubleval($currency_details['exchange_rate']);
					if($exchange_rate_allow)
					{
						$exchange_price = $exchange_rate * (doubleval(\Config::get("webshoppack::site_exchange_rate")) * 0.01);
						$exchange_rate = $exchange_rate - $exchange_price;
					}
					$amt = $amt / $exchange_rate;
				}
			}
			return $amt;
		}
		return $amount;
	}

	public static function setUserCode($user_id)
	{
		$user_code = str_pad($user_id, 6, "0", STR_PAD_LEFT);
		return "U".$user_code;
	}

	public static function getUserId($user_code)
	{
		$user_id = preg_replace("/U(0)*/", '', $user_code);
		return $user_id;
	}

	/**
	 * CUtil::isShopOwner()
	 * added by manikandan_133at10
	 *
	 * @return boolean
	 */
	public static function isShopOwner($user_id = null)
	{
		if(is_null($user_id))
		{
			$user = \Config::get('webshoppack::logged_user_id');
			$logged_user_id = $user();
		}
		else
			$logged_user_id = $user_id;

		if($logged_user_id > 0)
		{
			$details = UsersShopDetails::Select('is_shop_owner', 'paypal_id')->where('user_id', $logged_user_id)->first();
			if(count($details))
			{
				$is_shop_owner = $details->is_shop_owner;
				$paypal_id = $details->paypal_id;
				if($is_shop_owner == 'Yes' && $paypal_id != '')
				{
					return true;
				}
			}
		}
		return false;
	}

	public static function getCurrencyBasedAmount($base_amount, $usd_amount, $base_currency, $return_as_arr = false)
	{
		if($usd_amount != "")
		{
			$amt = $usd_amount;
			$currency_symbol = "USD";
			$currency_symbol_font = "$";
			$fetched_api_currency = "";
			$return_arr = compact('amt','currency_symbol','currency_symbol_font');
			$currency_code = \Config::get("webshoppack::site_cookie_prefix")."_selected_currency";
			if(CUtil::getCookie($currency_code) == "")
				$fetched_api_currency = CUtil::getLocatorApiCurrencyCode();

			if(\Config::get("webshoppack::currency_is_multi_currency_support") == "true")
			{
				if(CUtil::getCookie($currency_code) != "" || $fetched_api_currency != "")
				{
					$currency_details = array();
					//Check whether the currency in coookie is Active status
					if($fetched_api_currency != "")
						$currency_details = CUtil::chkIsValidCurrency($fetched_api_currency);
					else
						$currency_details = CUtil::chkIsValidCurrency(CUtil::getCookie($currency_code));
					if(count($currency_details) > 0)
					{
						if($fetched_api_currency != "")
						{
							if($base_currency == $fetched_api_currency) {
								$amt = $base_amount;
								$currency_symbol = $currency_details["currency_code"];
								$currency_symbol_font = $currency_details["currency_symbol"];
								$return_arr = compact('amt','currency_symbol','currency_symbol_font');
								if($currency_symbol == "INR")
									$currency_symbol_font = "<em class=\"clsWebRupe\">".$currency_details["currency_symbol"]."</em>";
							}
							else
							{
								if($currency_details["currency_code"] != "USD")
								{
									//Currency 2 = currency1 x exchange rate.
									$amt = $amt * $currency_details["exchange_rate"];
									$currency_symbol = $currency_details["currency_code"];
									$currency_symbol_font = $currency_details["currency_symbol"];
									$return_arr = compact('amt','currency_symbol','currency_symbol_font');
									if($currency_symbol == "INR")
										$currency_symbol_font = "<em class=\"clsWebRupe\">".$currency_details["currency_symbol"]."</em>";
								}
							}
						}
						else
						{
							if($base_currency == CUtil::getCookie($currency_code))
							{
								//$amt = $base_amount.toDouble;
								$amt = $base_amount;
								$currency_symbol = $currency_details["currency_code"];
								$currency_symbol_font = $currency_details["currency_symbol"];
								$return_arr = compact('amt','currency_symbol','currency_symbol_font');
								if($currency_symbol == "INR")
									$currency_symbol_font = "<em class=\"clsWebRupe\">".$currency_details["currency_symbol"]."</em>";
							}
							else
							{
								if($currency_details["currency_code"] != "USD")
								{
									//Currency 2 = currency1 x exchange rate.
									$amt = $amt * $currency_details["exchange_rate"];
									$currency_symbol = $currency_details["currency_code"];
									$currency_symbol_font = $currency_details["currency_symbol"];
									$return_arr = compact('amt','currency_symbol','currency_symbol_font');
									if($currency_symbol == "INR")
										$currency_symbol_font = "<em class=\"clsWebRupe\">".$currency_details["currency_symbol"]."</em>";
								}
							}
						}
					}
				}
			}

			$formatted_amt = "";
			$formatted_amt = number_format ($amt, 2, '.','');
			$formatted_amt = str_replace(".00", "", $formatted_amt);
			$formatted_amt = str_replace("Rs.", "", $formatted_amt);

		//	$currencyFormatter = NumberFormat.getCurrencyInstance(new Locale("en", "IN"));
		//	formatted_amt = currencyFormatter.format(amt);
		//	formatted_amt = formatted_amt.replace(".00","");
		//	formatted_amt = formatted_amt.replace("Rs.","");
		//	return "<span class=\"clsPriSym\">"+currency_symbol+"</span>" + " " + currency_symbol_font + formatted_amt;
			if($return_as_arr)
				return $return_arr;
			else
			{
				//return "<small class=\"clsPriSym\">".$currency_symbol. '</small> <strong>' . $currency_symbol_font . $formatted_amt.'</strong>';
				return '<strong>' . $currency_symbol_font .' '. $formatted_amt.'</strong>';
			}
		}
		return "";
	}

	public static function getCookie($cookie_name)
	{
		$value = "";
		if(\Cookie::has($cookie_name) && \Cookie::get($cookie_name)!=null)
		{
			$value = \Cookie::get($cookie_name);
		}
		return $value;
	}

	public static function getLocatorApiCurrencyCode()
	{
		$locatorhq_username = \Config::get("webshoppack::locatorhq_api_username");
		$locatorhq_apikey = \Config::get("webshoppack::locatorhq_api_key");
		$currencyCode = "USD";
		if($locatorhq_username != "" && $locatorhq_apikey != "")
		{
			$ipaddresslist = $_SERVER["REMOTE_ADDR"];
			$locator_url = "http://api.locatorhq.com/?user=".$locatorhq_username."&key=".$locatorhq_apikey."&ip=".$ipaddresslist."&format=text";
			//$result = explode(',',file_get_contents($locator_url));
			$result = explode(',', CUtil::getContents($locator_url));
			if(sizeof($result) > 1)
			{
				$country_name = $result[1];
				if($country_name != "" && $country_name != "-")
				{
					$currency_exchange = CurrencyExchangeRate::select('currency_code')->where('country','=',$country_name)->get();
					if(sizeof($currency_exchange) > 0)
						return $currency_exchange[0]['currency_code'];
				}
			}
		}
		return $currencyCode;
	}
	public static function getContents($url)
	{
		$result = '';

		if(!strstr($url, '://'))
			$url = 'http://'.$url;

		if (function_exists('curl_init'))
			{
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/2.0.0.2');
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    $result = curl_exec($ch);
			    if (!curl_errno($ch))
			        curl_close($ch);
			     else
			        $result = false;
			}
		else
			{
				set_time_limit(180);
				$result = file_get_contents($url) ;
			}
		return $result;
	}

	public static function getUserPersonalImage($user_id, $image_size = "small", $cache = true)
	{
		//if(isset(self::$u_image[$user_id]))
		//	return self::$u_image[$user_id];
		$image_exists = false;
		$image_details = array();

		$user_imageInfo = UserImage::whereRaw('user_id = ? ', array($user_id))->first();
		if(count($user_imageInfo) > 0)
		{
			$image_exists = true;
			$image_details["image_id"] = $user_imageInfo->image_id;
			$image_details["image_ext"] = $user_imageInfo->image_ext;
			$image_details["image_name"] = $user_imageInfo->image_name;
			$image_details["image_server_url"] = $user_imageInfo->image_server_url;
			$image_details["image_large_width"] = $user_imageInfo->large_width;
			$image_details["image_large_height"] = $user_imageInfo->large_height;
			$image_details["image_small_width"] = $user_imageInfo->small_width;
			$image_details["image_small_height"] = $user_imageInfo->small_height;
			$image_details["image_thumb_width"] = $user_imageInfo->thumb_width;
			$image_details["image_thumb_height"] = $user_imageInfo->thumb_height;
			$image_details["image_folder"] = Config::get("generalConfig.user_image_folder");
		}

		$image_path = "";
		$image_url = "";
		$image_attr = "";
		if($image_exists)
			$image_path = URL::asset(Config::get("generalConfig.user_image_folder"))."/";

		$cfg_user_img_large_width = Config::get("user_image_large_width");
		$cfg_user_img_large_height = Config::get("user_image_large_height");
		$cfg_user_img_thumb_width = Config::get("user_image_thumb_width");
		$cfg_user_img_thumb_height = Config::get("user_image_thumb_height");
		$cfg_user_img_small_width = Config::get("user_image_small_width");
		$cfg_user_img_small_height = Config::get("user_image_small_height");

		switch($image_size)
		{
			case 'large':
				$image_url = URL::asset("packages/agriya/webshoppack/images/no_image").'/userno-180.jpg';

				$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_large_width, $cfg_user_img_large_height, $cfg_user_img_large_width, $cfg_user_img_large_height);

				if($image_exists)
				{
					$image_url =  $image_path . $image_details["image_name"]."_L.".$image_details["image_ext"];
					$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_large_width, $cfg_user_img_large_height, $image_details["image_large_width"], $image_details["image_large_height"]);
				}
				break;

			case "thumb":

				$image_url = URL::asset("packages/agriya/webshoppack/images/no_image").'/userno-62.jpg';

				$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_thumb_width, $cfg_user_img_thumb_height, $cfg_user_img_thumb_width, $cfg_user_img_thumb_height);

				if($image_exists)
				{
					$image_url =  $image_path . $image_details["image_name"]."_T.".$image_details["image_ext"];
					$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_thumb_width, $cfg_user_img_thumb_height, $image_details["image_thumb_width"], $image_details["image_thumb_height"]);
				}
				break;

			case "small":

				$image_url = URL::asset("packages/agriya/webshoppack/images/no_image").'/userno-30.jpg';

				$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_small_width, $cfg_user_img_small_height, $cfg_user_img_small_width, $cfg_user_img_small_height);

				if($image_exists)
				{
					$image_url =  $image_path . $image_details["image_name"]."_S.".$image_details["image_ext"];
					$image_attr = CUtil::TPL_DISP_IMAGE($cfg_user_img_small_width, $cfg_user_img_small_height, $image_details["image_small_width"], $image_details["image_small_height"]);
				}
				break;

			default:

				$image_url = URL::asset("packages/agriya/webshoppack/images/no_image").'/userno-62.jpg';

				$image_attr = CUtil::TPL_DISP_IMAGE(52, 52, 62, 62);

				if($image_exists)
				{
					$image_url =  $image_path . $image_details["image_name"]."_T.".$image_details["image_ext"];
					$image_attr = CUtil::TPL_DISP_IMAGE(52, 52, $image_details["image_thumb_width"], $image_details["image_thumb_height"]);
				}
		}
		$image_details['image_url'] = $image_url;
		$image_details['image_attr'] = $image_attr;
		self::$u_image[$user_id] = $image_details ;
		return self::$u_image[$user_id];

	}

	/**
	 * CUtil::makeClickableLinks()
	 * added by manikandan_133at10
	 *
	 * @param mixed $text
	 * @return
	 */
	public static function makeClickableLinks($text)
	{
		$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1:", $text);
		$ret = ' ' . $text;
		$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
		$ret = substr($ret, 1);
		return $ret;
	}

	public static function wordWrap($text, $textLimit = 100, $extra_char = '...')
	{	if(strlen($text) > $textLimit)
		{
			$return_str = preg_replace('/\s+?(\S+)?$/', '', substr($text, 0, $textLimit));
			return $return_str.$extra_char;
		}
		return $text;
	}

	public static function arraytolower(array $array, $round = 0)
	{
	  	return unserialize(serialize($array));
	}

	public static function remminLength($val)
	{
		if(strlen($val)>=4)
			return $val;
	}
}
