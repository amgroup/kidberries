/*

*/
;

jQuery(document).ready(function($){
	var timeOut;
	hidecontent = function(id) {
		var $this = $(id);
		if( ! $this.hasClass("mouseover") ) {
			$( $this.data("menu") ).removeClass("active");
			$this.removeClass("mouseover").slideUp();
		}
	};

    $("li.crumb[data-toggle]").each( function() {
		var $this = $(this);
		var $content = $( $this.data("toggle") );

	    if( $content.length > 0 ) {
			$content.bind( "mousewheel", function(e, delta) { this.scrollLeft -= (delta * 40); e.preventDefault();} );
			$content.bind( "mouseenter", function(e) { $(this).addClass("mouseover"); } );
			$content.bind( "mouseleave", function(e) { $(this).removeClass("mouseover").slideUp(); $( $(this).data("menu") ).removeClass("active"); });
	    }

	    $(this).bind( "mouseenter", function() {
	    	var $this = $(this);
	    	var opened = 0;
	    	if( $("li.crumb.active").length > 0 )
	    		opened = 1;

	    	$("li.crumb.active").each(function(){
	    		var $this = $(this);
	    		$( $this.data("toggle") ).removeClass("mouseover").hide();
	    		$this.removeClass("active");
	    	});

	    	if( opened )
	    		$( $this.data("toggle") ).show();
	    	else
	    		$( $this.data("toggle") ).slideDown();

			$this.addClass("active");
	    });

	    $(this).bind( "mouseleave", function() {
			if( timeOut ) clearTimeout( timeOut );
			timeOut = setTimeout( 'hidecontent("' + $(this).attr("data-toggle") + '")', 800 );
	    });
    });

});
