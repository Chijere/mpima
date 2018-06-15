/* JS Document */

/******************************

[Table of Contents]

1. lightSlider
2. PhotoSwipe


******************************/

      $(document).ready(function() {
      	/*###########################
      	1. LightSlider Plugin
      	 ##########################*/
        
            $('#image-gallery').lightSlider({
                gallery:true,
                item:1,
                thumbItem:12,
                slideMargin: 0,
                speed:500,
                controls:false,
                loop:true,
                onSliderLoad: function() {
                    $('#image-gallery').removeClass('cS-hidden');
                }  
            });  
      	/*###########################
      	2. PhotoSwipe Plugin
      	 ##########################*/
      
    var $pswp = $('.pswp')[0];
    var image = [];

		    $('#image-gallery').each( function() {
		        var $pic     = $(this),
		            getItems = function() {
		                var items = [];
		                $pic.find('li').each(function() {
		                    var $src   = $(this).data('src'),
		                        $size   = $(this).data('size').split('x'),
		                        $width  = $size[0],
		                        $height = $size[1];

		                    var item = {
		                        src : $src,
		                        w   : $width,
		                        h   : $height
		                    }

		                    items.push(item);
		                });

		                return items;
		            }

		        var items = getItems();
		        		//slice the array in half coz its taking thumbnails as well
		                var half_length = Math.ceil(items.length / 2);    
						var items = items.splice(0,half_length);

		        $.each(items, function(index, value) {
		            image[index]     = new Image();
		            image[index].src = value['src'];
		        });

		        $pic.on('click', 'li', function(event) {
		            event.preventDefault();
		            
		            var $index = $(this).index();
		            var options = {
		                index: $index,
		                bgOpacity: 0.91,
		                showHideOpacity: true
		            }

		            var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
		            lightBox.init();
		        });
		    });

      });

