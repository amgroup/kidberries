/*!
 * Variations Plugin 
 */
;


(function ( $, window, document, undefined ) {

	ArrayLength = function(a) {
		var l = 0;
		for( var key in a )
			if( typeof a[key] == 'string' )
				l++;
		return l;
	}

	ArrayhasArray = function(a, b) {
		var c = $.extend(true, {}, b);
		for( var key in a )
			if( typeof a[key] == 'string' && a[key] == c[key] )
				delete c[key];
		return ArrayLength(c) > 0 ? false : true;
	}

	function set_field(selector,html) {
		var $e = $(selector);
		var name = $e.prop("tagName");

		if( ! $e.attr("data-default") ) {
			if( name == 'INPUT' || name == 'SELECT' ) $e.attr("data-default", $e.val() );
			else $e.attr("data-default", $e.html() );
		}

		if( name == 'INPUT' || name == 'SELECT' ) $e.val( html );
		else $e.html(html);
	}

	function reset_field( selector ) {
		var $e = $(selector);
		var name = $e.prop("tagName");

		if( name == 'INPUT' || name == 'SELECT' ) $e.val( $e.attr("data-default" ) );
		else $e.html( $e.attr("data-default" ) );
	}

	function set_images( selector, variant ) {
		var $box = $(selector);

		if( ! $box.attr( "data-default_src" ) ) {
			$box.attr({
				"data-default_src"    : $box.find("img").attr("src"),
				"data-default_zoomed" : $box.find("a").attr("href"),
				"data-default_title"  : $box.find("a").attr("title")
			})
		}
		$box.find("img").attr("src", variant.image_src );
		$box.find("a").attr("href",  variant.image_zoomed );
		$box.find("a").attr("title", variant.image_title );

		if( jQuery.fn.CloudZoom ) $('#image-zoom').CloudZoom();
	}

	function reset_images( selector ) {
		var $box = $(selector);

		$box.find("img").attr("src", $box.attr("data-default_src") );
		$box.find("a").attr("href",  $box.attr("data-default_zoomed") );
		$box.find("a").attr("title", $box.attr("data-default_title") );

		if( jQuery.fn.CloudZoom ) $('#image-zoom').CloudZoom();
	}


	function set_changeables( variant ) {
		if( variant.taxonomy ) {
			for( var name in variant.taxonomy ) {
				var attribute = variant.taxonomy[name];
				var selector = '.changeable.attribute.' + name;
				for( field in attribute ) {
					set_field( selector + '.' + field , attribute[field] );
				}
			}
		}
	}

	function reset_changeables() {
		$('.changeable.attribute').each(function(){
			$this = $(this);
			$this.html( $this.attr("data-default") );
		});
	}


	$(document).ready(function(){

		var $form = $("form.variations_form");
		var $select = $form.find("select");
		
		function set_variant(variant) {
			// "Autocomplete" when only one choice
			for( var name in variant.attributes ) {
				$("select[name='" + name + "']").val( variant.attributes[name] ).addClass("complete");
			}

			// Set main product attributes
			set_field( "input[name='variation_id']", variant.variation_id );

			set_field( ".availability.stock_container > .stock", variant.availability_html );
			set_field( "#sku", variant.sku );
			set_field( ".product_weight", variant.weight );
			set_field( ".product_dimensions", variant.dimensions );
			set_field( ".actions-box .price-box ", wpap( '<span>' + variant.price_html + '</span>' ) );

			set_images( ".product-img-box .product-image", variant );
			set_changeables( variant );
		}

		function reset_selects(event) {
			event.preventDefault();
			reset_images( ".product-img-box .product-image" );
			$select.removeClass("complete").val("");
			$("*[data-default]").each(function() {
				var $this = $(this);
				$this.html( $this.attr("data-default") );
			});
			reset_changeables();
		}

		$("button.reset_variations").on('click', function(e){reset_selects(e); $(this).attr("disabled","disabled");});
		
		$select.focus(function(){
			var $this   = $(this);
			var name  = $this.attr("name");	
			var variant = eval( $("form.variations_form").attr("data-product_variations") );
			var vector  = new Array();

			$select.each( function(){
				var $this = $(this);
				if( $this.val() != "" && name != $this.attr("name") )
					vector[ $this.attr("name") ] = $(this).val()
			});
			
			$this.find("option[value!='']").each(function() {
				var $this = $(this);
				var s = new Array();
				s[ name ] = $this.attr("value");

				if( ArrayLength(vector) > 0  ) {
					$this.addClass("disabled").attr("disabled", "disabled");
					for( var i = variant.length-1; i >= 0; i-- ) {
						if( ArrayhasArray( variant[i].attributes, $.extend(true, s, vector) ) && variant[i].is_in_stock ) {
							$this.removeClass("disabled").removeAttr("disabled");
							break;
						}
					}
				} else {
					$this.removeClass("disabled").removeAttr("disabled");
				}
			});
		});

		$select.change(function(e){
			var variant = eval( $("form.variations_form").attr("data-product_variations") );

			$select.each( function(){
				var $this = $(this);
				var value = $this.val();
				var name  = $this.attr("name");	

				if( '' !== value ) $this.addClass( "complete" );
				else $this.removeClass("complete");

				if( value != "" ) {
					for( var i = variant.length-1; i >= 0; i-- ) {
						if( variant[i].attributes[ name ] != value ) {
							variant.splice(i,1);
						}
					}
				}
			});
			if( $select.hasClass('complete') )
				$("button.reset_variations").removeAttr("disabled");

			if(variant.length == 1) {
				set_variant( variant[0] );
				//$("button[type='submit']").removeAttr("disabled");
			} else {
				//$("button[type='submit']").attr({disabled:"disabled"});
			}
		});
	});

})( jQuery, window, document );