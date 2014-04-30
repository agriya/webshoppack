<?php namespace Agriya\Webshoppack;
use Input,Validator,View,Config,URL,Request,Redirect;
class MessageAddController extends \BaseController {

	/**
	 * MessageAddController::getAdd()
	 *
	 * @return
	 */
	public function getAdd($user_code)
	{
		$this->addMessageService = new MessageAddService();
        $this->productViewService = new ViewProductService();
		$type = 'viewshop';

		$d_arr = array();

		$is_valid_user = false;
		$user_id = CUtil::getUserId($user_code);
		$user_details = CUtil::getUserDetails($user_id);
		if(count($user_details) > 0) {
			$user = Config::get('webshoppack::logged_user_id');
			$logged_user_id = $user();

			if($user_id != $logged_user_id)
				$is_valid_user = true;
		}
		if($is_valid_user)
		{
			$d_arr['user_code'] = $user_code;
			$d_arr['type'] = $type;
		}
		else
		{
			$d_arr['error_msg'] = trans('webshoppack::messaging.addMessage.invalid_user');
			$d_arr['type'] = '';
		}
		return View::make('webshoppack::addMessage', compact('d_arr'));
	}

	/**
	 * MessageAddController::postAdd()
	 *
	 * @return
	 */
	public function postAdd()
	{
		$this->addMessageService = new MessageAddService();

		$messages = array();
		$messages['message_text.required'] = trans('common.required');
		$messages['subject.required'] = trans('common.required');
		$rules = array('subject'		=> $this->addMessageService->getValidatorRule('subject'),
						'message_text'  => $this->addMessageService->getValidatorRule('message_text')
				 );
		$user_code = Input::get('user_code');
		$type = Input::get('type');
		$v = Validator::make(Input::all(), $rules, $messages);
		if ( $v->passes())
		{
			$input = Input::all();
			$message_id = $this->addMessageService->addMessage($input);
			if($message_id > 0)
			{
				$d_arr['message_id'] = $message_id;
				$success_msg = trans('webshoppack::messaging.addMessage.message_add_success');
				return Redirect::to(Config::get('webshoppack::shop_uri').'/user/message/add/'.$user_code)->with('message_id', $message_id)->with('success_message', $success_msg);
			}
			else
			{
				return Redirect::to(Config::get('webshoppack::shop_uri').'/user/message/add/'.$user_code);
			}
		}
		else
		{
			return Redirect::to(Config::get('webshoppack::shop_uri').'/user/message/add/'.$user_code)->withInput()->withErrors($v);
		}
	}
}