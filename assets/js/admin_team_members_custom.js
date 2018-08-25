

/* JS Document */

/******************************

[Table of Contents]

1. Dropzone controller
******************************/

/*################### ADD BANNER DROPZONE###################################*/

$(document).ready(function(){
Dropzone.autoDiscover = false;
	$(".add.dropzone").dropzone({
		maxFilesize: 3,
		maxFiles: 1,
		acceptedFiles: ".jpeg,.jpg,.png",
		init: function() {
			this.on("maxfilesexceeded", function(file){
			        this.removeAllFiles(); this.addFile(file);
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

/*################### ADD BANNER FORMS###################################*/

		var thisElemnt; 
       $('.btn.add-banner').on('click',function(event) {
          event.preventDefault();
      
          var totalthumbs=0;
          $('#image-gallery').find('.thumbnail').each(function(){totalthumbs++;});

          if(totalthumbs>5)
          {
	        $.sweetModal({
	        content: 'You can only add up to 5 banners, delete some banners first before you add',
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
	        $("#add-banner-Modal").modal({show: true});

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
          content: 'Confirm to delete this Banner?',
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
                              				var other = {message:"Banner deleted"};
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
           
            // check pics
            if(thisElemnt.find('.dropzone').find('input[name="input1"]').length <1 && thisElemnt.find(".add")[0])
            {
              valid = false;

                thisElemnt.find('.alert').show().find('span').html(' Upload an Image');
                setTimeout(function(){ thisElemnt.find('.alert').hide(); }, 30000);
            }

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
              	var mssg = "Banner Added.";
              else 
              	var mssg = "Banner edited."; 
              
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



/*################### EDIT BANNER DROPZONE###################################*/

	$(".gallery_form").dropzone({
		maxFilesize: 3,
		maxFiles: 1,
		acceptedFiles: ".jpeg,.jpg,.png",
		init: function() {
			this.on("maxfilesexceeded", function(file){
			        this.removeAllFiles(); this.addFile(file);
			    });

			this.on("addedfile", function(file){
			        $(".gallery_form").show().parent('.view').find('img.single_img').hide();
					thisElemnt = $(file.previewElement).parents('.modal');
					var ref = thisElemnt.find('.single_img').attr('ref');
				  	var dlt= '<input for="inputDelete" type="hidden" name="dlt1" value="'+ref+'" >'; 
				    if(ref!=null)
				    thisElemnt.find('.main_form').append(dlt); 

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




});