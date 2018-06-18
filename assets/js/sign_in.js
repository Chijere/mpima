$(document).ready(function() {
    
//prompt submit
	$("form#login_form").on('submit',function(e){
        e.preventDefault();
		$(this).parents('form#login_form').submit();
	 
	});


	// submit
	 $('form#login_form').submit(function() {
		    $.ajax({
                  url  : $(this).attr('action'),
                  type : 'POST',
                  data : $('#login_form').serialize(),
                  success : afterSuccess,
                  beforeSubmit: beforeSubmit,
                  uploadProgress: OnProgress,
                  error: generalFail,
                  });	
			// always return false to prevent standard browser submit and page navigation 
			return false; 
		}); 		
	

	// beforeSubmit	
	function beforeSubmit()
	{
		/*if (confirm('Are you sure, You want to Delete ?'))
		{
		return true;
		}else
		return false;*/
		
		 $('form#login_form').validate({
				 rules:{
							email:{
									required:true,
									email:true},
							password:{
									required:true}
					   },			
			   messages:{
							email:{
									required:function(){reportError("Please Fill All fields");},
									email:function(){reportError("Please Provide a valid Email");}},
							password:{
									required:function(){reportError("Please Fill All fields");}}
				        }			
						});	
						
			if($('form#login_form').valid())
				return true;
			else
				return false;
		
	}

	//on general fail
	function generalFail()
	{
	   reportError("Sorry An Error Occurred");
	   return false
	}

	function errored(jqXHR, textStatus, errorThrown) {
	   reportError(errorThrown);
	}
			
	//after succesful
	function afterSuccess(info)
	{
		
		if(info.status)
		{
		  if(info.data.href=='')
		  window.location.href = '../';
		  else
		  window.location.href = info.data.href;
		}
		else
		{
		//--reporting
			reportError(info.data.result_info);
		}

	}

	//reporting error
	var timer = null;
	function reportError(errMsg)
	{
			$('#error_see').show('slow');
			$('#error_see').html(errMsg);

			if (timer) {
				    clearTimeout(timer); //cancel the previous timer.
				    timer = null;
				}
			timer = setTimeout(function(){$('#error_see').hide('slow');},9000);
	}

	//during progress
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