

/* JS Document */

/******************************

[Table of Contents]

1. Dropzone controller
******************************/


$(document).ready(function(){
Dropzone.autoDiscover = false;
var totalInputs=0;

	$(".gallery_form").dropzone({
		maxFilesize: 3,
		maxFiles:12,
		acceptedFiles: ".jpeg,.jpg,.png",
		addRemoveLinks: true,
		init: function() {
			
			myDropzone = this;

			this.options.addRemoveLinks = true;
      		this.options.dictRemoveFile = "Delete";
   			
			this.on("maxfilesexceeded", function(file){
		        this.removeFile(file);
		    });

			this.on("error", function(file,response,z) {
			 var errorMessage = response.errorMessage;
			 $(file.previewElement).find('.dz-error-message').text('Error Occured: 10');
			});
			this.on("success", function(file,response,z) {
			 var errorMessage = response.errorMessage;

			 var index = $(file.previewElement).parents('.dropzone').find('.dz-image-preview.dz-success.dz-complete').length + 1+1;// start counting at input2 bcoz input1 is assigned to featured image
			 var toPrint='<input type="hidden" value="'+response.data.ref+'" name="input'+index+'">';
			 $(file.previewElement).prepend(toPrint);
			
			});
			
			}
	});
	
    $.ajax({
        url  : window.location.href,
        type : 'GET',
	    dataType: 'json',
	    success: function(data) { 
	    		
	    		var index = 0;
	    		$.each(data.item.data.records[0].item_pic, function(key,value){
	    			if(value.front_pic!=1)
	    			{

			    		var mockFile_path = $('#info #IMAGE_SRC_URL').html()+ value.path+'_t.jpg';
			    		var mockFile = { name: 'property', size:1000000, serverID: 0};
			    		// Call the default addedfile event handler
						myDropzone.emit("addedfile", mockFile);
						// And optionally show the thumbnail of the file:
						myDropzone.emit("thumbnail", mockFile, mockFile_path);
						// Make sure that there is no progress bar, etc...
						myDropzone.emit('complete', mockFile);
						
						myDropzone.options.maxFiles = myDropzone.options.maxFiles - 1;
    					
    					myDropzone.files.push(mockFile);

    					$(".gallery_form").find('.dz-preview').eq(index).attr('ref', value.id);
    					index++;
		          	}
		          });
	    	},
	    error: function (request, error) {
			    //console.log(error);
			},
        });


	myDropzone.on("removedfile", function (file) {
    	    
		    $('.form_publish').find('input[for="inputDelete"]').each(function(){totalInputs++;});
		    //var ref=ths.closest("td").find('img').attr('ref');
		    var dlt= '<input for="inputDelete" type="hidden" name="dlt'+(totalInputs+1)+'" value="'+file.previewElement.attributes.ref.value+'" >'; 
		    
		    if(file.previewElement.attributes.ref.value!=null)
		    $('.form_publish').append(dlt);

			myDropzone.options.maxFiles = myDropzone2.options.maxFiles + 1;

    	//alert('jjj');
    
    });


	$(".featured_form").dropzone({
		maxFilesize: 3,
		maxFiles: 1,
		acceptedFiles: ".jpeg,.jpg,.png",
		addRemoveLinks: true,
		init: function() {

			myDropzone2 = this;

			console.log(myDropzone2.options.maxFiles);

			this.options.addRemoveLinks = true;
      		this.options.dictRemoveFile = "Delete";
      		this.on("maxfilesexceeded", function(file){
      			console.log(myDropzone2.options.maxFiles);
			        this.removeAllFiles(); this.addFile(file);
			    console.log(myDropzone2.options.maxFiles);    
			    });
   
			this.on("error", function(file,response,z) {
			 var errorMessage = response.errorMessage;
			 $(file.previewElement).find('.dz-error-message').text('Error Occured: 10');
			});
			
			this.on("success", function(file,response,z) {
			 var errorMessage = response.errorMessage;

			 var toPrint='<input type="hidden" value="'+response.data.ref+'" name="input1">'; //assign a permanent input1
			 $(file.previewElement).prepend(toPrint);

			});
		
			}
	});

    $.ajax({
    url  : window.location.href,
    type : 'GET',
    dataType: 'json',
    success: function(data) { 
    		
    		var mockFile_path = $('#info #IMAGE_SRC_URL').html()+ data.item.data.records[0].item_pic.main.path+'_t.jpg';
    		var mockFile = { name: 'property', size:1000000 };
    		// Call the default addedfile event handler
			myDropzone2.emit("addedfile", mockFile);
			// And optionally show the thumbnail of the file:
			myDropzone2.emit("thumbnail", mockFile, mockFile_path);
			// Make sure that there is no progress bar, etc...
			myDropzone2.emit('complete', mockFile);

			myDropzone2.options.maxFiles = myDropzone2.options.maxFiles - 1;

			myDropzone2.files.push(mockFile);

			$(".featured_form").find('.dz-preview').attr('ref', data.item.data.records[0].item_pic.main.id);
	          
    	},
    error: function (request, error) {
		    //console.log(error);
		},
    });

	myDropzone2.on("removedfile", function (file) {

	    if( typeof file.previewElement.attributes.ref != "undefined")
	    {
		    $('.form_publish').find('input[for="inputDelete"]').each(function(){totalInputs++;});
		    var dlt= '<input for="inputDelete" type="hidden" name="dlt'+(totalInputs+1)+'" value="'+file.previewElement.attributes.ref.value+'" >'; 
		    $('.form_publish').append(dlt);
		    myDropzone2.options.maxFiles = myDropzone2.options.maxFiles + 1;
	    }

	//alert('jjj');            
    });
});




















