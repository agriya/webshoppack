<?php namespace Agriya\Webshoppack;
use Config;
// @added manikandan_1333at10
class MessagingService
{
	/**
	 * MessagingService::sendMessageAddedNotification()
	 * Mail notification to admin & user regarding the message posted
	 * @param mixed $message_id
	 * @return
	 */
	public function sendMessageAddedNotification($message_id)
	{

		if($message_id)
		{
			//To Admin
			$message_details = Message::where('id', $message_id)->where('is_deleted', 0)->first();
			$data_arr['from_user_details'] = CUtil::getUserDetails($message_details->from_user_id);
			$data_arr['to_user_details'] = CUtil::getUserDetails($message_details->to_user_id);
			$data_arr['subject'] = trans('webshoppack::common.new_message_posted_mail_for_admin');
			$data_arr['message_text'] = $message_details->message_text;
			$data_arr['message_subject'] = $message_details->subject;
			$data_arr['date_posted'] = date('Y-m-d', strtotime($message_details->date_added));

			\Mail::send('webshoppack::emails.newMessagePostedMailForAdmin', $data_arr, function($m) use ($data_arr){
				$m->to(Config::get('webshoppack::admin_mail'));
				$m->subject($data_arr['subject']);
			});

			//To User
			$data_arr['to_email'] = $data_arr['to_user_details']['email'];
			$data_arr['to_name'] = $data_arr['to_user_details']['display_name'];
			$data_arr['subject'] = $message_details->subject;

			\Mail::send('webshoppack::emails.newMessagePostedMailForUser', $data_arr, function($m) use ($data_arr){
				$m->to($data_arr['to_email'], $data_arr['to_name']);
				$m->subject($data_arr['subject']);
			});
		}
	}
}
