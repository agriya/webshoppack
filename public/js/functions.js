var populateAnalyticsInfo = function() {
	$.geocode('#geobyte_info', '#maxmind_info', '#browser_info');
	populateHiddenFields(this);
};

var displayLoadingImage = function(){
	$("#selLoading").show();
};

var hideLoadingImage = function(){
	$("#selLoading").hide();
};

var Redirect2URL = function(){
	if(arguments[0]){
		location.replace(arguments[0]);
	}
	else{
		window.back();
	}
	return false;
};

var postAjaxForm = function(){
	/* form name to post */
	var frmname = arguments[0];
	/* div id to populate the response */
	var divname = arguments[1];
	/* action url change */
	var action = $("#"+frmname).attr('action');
	if(arguments.length>2){
		action = arguments[2];
	}
	/* To remove particularElement */
	var remove_element = '';
	if(arguments.length>3){
		remove_element = arguments[3];
	}
	var data = $("#"+frmname).serialize();
	if(arguments.length>4){
		data = arguments[4];
	}

	$.ajax({
		type: "POST",
		url: action,
		data: data,
		beforeSend:displayLoadingImage(),
		success: function(html){
					if(remove_element){
						$(remove_element).remove();
					}
					hideLoadingImage();
				 	$("#"+divname).html(html);
				}
	 });
	 return false;
};

function jquery_ajax(url, pars, function_name){
	if(arguments.length<=0){
		var url = callBackArguments[0];
		var pars = callBackArguments[1];
		var function_name = callBackArguments[2];
	}
	$.ajax({
		type: "POST",
		url: url,
		data: pars,
		/* beforeSend:displayLoadingImage(), */
		success: eval(function_name)
	 });
	return false;
};