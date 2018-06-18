$(document).ready(function(){

var thisElement='';
imgSpinner ();
var spnEl ='			<div class="spinner" style="position: absolute; width: 0px; z-index: 0; left: 50%; top: 50%;" role="progressbar">'+
						'<div style="position: absolute; top: -2px; opacity: 0.05; animation: 1.11111s linear 0s normal none infinite running opacity-74-5-0-9;">'+
						'<div style="position: absolute; width: 5.25px; height: 5.25px; background: rgb(125, 155, 170) none repeat scroll 0% 0%; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.1); transform-origin: left center 0px; transform: rotate(26deg) translate(9px, 0px); border-radius: 2px;"></div>'+
						'</div>'+
						'<div style="position: absolute; top: -2px; opacity: 0.05; animation: 1.11111s linear 0s normal none infinite running opacity-74-5-1-9;">'+
						'<div style="position: absolute; width: 5.25px; height: 5.25px; background: rgb(125, 155, 170) none repeat scroll 0% 0%; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.1); transform-origin: left center 0px; transform: rotate(66deg) translate(9px, 0px); border-radius: 2px;"></div>'+
						'</div>'+
						'<div style="position: absolute; top: -2px; opacity: 0.05; animation: 1.11111s linear 0s normal none infinite running opacity-74-5-2-9;">'+
						'<div style="position: absolute; width: 5.25px; height: 5.25px; background: rgb(125, 155, 170) none repeat scroll 0% 0%; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.1); transform-origin: left center 0px; transform: rotate(106deg) translate(9px, 0px); border-radius: 2px;"></div>'+
						'</div>'+
						'<div style="position: absolute; top: -2px; opacity: 0.05; animation: 1.11111s linear 0s normal none infinite running opacity-74-5-3-9;">'+
						'<div style="position: absolute; width: 5.25px; height: 5.25px; background: rgb(125, 155, 170) none repeat scroll 0% 0%; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.1); transform-origin: left center 0px; transform: rotate(146deg) translate(9px, 0px); border-radius: 2px;"></div>'+
						'</div>'+
						'<div style="position: absolute; top: -2px; opacity: 0.05; animation: 1.11111s linear 0s normal none infinite running opacity-74-5-4-9;">'+
						'<div style="position: absolute; width: 5.25px; height: 5.25px; background: rgb(125, 155, 170) none repeat scroll 0% 0%; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.1); transform-origin: left center 0px; transform: rotate(186deg) translate(9px, 0px); border-radius: 2px;"></div>'+
						'</div>'+
						'<div style="position: absolute; top: -2px; opacity: 0.05; animation: 1.11111s linear 0s normal none infinite running opacity-74-5-5-9;">'+
						'<div style="position: absolute; width: 5.25px; height: 5.25px; background: rgb(125, 155, 170) none repeat scroll 0% 0%; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.1); transform-origin: left center 0px; transform: rotate(226deg) translate(9px, 0px); border-radius: 2px;"></div>'+
						'</div>'+
						'<div style="position: absolute; top: -2px; opacity: 0.05; animation: 1.11111s linear 0s normal none infinite running opacity-74-5-6-9;">'+
						'<div style="position: absolute; width: 5.25px; height: 5.25px; background: rgb(125, 155, 170) none repeat scroll 0% 0%; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.1); transform-origin: left center 0px; transform: rotate(266deg) translate(9px, 0px); border-radius: 2px;"></div>'+
						'</div>'+
						'<div style="position: absolute; top: -2px; opacity: 0.05; animation: 1.11111s linear 0s normal none infinite running opacity-74-5-7-9;">'+
						'<div style="position: absolute; width: 5.25px; height: 5.25px; background: rgb(125, 155, 170) none repeat scroll 0% 0%; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.1); transform-origin: left center 0px; transform: rotate(306deg) translate(9px, 0px); border-radius: 2px;"></div>'+
						'</div>'+
						'<div style="position: absolute; top: -2px; opacity: 0.05; animation: 1.11111s linear 0s normal none infinite running opacity-74-5-8-9;">'+
						'<div style="position: absolute; width: 5.25px; height: 5.25px; background: rgb(125, 155, 170) none repeat scroll 0% 0%; box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.1); transform-origin: left center 0px; transform: rotate(346deg) translate(9px, 0px); border-radius: 2px;"></div>'+
						'</div>'+
						'</div>';
var spinId = 0;
//----- select file -----
	$('.attach-photo-container').on('click','.attach-photo-button',function() {	
		thisElement=$(this);
		thisElement.parents('.attach-photo-container').find('input.attach-photo-file').click();

	});	

//----- submit attached file -----
$('input.attach-photo-file').on('change', function() {
	    
    //limit number of files to 4
	if(this.files.length<1 || (thisElement.parents('.attach-photo-container').find('td#pic').length + this.files.length)>4)
    {
		reportError('Please choose only 4 photos. max.',thisElement);
		return false;
	}

	// add spinners
	var numberFiles=this.files.length;
	var sIndex=0;
 	thisElement.parents('.attach-photo-container').find('td#pic_sampl').each(function(index, el) {
 		spinId++;
 			
			var ths = $(this);
			if(sIndex <numberFiles)
			{	
			if (ths.hasClass('img-spinnerxxx')) 
			{

			}
			else
			{	
				ths.addClass('img-spinner'+spinId);				 
				ths.addClass('img-spinnerxxx');				 
				ths.find('div#pic_upld_button').html(spnEl);
				if(sIndex <numberFiles)
					sIndex++;
			}
		}
	
 	});
    
    //go--
	var form_url=$(this).parents('form').attr('action');
	jQuery.each(this.files,function(index,file){
		var tmpform = new FormData();//create-a-temporary-form-
		tmpform.append("file",file);//append-files-to-the-form
		var options={
						url:form_url,
						data:tmpform,
						type:'POST',
						cache:false,
						contentType:false,
						processData:false,
						beforeSend:beforeSubmit,
						success:afterSuccess,
						error:generalFail,
					};						
		$.ajax(options);	
	});	 
});
 

//function to check file size before uploading.
function beforeSubmit(jqXHR,element){
    //check whether browser fully supports all File API
   if (!window.File || !window.FileReader || !window.FileList || !window.Blob)
	{
		//output to older unsupported browsers that doesn't support HTML5 File API
		reportError("Please upgrade your browser, because your current browser lacks some new features we need!",thisElement);
		return false;
	}
	
	//check empty input filed
	
	if( !thisElement.parents('.attach-photo-container').find('input.attach-photo-file').val()) 
	{
		reportError("No file Provided?",thisElement);
		return false
	}

	//allow file types 
	var ftype = thisElement.parents('.attach-photo-container').find('input.attach-photo-file')[0].files[0].type; //get file type
	switch(ftype)
    {
		case 'image/jpeg':
		case 'image/jpg':
		case 'image/x-png':
		case 'image/png':
		case 'image/gif':
            break;
        default:
            reportError("<b>"+ftype+"</b> Unsupported file type!",thisElement);
			return false
    }
	
	//Allowed file size is less than 5 MB (1048576)
	var fsize = thisElement.parents('.attach-photo-container').find('input.attach-photo-file')[0].files[0].size; //get file size
	if(fsize>3364320) 
	{
		reportError("<b>"+bytesToSize(fsize) +" Too big file! File it should be less than 3 MB.",thisElement);
		return false
	}
				
	
}

//progressXhr
/*function progressHandlingFunction(){
if(e.lengthComputable){
$('progress').attr({value:e.loaded,max:e.total});
}
} */
		

/*function errored(jqXHR, textStatus, errorThrown) {
   reportError(errorThrown);
}*/
		
//function after succesful file upload (when server response)
function afterSuccess(info)
{
	if(info.status)
	{
		//create the thumb
		var index = thisElement.parents('.attach-photo-container').find('td.scrollable-pic').length + 1;
		var toPrint='<td id="pic" class="scrollable-pic" stat="upld"><img class="thumb_pic" pos=""  src="'+info.data.path
		             +'" ></img><span class="glyphicon glyphicon-remove close_thumb editable-table-scrollable-thumbnail-close" title="remove photo" ></span>'+
		                  '<input type="hidden" value="'+info.data.ref+'" name="input'+index+'"> </td>';
		
		if(thisElement.parents('.attach-photo-container').find('tr.scrollable').hasClass('scrollable-onUpload-replace-photo'))
			thisElement.parents('.attach-photo-container').find('tr.scrollable').html(toPrint);
		else if(thisElement.parents('.attach-photo-container').find('td.scrollable-pic').length>0)
			thisElement.parents('.attach-photo-container').find('td.scrollable-pic').last().after(toPrint)
		else
		thisElement.parents('.attach-photo-container').find('tr.scrollable').prepend(toPrint);
		
		
		thisElement=thisElement.parents('.attach-photo-container').find('td.scrollable-pic-sample');
		thisElement.parents('.attach-photo-container').find('td.scrollable-pic-sample').first().remove();
		reportErrorClear(thisElement);											 
		reportErrorCustomClear('photo');											 
	}
	else
	{
		//--------------------------------reporting-------------------
		
		reportError(info.data.result_info,thisElement);
		deleteBlindImgSample(thisElement);
	}

}

//function on general fail
function generalFail(a,b,c,element)
{

   reportError("Sorry An Error Occurred",thisElement);
   deleteBlindImgSample(thisElement);
   return false
}


//function report error clear
function reportErrorClear(element)
{
	element.parents('.attach-photo-container').find('.attach-photo-error-general').hide('');
	//element.find('.progressbox').hide('');
	element.parents('.attach-photo-container').find('.attach-photo-error-general').html("");
}


//function report custom error clear
function reportErrorCustomClear(forAttr)
{
		$('.custom-error').each(function(index,value){
		        if($(this).attr('for')==forAttr)
		        {
			        $(this).hide();
					$(this).html('');
				}
			});
}

//function report error
var timer = null;
function reportError(errMsg,element)
{	
	element = element.parents('.attach-photo-container');	
	element.find('.attach-photo-error-general').show('slow');
	element.find('.attach-photo-error-general').html(errMsg);

	if (timer) {
		    clearTimeout(timer); //cancel the previous timer.
		    timer = null;
		}

	timer = setTimeout(function(){element.find('.attach-photo-error-general').hide('slow');},5000);
}


//progress bar function
function OnProgress(event, position, total, percentComplete)
{
    //Progress bar
	thisElement.parents('.attach-photo-container').find('.attach-photo-progressbox').show('slow');
	thisElement.parents('.attach-photo-container').find('.attach-photo-error-general').hide();
    thisElement.parents('.attach-photo-container').find('.attach-photo-progressbox .progressbar').width(percentComplete + '%') //update progressbar percent complete
    thisElement.parents('.attach-photo-container').find('.attach-photo-progressbox .statustxt').html(percentComplete + '%'); //update status text
    
	if(percentComplete>50)
        {
            thisElement.parents('.attach-photo-container').find('.attach-photo-progressbox .statustxt').css('color','#5f5f5f'); //change status text to white after 50%
        }
}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}


//--delete img
   $('.editable-table-scrollable').on('click', '.editable-table-scrollable-thumbnail-close', function() {
 
    if(confirm('Are you sure you want to Delete this Photo?'))
	{
		deleteThisImg ($(this));				  
	}
	  
  });

//remove img
function deleteThisImg (ths)// this 'ths' is for any child element inside the container of the img
{	
		var totalInputs=0;
	    ths.closest('form.editable-form').find('input[for="editable-table-inputBox-delete"]').each(function(){totalInputs++;});
	    var ref=ths.closest("td").find('img').attr('ref');
	    var dlt= '<input class="editable-table-inputBox" for="editable-table-inputBox-delete" type="hidden" name="dlt'+(totalInputs+1)+'" value="'+ref+'" ">'; 
	    if(ref!=null)
	    ths.closest('form.editable-form').append(dlt);


	    var thisParent=ths.closest('.editable-table-scrollable');
		ths.closest("td").remove();		
		var toPrint = '<td id="pic_sampl" class="scrollable-pic-sample attach-photo-button">'+
					  '<div id="pic_upld_button" class="thumb_pic add_sampl">'+
					  '<span class="add_sgn" >+</span>'+
					  '<span id="img-spinner" class="add_sgn img-spinner" ></span>'+
					  '</div>'+
					  '</td>';
					  
		thisParent.append(toPrint);
}

//remove img
function deleteBlindImgSample (ths)// this 'ths' is for any element inside the container of the img, deletes the sample imgs
{	
	    var thisParent=ths.parents('.attach-photo-container');
		thisParent.find('td.scrollable-pic-sample').first().remove();		
		var toPrint = '<td id="pic_sampl" class="scrollable-pic-sample attach-photo-button">'+
					  '<div id="pic_upld_button" class="thumb_pic add_sampl">'+
					  '<span class="add_sgn" >+</span>'+
					  '<span id="img-spinner" class="add_sgn img-spinner" ></span>'+
					  '</div>'+
					  '</td>';
					  
		thisParent.find('.editable-table-scrollable').append(toPrint);
}

var spinnerUploadImg;
function imgSpinner (target) {
	
	//onload spinner
	var opts = {
				  lines: 9 // The number of lines to draw
				, length: 0 // The length of each line
				, width: 7 // The line thickness
				, radius: 12 // The radius of the inner circle
				, scale: 0.75 // Scales overall size of the spinner
				, corners: 0.8 // Corner roundness (0..1)
				, color: '#7d9baa' // #rgb or #rrggbb or array of colors
				, opacity: 0.05 // Opacity of the lines
				, rotate: 26 // The rotation offset
				, direction: 1 // 1: clockwise, -1: counterclockwise
				, speed: 0.9 // Rounds per second
				, trail: 74 // Afterglow percentage
				, fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
				, zIndex: 0 // The z-index (defaults to 2000000000)
				, className: 'spinner' // The CSS class to assign to the spinner
				, top: '50%' // Top position relative to parent
				, left: '50%' // Left position relative to parent
				, shadow: false // Whether to render a shadow
				, hwaccel: false // Whether to use hardware acceleration
				, position: 'absolute' // Element positioning
			};
	spinnerUploadImg = new Spinner(opts).spin();
		var data = $('body').data();
    if (data.spinnerUploadImg) 
    {
    	data.spinnerUploadImg.stop();
    	delete data.spinnerUploadImg;
  	}
}


});