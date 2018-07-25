

/* JS Document */

/******************************

[Table of Contents]

1. Dropzone controller
******************************/

Dropzone.autoDiscover = false;
$(document).ready(function(){
	
	
	$(".gallery_form").dropzone({
		maxFilesize: 3,
		maxFiles:12,
		acceptedFiles: ".jpeg,.jpg,.png",
		addRemoveLinks: true,
		init: function() {
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
	
});