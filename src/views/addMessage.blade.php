@extends('webshoppack::adminPopup')
@section('content')
	<div class="popup-title">
    	@if($d_arr['type'] == 'offer')
        	<h2>{{trans("webshoppack::messaging.addMessage.make_an_offer")}}</h2>
        @else
			<h2>{{trans("webshoppack::messaging.addMessage.contact_member")}}</h2>
        @endif
	</div>
    <div class="popup-frm" style="max-height:380px;overflow:auto;">
		@if(isset($d_arr['error_msg']))
			<div class="alert alert-danger">
				{{ $d_arr['error_msg'] }}
			</div>
			<div class="form-group">
				<div class="col-lg-offset-2 col-lg-10 pad-lft0">
					<a href="javascript://" itemprop="url" onclick="javascript:parent.$.fancybox.close();">
						<button type="reset" class="btn btn-danger mt10">Close</button>
					</a>
				</div>
			</div>
		@elseif(Session::has('success_message'))
			<div class="alert alert-success">
				{{ Session::get('success_message') }}
			</div>
			<div class="form-group">
				<a href="javascript://" itemprop="url" onclick="javascript:parent.$.fancybox.close();">
					<button type="reset" class="btn btn-default mt10 pad-lft0">Close</button>
				</a>
			</div>
    	@else
        {{ Form::open(array('url' => Config::get('webshoppack::shop_uri').'/user/message/add/'.$d_arr['user_code'], 'class' => 'form-horizontal',  'id' => 'addmessage_frm')) }}
                {{ Form::hidden("user_code", $d_arr['user_code']) }}
                {{ Form::hidden("type", $d_arr['type']) }}
                <fieldset class="col-sm-12">
                    <div class="form-group {{{ $errors->has('subject') ? 'error' : '' }}}">
                        {{ Form::label('subject', trans('webshoppack::messaging.addMessage.subject_label'), array('class' => 'col-lg-2 control-label required-icon')) }}
                        <div class="col-lg-3">
                            {{  Form::text('subject', null, array('class' => 'form-control')); }}
                            <label class="error">{{{ $errors->first('subject') }}}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('message_text', trans('webshoppack::messaging.addMessage.description_label'), array('class' => 'col-lg-2 control-label required-icon')) }}
                        <div class="col-lg-3">
                            {{  Form::textarea('message_text', null, array('class' => 'form-control')); }}
                            <label class="error">{{{ $errors->first('message_text') }}}</label>
                        	<p class="text-muted">{{ trans('webshoppack::messaging.addMessage.code_note') }}</p>
						</div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <a href="javascript://" itemprop="url" onclick="javascript:parent.$.fancybox.close();">
                                <button type="reset" class="btn btn-default">Cancel</button>
                            </a>
                        </div>
                    </div>
                </fieldset>
        {{ Form::close() }}
	</div>
   <script type="text/javascript">
   		var mes_required = 'Required';
   		$("#addmessage_frm").validate({
			rules: {
				 subject: {
					required: true
				 },
				 message_text: {
						required: true
				 },
		  },
		messages: {
			subject: {
					required: mes_required
				},
			message_text: {
					required: mes_required
				},
		},
		/* For Contact info violation */
		submitHandler: function(form) {
			form.submit();
		}
	});
   </script>
  @endif
@stop