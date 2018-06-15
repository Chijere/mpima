$(document).ready(function(){

var options;
var thisElemnt;

var options = { 
	//target:   '#add_sng_frm_err span#err_msg',   // target element(s) to be updated with server response 
	beforeSubmit:  beforeSubmit,  // pre-submit callback 
	success: afterSuccess,  // post-submit callback 
	uploadProgress: OnProgress, //upload progress callback 
	error: generalFail, // use errored() only wen need
	type:'POST',
	//resetForm: true        // reset the form after successful submit 
	}; 

// submit form submit
 $('form.editable-form').on('submit',function() {
		$(this).ajaxSubmit(options);  			
		// always return false to prevent standard browser submit and page navigation 
		return false; 
});

//submit by other tags
$('.editable-form').on('click','.editable-form-button-submit',function(){
	$(this).parents('.editable-form').ajaxSubmit(options);  			
	// always return false to prevent standard browser submit and page navigation 
		return false; 
});

//submit by other tags
$('.editable-form-container').on('click','.editable-form-button-submit',function(){
	$(this).parents('.editable-form-container').find('.editable-form').ajaxSubmit(options);  			
	// always return false to prevent standard browser submit and page navigation 
		return false; 
});

//form $this element
function containerElemntSelector(element){
	if(element.hasClass('editable-form-container'))
		return element;
	else if(element.parents('.editable-form-container').length)
		return element.parents('.editable-form-container');
	else if(element.parents('editable-form').length)
		return element.parents('.editable-form');
	else 
		return element;	
}


//beforeSubmit	
function beforeSubmit(jqXHR,element)
{
	
	if(element.attr('for')=='email')
	{ 
	    var rules=	{rules:{
						input1:{
								email:true},
						input2:{
								email:true},
						input3:{
								email:true},
						input4:{
								email:true},
				    },
				    messages:{
						input1:{
								email:"Provide a valid email"},
						input2:{
								email:"Provide a valid email"},
						input3:{
								email:"Provide a valid email"},
						input4:{
								email:"Provide a valid email"},
			        }};
	}else if(element.attr('for')=='phone')
	{ 
	    var rules=	{rules:{
						input1:{
								minlength:9,
								maxlength:11},
						input2:{
								minlength:9,
								maxlength:11},
						input3:{
								minlength:9,
								maxlength:11},
						input4:{
								minlength:9,
								maxlength:11}
				    },
				    messages:{
						input1:{
								minlength:"Provide a valid phone number",
								maxlength:"Provide a valid phone number"},
						input2:{
								minlength:"Provide a valid phone number",
								maxlength:"Provide a valid phone number"},
						input3:{
								minlength:"Provide a valid phone number",
								maxlength:"Provide a valid phone number"},
						input4:{
								minlength:"Provide a valid phone number",
								maxlength:"Provide a valid phone number"},
			        }}   ;
	}else if(element.attr('for')=='phone')
	{ 
	    var rules=	{rules:{
						input1:{
								minlength:9,
								maxlength:11},
						input2:{
								minlength:9,
								maxlength:11},
						input3:{
								minlength:9,
								maxlength:11},
						input4:{
								minlength:9,
								maxlength:11}
				    },
				    messages:{
						input1:{
								minlength:"Provide a valid phone number",
								maxlength:"Provide a valid phone number"},
						input2:{
								minlength:"Provide a valid phone number",
								maxlength:"Provide a valid phone number"},
						input3:{
								minlength:"Provide a valid phone number",
								maxlength:"Provide a valid phone number"},
						input4:{
								minlength:"Provide a valid phone number",
								maxlength:"Provide a valid phone number"},
			        }}   ;
	}else
	{
		var rules={rules:{},messages:{}};
	}
	 

	 	element.validate({
				rules:rules.rules,
				messages:rules.messages,
				focusInvalid: false,
				onkeyup: false	
	       });	
	

		if(element.valid())
			{return true; }
		else
			return false;
			
}

		
//function after succesful file upload (when server response)
function afterSuccess(info,a,b,element)
{	

	//form root element
	containerElement=containerElemntSelector(element);	

	if(info.status)
	{
			window.location.reload();
	}
	else
	{	
		//reporting		
		if(info.data.addition_info.indexOf('validation') !== -1)
		{
			reportErrorArray(info.data.result_array,element);
		}
		else
		{
			reportError(info.data.result_info,containerElement);
		}
	}


}

//function on general fail
function generalFail(a,b,c,element)
{
   reportError("Sorry An Error Occurred",element);
   return false
}

//function report error
function reportError(errMsg,containerElement)
{			
		containerElement.find('div#error_gnrl').show('slow');
		containerElement.find('div#error_gnrl').html(errMsg);
}

//function report error array
function reportErrorArray(e,editableFormElement)
{
		jQuery.each(e,function(index,mssg){
		        editableFormElement.find('input[name="'+index+'"]').parents('td').children('label.error').show('slow');
				editableFormElement.find('input[name="'+index+'"]').parents('td').children('label.error').html(mssg);
			
			});
}

//progress bar function
function OnProgress(event, position, total, percentComplete)
{
    //Progress bar
	//$('#loading-img').hide();
	/*$('#progressbox').show('slow');
	$("#frm_fl_upld_err").hide();
    $('#progressbar').width(percentComplete + '%') //update progressbar percent complete
    $('#statustxt').html(percentComplete + '%'); //update status text
    
	if(percentComplete>50)
        {
            $('#statustxt').css('color','#5f5f5f'); //change status text to white after 50%
        }*/
}

});

