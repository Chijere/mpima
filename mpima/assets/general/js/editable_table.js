$(document).ready(function(){

//************* showing  the edit options	
$(".editable-table td").on('click','.editable-table-button-edit',function(){

		//handle scrollable
		var scrollable_photos=[];
		$(this).closest('.editable-table').find('.editable-table-scrollable-autoload-photos').find('img').each(function(index,value){
			scrollable_photos[index]=[];
			scrollable_photos[index]['src']=$(this).attr('src');
			scrollable_photos[index]['ref']=$(this).attr('ref');
		});

		var toPrint='';
		var i=0;
		$.each(scrollable_photos,function(index,value){
			toPrint +=  	'<td id="pic" stat="">'+
							'<img class="thumb_pic" src="'+value['src']+'" ref="'+value['ref']+'" pos="">'+
							'<icon id="close_thumb" class="icon-close close_thumb editable-table-scrollable-thumbnail-close"></icon>'+
				  		'</td>';
			i++;
		})
		while(i<4)
		{
			toPrint += '<td id="pic_sampl">'+
					  '<div id="pic_upld_button" class="thumb_pic add_sampl attach-photo-button">'+
					  '<span class="add_sgn" >+</span>'+
					  '</div>'+
					  '</td>';
			i++;
		}	
		$(this).closest('.editable-table').find('.editable-table-scrollable-autoload').html(toPrint);
		
		//handle textarea
		$(this).closest('.editable-table').find(".editable-table-value-textarea").each(function(){
		var contentHeight = $(this).height();
		console.log($(this).height());
		contentHeight = contentHeight+40;

		var content = $(this).html();
			content = content.replace(/[<]br[^>]*[>]/gi,'');

		var matchUp= $(this).attr('for');
		$(this).closest('.editable-table').find('textarea[for="'+matchUp+'"]').focus();
		$(this).closest('.editable-table').find('textarea[for="'+matchUp+'"]').val(content);
		$(this).closest('.editable-table').find('textarea[for="'+matchUp+'"]').css('height',contentHeight+"px");
		
		
		});
		
		//handle hidden parts
		$(this).closest('.editable-table').find(".editable-table-hidden,.editable-table-hidden-toggle").each(function(){
		$(this).toggleClass('editable-table-hidden');
		$(this).addClass('editable-table-hidden_temp');		
		});		
		
		return false;
	});
	
//************* cancel		
$(".editable-table td").on('click','.editable-table-button-cancel',function(){
	$(this).closest('.editable-table').find(".editable-table-hidden_temp").each(function(){
		$(this).removeClass('editable-table-hidden_temp');
		$(this).toggleClass('editable-table-hidden');
			
		$(this).closest('.editable-table').find("#panel_detail_editable").each(function(){
			$(this).show();
		});
	});	
    $(this).closest('form.editable-form')[0].reset();     
    $(this).closest('form.editable-form').find('input.editable-table-inputBox').remove();     
    $(this).closest('form.editable-form').find('div#error_gnrl').hide();     
    $(this).closest('form.editable-form').find('div#error_gnrl').html("");     
    return false;		
});		

//************* add input box	
$(".editable-table td").on('click','.editable-table-button-add-input',function(){

		var infoTotal=0;
		var totalInputs=0;
				
		$(this).closest('.editable-table').find(".editable-table-value-toInclude").each(function(){
			if(!($(this).is(":hidden")))
				infoTotal++;
		});
		$(this).closest('.editable-table').find('input[for="editable-table-inputBox-add"]').each(function(){infoTotal++; totalInputs++;});
		
		var toPrint='<tr id="table_add_input_tr" class="contact_table_add_tr editable-table-hidden_temp">'+
		'<td id="table_add_input_td" class="contact_table_add_td">'+
		'<input id="add_inputBox" for="editable-table-inputBox-add" class="contact_add_inputBox editable-table-inputBox" type="text" name="input'+(totalInputs+1)+'" ">'+
		'<label class="error" for="input'+(totalInputs+1)+'" style="display:none ;"></label>'+
		'</td></tr>'
		;		
		
		if(infoTotal==4 || infoTotal>4)
		{
		 alert("only a maximum of 4");
		}
		else
		{
		 $(this).closest('tr').before(toPrint);
		}
});

//remove
$(".editable-table td").on('click','.editable-table-value-remove',function(){

	$(this).closest('.contact_table_info_tr').addClass('editable-table-hidden');
	$(this).closest('.contact_table_info_tr').addClass('editable-table-hidden_temp');	
	var totalInputs=0;
    $(this).closest('form.editable-form').find('input[for="editable-table-inputBox-delete"]').each(function(){totalInputs++;});
    var ref=$(this).closest(".contact_table_info_tr").attr('ref');
    var dlt= '<input class="editable-table-inputBox" for="editable-table-inputBox-delete" type="hidden" name="dlt'+(totalInputs+1)+'" value="'+ref+'" ">'; 
    if(ref!=null)
    $(this).closest('form.editable-form').append(dlt);	
});


});