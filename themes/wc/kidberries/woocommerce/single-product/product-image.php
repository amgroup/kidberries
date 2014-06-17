<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;

?>

<style>
    #slideshow { position: relative; width: 510px; height: 510px; }
    #slideshow > * { position: absolute; }
</style>


    <div id="slideshow" class="product-image">
        <?php
            if( $product->product_type == 'variable' ) {
                $available_variations = $product->get_available_variations();

                foreach ( $available_variations as $id => $variation ) {
                    $image_id = get_post_thumbnail_id( $variation['variation_id'] );
                    $icon_id  = get_post_meta( $variation['variation_id'], '_thumbnail_icon_id', true );

                    if( $image_id && ! $icon_id )     $icon_id = $image_id;
                    elseif( $icon_id && ! $image_id ) $image_id = $icon_id;

                    if( $image_id ) {
                        $variations_images[ $image_id ] = array(
                            'image'  => wp_get_attachment_image_src( $image_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ),
                            'zommed' => wp_get_attachment_image_src( $image_id, apply_filters( 'single_product_zoomed_thumbnail_size', 'shop_zoomed' ) ),
                            'title'  => esc_attr( get_the_title( $image_id ) ),
                        );
                    }
                }

                foreach ( $variations_images as $attachment_id => $variations_image ) {
                    if( is_array($variations_image) ) {
                        $zoomed = $variations_image['zommed'];
                        $image  = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
                        $title  = $variations_image['title'];

                        echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" target="_blank" itemprop="image" class="woocommerce-main-image" title="%s">%s</a>', esc_url( $zoomed[0] ), $title, $image ), $post->ID );
                    }
                }
            }

            $attachment_ids = $product->get_gallery_attachment_ids();
            if ( $attachment_ids ) {
                    foreach ( $attachment_ids as $attachment_id ) {
                            $image              = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
                            $image_title        = esc_attr( get_the_title( $attachment_id ) );
                            $image_link         = wp_get_attachment_url( $attachment_id );

                            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" target="_blank" itemprop="image" class="woocommerce-main-image" title="%s">%s</a>', $image_link, $image_title, $image ), $post->ID );
                    }
            }

        ?>
    </div>
