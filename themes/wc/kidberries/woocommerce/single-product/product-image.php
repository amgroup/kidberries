<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce;

?>
<p class="product-image">

	<?php
		if ( has_post_thumbnail() ) {

			$image       		= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
			$image_title 		= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$attachment_count   = count( get_children( array( 'post_parent' => $post->ID, 'post_mime_type' => 'image', 'post_type' => 'attachment' ) ) );
            $zoomed             = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), apply_filters( 'single_product_large_thumbnail_size', 'shop_zoomed' ) );
        
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" id="image-zoom" class="cloud-zoom" title="%s" rel="zoomWidth:600,zoomHeight:600,position:' . "'right'" . ',adjustX:30,adjustY:-3,tintenable:0,zoomeffect:1,tintOpacity:0.4,lensOpacity:1,showTitle:1,tint:' . "'#000000'" . '">%s</a>', esc_url( $zoomed[0] ), $image_title, $image ), $post->ID );
		} else {
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', woocommerce_placeholder_img_src() ), $post->ID );
		}
	?>

	

</p>