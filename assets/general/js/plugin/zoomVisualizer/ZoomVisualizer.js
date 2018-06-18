/*
	//--------
	
*/

(function($){
	
	//
	$.fn.ZoomVisualizer = function( method )
	{
		
		//
		var methods =
		{
			init :										function( options ){ 			return this.each(function(){	_init(this, options);});}
		};

		var defaults =
		{
			closeWhenClickOutside	: false,
			closeWhenEscPressed		: false,
			object					: null,
			resizeInitial			: false,// redimensiona ou não a imagem em zoom
			sliderOrientation		: "horizontal",
			positionZoom			: {left:0,right:0,top:0,bottom:0},//margem para a mascara do zoom na tela
			centerThumbs			: false,
			loader					: "",
			added					: function() {},
			removed					: function() {}
		};
		
		var _this;
		var _imageLoad;//nome da imagem a ser carregada
		var _data;//dados 
		var _wrapperResize;//objeto a ser redimensionado/ movimentado
		var _originalWidth;
		var _originalHeight;
		var _wrapper;
		var hDragMove;
		var btZoomIn;
		var btZoomOut;
		var posDragMove = 0;
		var moveDrag = 2;
		var firstLoad;
		var wrapper_geral;
		var scaleResize = 1;
		var index_active = 0;
		var plugin_settings;		
		
		// Method calling logic
		if ( methods[method] )//caso exista um método, esse método é chamado
		{
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		}
		else if ( typeof method === 'object' || ! method )//caso não exista um método ou seja apenas passado o objeto
		{
			return methods.init.apply( this, arguments );
		}
		else//caso o método não exista
		{
		  $.error( 'Method ' +  method + ' does not exist on ZoomVisualizer' );
		} 

		function _init($this, options)
		{
			
			var $this 						= $($this);
					
			var options 					= $.extend(defaults, options);
			plugin_settings 				= options;
			$this.data(options);
			
			_this = $this;
			
			var data 						= $this.data();
			


			if(options.object == '')
			{

				//console.log('The object is empty!!!!');
			}
			else
			{
				initialize($this);
			}
		}
		var timeInterval;

		function initialize($this)
		{
						
			_data = $this.data();
			
			wrapper_geral = $(_data.object);
			
			wrapper_geral.fadeIn(400);
			
			plugin_settings.added.call(this, { object:wrapper_geral});
			
			//prevent body from scroll
			jQuery(document.body).css('overflow', 'hidden');
			
			_wrapper = wrapper_geral.find(".wrapper");
			
			//---------------------------------------------------------
			//botões de zoom
			//---------------------------------------------------------
			var _btZoomIn = $(wrapper_geral.find(".zoom-in"));
			var _btZoomOut = $(wrapper_geral.find(".zoom-out"));
	
			_btZoomOut.mouseover(function(){
				timeInterval = window.setInterval(zoomOut,0);
				return false;
			});
	
			_btZoomIn.mouseover(function(){
				timeInterval = window.setInterval(zoomIn,0);
				return false;
			});
	
			_btZoomOut.mouseout(function(){
				clearInterval(timeInterval);
				//return false;
			});
	
			_btZoomIn.mouseout(function(){
				clearInterval(timeInterval);
				//return false;
			});
	
			_btZoomOut.click(function(){
				return false;
			});
	
			_btZoomIn.click(function(){
				return false;
			});

			//---------------------------------------------------------
			//---------------------------------------------------------			
			//posiciona a imagem do zoom
			$(".content",wrapper_geral).css({top:_data.positionZoom.top,left:_data.positionZoom.left});
			
			//
			wrapper_geral.addClass(_data.sliderOrientation);
			
			//posiciona os thumbs caso centerThumbs == true
			if(_data.centerThumbs == true)
			{
				var _width = 0;
				
				$('#listagem-imagens a',wrapper_geral).each(function(index,value)
				{
					_width += $(this).outerWidth(true);
				});
				
				if(_width > $(window).width())
				{
					$('#listagem-imagens > div',wrapper_geral).width(_width+5);
					$('#listagem-imagens',wrapper_geral).css({ overflow:"scroll"});
					_width = $(window).width() - 10;
				}
				
				$('#listagem-imagens',wrapper_geral).width(_width+5);
				$('#listagem-imagens',wrapper_geral).css({ margin:'0 auto'});
			}
			
			//oculta zoom-footer e as setas caso tenha apenas um item no zoom
			if($('#listagem-imagens a',wrapper_geral).size() == 1)
			{
				$(".zoom-footer",wrapper_geral).css({height:0,opacity:0,visibility:"hiden", display:"none"});
				$(".content #next",wrapper_geral).hide();
				$(".content #before",wrapper_geral).hide();
				$("#aba-lista").css({height:0,opacity:0,visibility:"hiden", display:"none"});
			}
			
			if(_data.sliderOrientation == "vertical") 
			{
				$(".tooltip-caller",wrapper_geral).mouseenter(function(){
					var _tooltip = $(".tooltip", $(this).parent());
					
					_tooltip.css({display:'block',opacity:0});
					
					if(!_tooltip.hasClass('positioned')){
						_tooltip.addClass('positioned');
						_tooltip.css({left:-$("p",_tooltip).outerWidth(true)-10, width:$("p",_tooltip).outerWidth(true)});
					}
					
					_tooltip.animate({opacity:1},80);
				});
				
				$(".tooltip-caller",wrapper_geral).mouseleave(function(){
					var _tooltip = $(".tooltip", $(this).parent());
					_tooltip.hide(100);					
				});
			}
			
			//---------------------------------
			//drag zoom
			//----------
			var _drag = wrapper_geral.find("#barra #scroll");//barra
			var _box = wrapper_geral.find("#barra");
			
			hDragMove = (_data.sliderOrientation == "horizontal") ? _box.width()-_drag.width() : _box.height()-_drag.height();
			
			var _axis = (_data.sliderOrientation == "horizontal") ? 'x' : 'y';
			
			_drag.draggable({
				axis: _axis,
				containment: 'parent',
				cursor: 'pointer',
				appendTo: 'body',
				scroll: false,
				drag: function(event, ui)
				{
					reposImage();
				},
				start: function(event, ui)
				{
					_box.parent().parent().mouseleave(
						function()
						{
							$('.ui-draggable-dragging',wrapper_geral).draggable( 'option',  'revert', false ).trigger('mouseup');
						}
					);
				},
				stop: function(event, ui)
				{
					//box.parent().parent().unbind();
				}
			});
			
			//---------------------
			//move image with mouse wheel
			/*_wrapper.bind('mousewheel',
				function(event, delta)
				{
					if(_wrapperResize){
						//-----------------------------------
						var posDrag = parseFloat(getSize(_drag,"left"));
						var pctBox = (_wrapper.height()/_wrapperResize.height())*5;
						var _rate = 1;
						
						if(posDrag-(delta*_rate*pctBox) >= 0 && posDrag-(delta*_rate*pctBox) <= hDragMove)
						{
							_drag.css('left',(posDrag-(delta*_rate*pctBox))+"px");
						}else{
							if(posDrag-(delta*_rate) < 0)
							{
								_drag.css('left',"0px");
							}
							
							if(posDrag-(delta*_rate) > hDragMove)
							{
								_drag.css('left',hDragMove+"px");
							}
						}
						
						reposImage();
					}
					//-----------------------------------
					return false;
				}
			);*/
			
			//-----------------------------
			var _btFechar = $(wrapper_geral.find(".fechar"));//barra
			
			_btFechar.click(
				function () {
					closeVisualizer ();
					return false;
				}
			);
			
			if(_data.closeWhenEscPressed)
			{
				$(document).on('keyup',checkKeyUp);

			}
				
			if(_data.closeWhenClickOutside)
			{	
				$(document).mousedown(checkBackgroundMouseDown);
			}
					
			initListaThumbs();//inicia a lista com os thumbs
			loadImage();//carrega imagem
			initSetas();//inicia as setas
			initAbaMinimizar();
			moveImage();	
			
			//redimensionamento do browser
			$(window).resize(resizeHandler);
			resizeHandler();
				
		}//end initialize
		
		function checkBackgroundMouseDown (evt)
		{
			if($(evt.target).hasClass("wrapper") ||$(evt.target).hasClass("lightbox-ofertas-bg"))
			{
				closeVisualizer ();
			}
		}
		
		function checkKeyUp (evt)
		{
			if (evt.keyCode == 27) {
			   closeVisualizer ();
			}
		}
		
		function closeVisualizer ()
		{
			$(_data.object).animate(300,function(){	destroy();});
			jQuery(document.body).css('overflow', 'auto');
		}
				
		function moveImage ()
		{
			//$( "#zoom-visualizer .wrapper" ).mousemove(function( event ) {
			  //console.log ( event.clientX + ", " + event.clientY);
			//});
			$(".wrapper",wrapper_geral).mousemove(function (e) {
				//http://www.quirksmode.org/js/events_properties.html
				//getting current mouse position
				
				var posx = 0;
				var posy = 0;
				
				posx = e.clientX;
				posy = e.clientY; 
				
				var _left = getSize($(this).parent(),'left');
				var _top = getSize($(this).parent(),'top');
				
				posx = posx - _left;
				posy = posy - _top;
				
				//------------------
				//movimento da imagem
				//------------------
				
				var _thumb = $(".dragme",$(this));
				
				var _mouseY = posy;
				var _mouseX = posx;
				
				var ctposY = getSize(_thumb,'top');
				var ctposX = getSize(_thumb,'left');
				
				var widthMask = $(this).width();
				var heightMask = $(this).height();
				
				var xTemp = (_mouseX/widthMask)*((widthMask- _thumb.width()));
				var dgmX = (xTemp-ctposX)/5;
				
				var yTemp = (_mouseY/heightMask)*((heightMask - _thumb.height()));
				var dgmY = (yTemp-ctposY)/5;
				
				if(_thumb.height()>heightMask)
				{					
					if (dgmY < -0.050000 || dgmY > 0.050000)
						_thumb.css({top:(ctposY + dgmY)});
				}
				
				if(_thumb.width()>widthMask)
				{					
					if (dgmX < -0.050000 || dgmX > 0.050000)
						_thumb.css({left:(ctposX + dgmX)});
				}
				
				//------------------
				//------------------
				
			});
			
		}
		
		function getSize(_obj,_css)
		{
			if(_obj.size()>0)
			{
				var _regExp = new RegExp("[a-z][A-Z]","g");
				return parseFloat(_obj.css(_css).replace(_regExp, ""));
			}
		}
		
		function initAbaMinimizar () {
			$("#aba-lista",wrapper_geral).click(function(){
				//---------------
				if($(".zoom-footer",wrapper_geral).hasClass("closed")){
					$(this).find("span").text("Ocultar miniaturas");
					$(".zoom-footer",wrapper_geral).animate({height:135},0);
					$("#listagem-imagens",wrapper_geral).show();
					$(" .zoom-footer",wrapper_geral).removeClass("closed");
				}else{
					$(this).find("span").text("Exibir miniaturas");
					$(".zoom-footer",wrapper_geral).animate({height:10},0);
					$("#listagem-imagens",wrapper_geral).hide();
					$(".zoom-footer",wrapper_geral).addClass("closed");
				}
				resizeHandler();
				reposImage ();	
				//--------------
				return false;
			});
		}
		
		function destroy () {
			
			//console.log(_data.object);
			$(_data.object).hide('fast', function() {});// lets just hide it instead of destorying the object completely			
			/*/--------
			_this = null;
			_imageLoad = null;//nome da imagem a ser carregada
			_data = null;//dados 
			_wrapperResize = null;//objeto a ser redimensionado/ movimentado
			_originalWidth = null;
			_originalHeight = null;
			_wrapper = null;
			hDragMove = null;
			btZoomIn = null;
			btZoomOut = null;
			posDragMove = 0;
			moveDrag = 2;
			firstLoad = null;
			wrapper_geral = null;
			scaleResize = 1;
			index_active = 0;
			
			if($('.loader_overlay').size() > 0){	$('.loader_overlay').remove();}
			
			plugin_settings.removed.call(this, {});
			
			$(window).unbind("resize", resizeHandler);
			
			$(document).unbind("mousedown",checkBackgroundMouseDown);
			
			if(plugin_settings.closeWhenEscPressed)
			{
				$(document).unbind("keyup", checkKeyUp);
			}*/
		}
			
		//--------------------
		function initListaThumbs () {
			
			//item-zoom-image
			if($(wrapper_geral.find(".zoom-footer .item-zoom-image.active")).size()>0)
			{
				index_active = $(wrapper_geral.find(".zoom-footer .item-zoom-image.active")).attr('alt');
			}

			

			$(wrapper_geral.find(".zoom-footer .item-zoom-image")).each(function(index, element) {
				
				if(index == index_active){
					$(element).addClass('active');
					_imageLoad = $(element).attr('href');

				}



				$(element).attr("alt",index);
				
				
				
				$(element).click(function(){
									
					if(!$(element).hasClass("active")){
						changeImage({bt:$(this)});
					}//end if
					
					return false;
				});//end click
			});//end each
		
		}//end lista thumbs
		var timeDelayLoadImage;
		function changeImage (obj_){
			
			if($(".active",obj_.bt.parent()).size() > 0)
			{
				$(".active",obj_.bt.parent()).removeClass("active");
			}
			
			obj_.bt.addClass("active");
			_imageLoad = obj_.bt.attr("href");
			index_active = obj_.bt.attr("alt");
			
			clearTimeout(timeDelayLoadImage);
			addLoader ();
			timeDelayLoadImage = setTimeout(function()
								{
									loadImage ();
								}, 2000);
			
		}
		
		function addLoader ()
		{
			if(_data.loader != "")
			{
				var _body = jQuery(document.body);
				if($('.loader_overlay').size() > 0){	$('.loader_overlay').remove();}
				var _loader = $("<div class='loader_overlay'><img src='"+_data.loader+"'></div>");
				_body.append(_loader);
			}

		}
		
		function initSetas () {
			
			var _setaEsq = $(wrapper_geral.find("#before"));
			var _setaDir = $(wrapper_geral.find("#next"));
			
			var _length = $(wrapper_geral.find(".zoom-footer .item-zoom-image")).length;
			
			if(_length==1){
				_setaDir.hide();
				_setaEsq.hide();
			}
			
			_setaDir.click(function(){
				if(index_active < _length-1){
					index_active++;
				}else{
					index_active = 0;
				}
				changeImageBySeta ();
				//-------
				return false;
			});
			
			_setaEsq.click(function(){
				if(index_active == 0){
					index_active = _length-1;
				}else{
					index_active --;
				}
				changeImageBySeta ();
				//-------
				return false;
			});
		
		}//end init setas
		
		function changeImageBySeta () {
			$(wrapper_geral.find(".zoom-footer .item-zoom-image")).each(function(index, element) {
				if(index_active==index){
					changeImage({bt:$(this)});
				}
			});
		}
		
		//--------------------
		//bts de zoom
		
		function zoomIn () {
			
			var _drag = $(wrapper_geral.find("#barra #scroll"));//barra
			var _box = $(wrapper_geral.find("#barra"));
			
			var _maxValue = (_data.sliderOrientation == "horizontal") ? _box.width() - _drag.width() :  _box.height() - _drag.height();
			
			if(posDragMove<_maxValue){
				posDragMove += moveDrag;
				if(_data.sliderOrientation == "horizontal")
				{
					_drag.css({left:posDragMove});
				}
				else
				{
					_drag.css({top:posDragMove});
				}
				
				reposImage();
			}else{
				//do nothing
			}
			
			return false;
			
		}
		
		function zoomOut () {
			
			var _drag = $(wrapper_geral.find("#barra #scroll"));//barra
			var _box = $(wrapper_geral.find("#barra"));
			
			var _maxValue = (_data.sliderOrientation == "horizontal") ? _box.width() - _drag.width() :  _box.height() - _drag.height();
			
			if(posDragMove>0){
				posDragMove -= moveDrag;
				
				if(_data.sliderOrientation == "horizontal")
				{
					_drag.css({left:posDragMove});
				}
				else
				{
					_drag.css({top:posDragMove});
				}
				reposImage();
			}else{
				//do nothing
			}
			
			return false;
			
		}
		
		//------------------------------------
	
		function resizeHandler () {
			
			var _wrapper = wrapper_geral.find(".wrapper");
			var _width = $(window).width()-(_data.positionZoom.left+_data.positionZoom.right);
			var _height = $(window).height() - (_data.positionZoom.top+_data.positionZoom.bottom) - wrapper_geral.find(".zoom-footer").height()-$("#aba-lista").height();
			_wrapper.css({width:_width, height:_height});
			
			reposImage ();
			
		}//end resizeHandler
		
		//------------------------------------
		function loadImage () {
			var _body = jQuery(document.body);
						
			addLoader ();
			
			var img = new Image();
						
			wrapper_geral.find(".wrapper").append($(img));
			
			_wrapperResize = wrapper_geral.find(".wrapper .imagem-ativa");
			
			$(img).load(function(){
				
				//remove a imagem caso exista
				if(wrapper_geral.find(".wrapper .imagem-ativa").size() > 0)
				{
					wrapper_geral.find(".wrapper .imagem-ativa").remove();
				}
				
				firstLoad = false;
				
				if(_data.loader != "")
				{
					if($('.loader_overlay').size() > 0){	$('.loader_overlay').remove();}
				}
				
				$(this).addClass('imagem-ativa');
				
				wrapper_geral.find(".wrapper").empty();		
				wrapper_geral.find(".wrapper").append($(this));
				
				//image loaded
				_wrapperResize = wrapper_geral.find(".wrapper img");							
				_wrapperResize.css({ display:"block", opacity:0});
				_wrapperResize.animate({opacity:1},300);
				_wrapperResize.addClass('dragme');
				
				_originalWidth = _wrapperResize.width();
				_originalHeight = _wrapperResize.height();
								
				var _drag = wrapper_geral.find("#barra #scroll");//barra
				
				if(_data.sliderOrientation == "horizontal")
				{
					_drag.css({left:0});
				}
				else
				{
					_drag.css({top:0});
				}
							
				reposImage();
			
			}).attr({
			  src: _imageLoad
			}).error(function(){
			  //do something if image cannot load
			});
			
		}//end loadImage
		
		//------------------------------------
		function reposImage () {
		
			var _drag = wrapper_geral.find("#barra #scroll");//barra
			var _box = wrapper_geral.find("#barra");
			var posDrag = (_data.sliderOrientation == "horizontal") ? parseFloat(getSize(_drag,"left")) : parseFloat(getSize(_drag,"top"));
			var _newWid;
			var _newHei;
			var _posMaxDrag = (_data.sliderOrientation == "horizontal") ? _box.width()-_drag.width() : _box.height()-_drag.height();
					
			if(_wrapperResize)	{
				if(!firstLoad){//checa se foi a primeira vez que a imagem foi carregada
					
					firstLoad = true;
					
					scaleResize = Math.min(_wrapper.width()/_originalWidth,_wrapper.height()/_originalHeight);
					
					//checa se a imagem é menor que a altura ou a largura do wrapper com a mascara
					if(scaleResize < 1){
					}else{
						scaleResize = 1;
					}
					
					if(_data.resizeInitial)//redimensiona imagem para ela caber no frame de visualização
					{
						_newWid = _originalWidth*scaleResize;
						_newHei = _originalHeight*scaleResize;
					}
					else
					{
						_newWid = _originalWidth;
						_newHei = _originalHeight;
						_drag.css({left:_posMaxDrag});
						posDrag = parseFloat(getSize(_drag,"left"));
					}
					
				}else{
					
					/*
					//valores conhecidos
					f(0) = 443; > f(0) = _wrapper.height();//altura máxima da imagem reduzida para caber por inteiro na parte visivel
					f(90) = 1053; > f(_posMaxDrag) = _originalHeight;//tamanho máximo da imagem com relação diretamente proporcional ao movimento máximo do drag
					//--------

					f(0) = _wrapper.height(); >  b = _wrapper.height();
					f(_posMaxDrag) = a[_posMaxDrag] + _wrapper.height(); > _originalHeight = a[_posMaxDrag] + _wrapper.height();
					a[_posMaxDrag] = _originalHeight - _wrapper.height();
					a = (_originalHeight - _wrapper.height())/_posMaxDrag;
					f(x) = (((_originalHeight - _wrapper.height())/_posMaxDrag)*x) + _wrapper.height();*/
					
					var _newHeightImg = (((_originalHeight - _wrapper.height())/_posMaxDrag)*posDrag) + _wrapper.height();
					var _scale = _newHeightImg / _originalHeight;
					
					_newHei = _newHeightImg;
					_newWid = _originalWidth * _scale;
										
				}
				
				posDragMove = posDrag;
				
				var _center_x = (_wrapper.width()-_newWid)/2;
				var _center_y = (_wrapper.height()-_newHei)/2;
				
				_left = _center_x;
				_top = _center_y;
				
				_wrapperResize.css({width:_newWid, height:_newHei, left:_left, top:_top});
				
			}	
				
		}//end reposImage
		
		//===================================================================================
		//===================================================================================   
	};
			
})(jQuery);