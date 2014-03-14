<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $woocommerce;

$attachment_ids    = $product->get_gallery_attachment_ids();
$variations_images = array();
if( $product->product_type == 'variable' ) {
    $available_variations = $product->get_available_variations();

    foreach ( $available_variations as $id => $variation ) {
        $image_id = get_post_thumbnail_id( $variation['variation_id'] );
        $icon_id  = get_post_meta( $variation['variation_id'], '_thumbnail_icon_id', true );

	if( $image_id && ! $icon_id )     $icon_id = $image_id;
        elseif( $icon_id && ! $image_id ) $image_id = $icon_id;

	if( $image_id ) {
        $is_in_stock = $variations_images[ $image_id ]['is_in_stock'] ? $variations_images[ $image_id ]['is_in_stock'] : $variation['is_in_stock'];
    	    //$variations_images[ $icon_id ]  = false;
		$variations_images[ $image_id ] = array(
		    'image'  => wp_get_attachment_image_src( $image_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ),
		    'zommed' => wp_get_attachment_image_src( $image_id, apply_filters( 'single_product_zoomed_thumbnail_size', 'shop_zoomed' ) ),
		    'small'  => wp_get_attachment_image( $icon_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) ),
		    'title'  => esc_attr( get_the_title( $image_id ) ),
		    'is_in_stock' => $is_in_stock,
		);
	}
    }
}
?>
<div style="display: inline-block;">
<ul class="thumbnail_selectors">
<?php
foreach ( $variations_images as $attachment_id => $variations_image ) {
    if( is_array($variations_image) ) {
        $zoomed = $variations_image['zommed'];
        $image  = $variations_image['image'];
        $small  = $variations_image['small'];
		
        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( "<li class=\"thumbnail_selector" . ($variations_image['is_in_stock']?'':' not_in_stock') . "\"><a href=\"%s\" class=\"cloud-zoom-gallery\" rel=\"useZoom: 'image-zoom', smallImage: '%s'\" >%s</a></li>", esc_url( $zoomed[0] ), esc_url( $image[0] ), $small ), $attachment_id, $post->ID, 'variations_image' );
    }
}
?>
</ul>
</div>
<?php
if ( $attachment_ids ) {
	echo '<ul>';

	$loop = 0;
	$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

	foreach ( $attachment_ids as $attachment_id ) {
		if( isset( $variations_images[ $attachment_id ] ) ) continue;

		$classes = array( 'zoom' );

		if ( $loop == 0 || $loop % $columns == 0 )
			$classes[] = 'first';

		if ( ( $loop + 1 ) % $columns == 0 )
			$classes[] = 'last';

		$zoomed      = wp_get_attachment_image_src( $attachment_id, apply_filters( 'single_product_zommed_thumbnail_size', 'shop_zoomed' ) );
                $image       = wp_get_attachment_image_src( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
		$small       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
		$image_class = esc_attr( implode( ' ', $classes ) );
		$image_title = esc_attr( get_the_title( $attachment_id ) );

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( "<li><a href=\"%s\" class=\"cloud-zoom-gallery\" rel=\"useZoom: 'image-zoom', smallImage: '%s'\" >%s</a></li>", esc_url( $zoomed[0] ), esc_url( $image[0] ), $small ), $attachment_id, $post->ID, $image_class );
		$loop++;
	}
	echo '</ul>';
}
?>

