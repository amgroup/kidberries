<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
	
	$classes[] .= ' item';
?>

<td>
	<div class="product-box" style='max-width: 286px'>
		<a href="<?the_permalink();?>" title="<?the_title();?>" class="product-image">
			<?woocommerce_template_loop_product_thumbnail();?>
		</a>
                    
		<div class="price-box alingcenter">
			<span class="regular-price price" id="product-price-841-upsell">
				<span class="price digit"><?echo $product->price;?></span>
				<span class="price currency"> руб.</span>
			</span>
		</div>

		<h3 class="product-name product-name2">
			<a href="<?the_permalink();?>" title="<?the_title();?>"><?the_title();?></a>
		</h3>
                                    
	</div>
</td>
	
	