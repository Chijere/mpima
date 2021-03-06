

/* JS Document */

/******************************

[Table of Contents]

1. Dropzone controller
******************************/
$(document).ready(function(){
Dropzone.autoDiscover = false;
var thisElemnt; 

/*################### ADD service FORMS###################################*/

		
       $('.btn.add-service').on('click',function(event) {
          event.preventDefault();
      
          var totalthumbs=0;
          $('#image-gallery').find('.thumbnail').each(function(){totalthumbs++;});

          if(totalthumbs>5)
          {
	        $.sweetModal({
	        content: 'You can only add up to 5 services, delete some services first before you add',
	        icon: $.sweetModal.ICON_ERROR,
	        theme:$.sweetModal.THEME_MIXED,                
	        buttons: {
	          someAction: {
	            label: 'Okay',
	            classes: '',
	            action: function() {
	              
	            }
	            },
	          },
	          onClose:function() {
	          }
	      	});
	      }else
	        $("#add-service-Modal").modal({show: true});

        });

       $('.modal .modal-change-photo').on('click',function(event) {
          event.preventDefault();
          thisElemnt = $(this).closest('.modal');
          thisElemnt.find('.dropzone').click();
        });

       $('.modal .delete').on('click',function(event) {
          event.preventDefault();
          thisElemnt = $(this).closest('.modal');
          
          $.sweetModal({
          content: 'Confirm to delete this service?',
          theme:$.sweetModal.THEME_MIXED,
          showCloseButton:false,                
          buttons: {
            someAction: {
              label: 'cancel',
              classes: '',
              action: function() {
              }
              },
            someAction2: {
              label: 'Yes',
              classes: 'redB',
              action: function() {

                          $.ajax({
                              url  : thisElemnt.find(".main_form").attr('action')+'/delete',
                              type : 'POST',
                              data : thisElemnt.find('.main_form,.dropzone').serialize(),
                              success : function(result,status,xhr) {
                              				var other = {message:"service deleted"};
                              				afterSuccess(result,status,xhr,other);
                              			},
                              beforeSend: function() {
						        			return beforeSubmit();
						    			},
                              });

              }
              },
            },
            onClose:function() {
            }
          });
              
        });

        $('.modal .submit').on('click',function(event) {
        event.preventDefault();
        thisElemnt = $(this).closest('.modal');

        thisElemnt.find('.main_form').parsley().validate();
        if (true === thisElemnt.find('.main_form').parsley().isValid()) {

            thisElemnt.find('.bs-callout-info').removeClass('hidden');
            thisElemnt.find('.bs-callout-warning').addClass('hidden');
             
            $.ajax({
                url  : thisElemnt.find(".main_form").attr('action'),
                type : 'POST',
                data : thisElemnt.find('.main_form,.dropzone').serialize(),
                success : function(result,status,xhr) {
                              				afterSuccess(result);
                              			},
                beforeSend: function() {
						        return beforeSubmit();
						    },
                });

          } else {

            thisElemnt.find('.bs-callout-info').addClass('hidden');
            thisElemnt.find('.bs-callout-warning').removeClass('hidden');
          
          }
        });

          var validateFront = function() {
            if (true === thisElemnt.find('.main_form').parsley().isValid()) {
              thisElemnt.find('.bs-callout-info').removeClass('hidden');
              thisElemnt.find('.bs-callout-warning').addClass('hidden');
            } else {
              thisElemnt.find('.bs-callout-info').addClass('hidden');
              thisElemnt.find('.bs-callout-warning').removeClass('hidden');
            }
          };

          //beforeSubmit  
          function beforeSubmit()
          {
            var valid=true;

            if(valid)
            {
               HoldOn.open({
                       theme:"sk-cube-grid",
                       message:'Uploading, please wait ...',
                       backgroundColor:"#456789",
                       textColor:"#c5d1e0"
                  });
            }

            return valid;

          }

              
          //function after succesful file upload (when server response)
          function afterSuccess(result,status,xhr,other)
          { 
              HoldOn.close();
            if(result.status)
            {
              thisElemnt.modal('toggle');


              if(typeof other !== 'undefined' && other.message!=="")
              	var mssg = other.message;
              else if(thisElemnt.hasClass("add"))
              	var mssg = "service Added.";
              else 
              	var mssg = "service edited."; 
              
              $.sweetModal({
                content: mssg,
                icon: $.sweetModal.ICON_SUCCESS,
                theme:$.sweetModal.THEME_MIXED,                
                buttons: {
                  someAction: {
                    label: 'Done',
                    classes: '',
                    action: function() {
                      location.reload();
                    }
                    },
                  },
                  onClose:function() {
                    location.reload();
                  }
              });
            }
            else
            { 
              if(result.data.addition_info.indexOf('validation') !== -1)
              {
                jQuery.each(result.data.result_array,function(index,mssg){
                         if( thisElemnt.find('input[name=' + index + ']').length ) // use this if you are using id to check
                              {
                                  thisElemnt.find('input[name=' + index + ']').parsley().reset();
                                  window.ParsleyUI.addError(thisElemnt.find('input[name=' + index + ']').parsley(), index + '-custom', mssg);  
                              }
                });
              }
              else
              {
                thisElemnt.find('.alert').show().find('span').html(result.data.result_info);
                setTimeout(function(){ thisElemnt.find('.alert').hide(); }, 30000);
              }
            }

          } 



/*################### ADD service DROPZONE###################################*/


  $(".add.dropzone").dropzone({
    maxFilesize: 3,
    maxFiles: 1,
    createImageThumbnails: false,
    acceptedFiles: ".jpeg,.jpg,.png,.pdf,.docx,.doc,.txt,.text",
    totaluploadprogress(uploadProgress) {
    thisElemnt.find('.progress-bar').css('width', uploadProgress + '%')
     },
    addedfile: function(file) {
      thisElemnt.find('.alert').hide(); 
      thisElemnt.find('.progress').show();
      //console.log(file); 
      //console.log(thisElemnt.find('.progress-bar').attr('class')); 

      },

    init: function() {
      this.on("maxfilesexceeded", function(file){
              this.removeAllFiles(); this.addFile(file);
      });


      this.on("success", function(file,response,z) {
       //console.log(file); 
       setTimeout(function(){ thisElemnt.find('.progress').hide(); }, 1000);
       var toPrint='<input type="hidden" value="'+response.data.ref+'.'+response.data.file_extension+'" name="input1">'; //assign a permanent input1
       
       thisElemnt.find('.file_name').html(file.name);
       thisElemnt.find('input[name="input1"]').remove();
       thisElemnt.find('.main_form').append(toPrint); 
      });   
      
 /*     uploadprogress: function(file, progress, bytesSent) {
    if (a.previewElement) {
        var progressElement = file.previewElement.querySelector("[data-dz-uploadprogress]");
        progressElement.style.width = progress + "%";
        progressElement.querySelector(".progress-text").textContent = progress + "%";
    }
}*/

      this.on("error", function(file,response,z) {
        thisElemnt.find('.progress').hide();
        thisElemnt.find('.alert').show().find('span').html(' Error Occured: code 10, '+response);
        setTimeout(function(){ thisElemnt.find('.alert').hide(); }, 30000);
      });
      }
  }); 

/*################### EDIT service DROPZONE###################################*/

	$(".gallery_form").dropzone({
		maxFilesize: 3,
		maxFiles: 1,
		acceptedFiles: ".jpeg,.jpg,.png",
    totaluploadprogress(uploadProgress) {
    thisElemnt.find('.progress-bar').css('width', uploadProgress + '%')
     },
		init: function() {
			this.on("maxfilesexceeded", function(file){
			        this.removeAllFiles(); this.addFile(file);
			    });

			this.on("addedfile", function(file){
           
      thisElemnt.find('.alert').hide(); 
      thisElemnt.find('.progress').show();

			        $(".gallery_form").show().parent('.view').find('img.single_img').hide();
					thisElemnt = $(file.previewElement).parents('.modal');
					var ref = thisElemnt.find('.single_img').attr('ref');
				  	var dlt= '<input for="inputDelete" type="hidden" name="dlt1" value="'+ref+'" >'; 
				    if(ref!=null)
				    thisElemnt.find('.main_form').append(dlt); 

			    });
   
			this.on("error", function(file,response,z) {
        thisElemnt.find('.progress').hide();
			 $(file.previewElement).find('.dz-error-message').text('Error Occured: 11, '+response);
			});
			this.on("success", function(file,response,z) {
       
       setTimeout(function(){ thisElemnt.find('.progress').hide(); }, 1000);
			 var toPrint='<input type="hidden" value="'+response.data.ref+'" name="input1">'; //assign a permanent input1
			 $(file.previewElement).prepend(toPrint);

			});
			
			}
	});




});