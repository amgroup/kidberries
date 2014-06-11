;
/*

*/
jQuery(document).ready(function($){
  $('.product-thumbnails .product-thumbnail').click(function(e){
    e.preventDefault();

    var $this = $(this);
    var $big  = $('.product-img-box .product-image img');
    var $a    = $('.product-img-box .product-image a');
    var image = $this.data('image');
    var link  = $this.data('link');

    $big.attr( 'src', image );
    $a.attr( 'href', link );
    $big.trigger('changed');
  });

  $('.product-img-box .product-image img').on('changed', function(){
    $('.product-img-box .product-image').addClass('slideshow-stopped');
  });


  if( $("#slideshow > *").length < 2 ) return;

  $("#slideshow > *:gt(0)").hide();
  setInterval( function(){
    if( ! $('.product-img-box .product-image').hasClass('slideshow-stopped') )
      $('#slideshow > *:first').fadeOut(1000).next().fadeIn(1000).end().appendTo('#slideshow');
  }, 3000 );

});