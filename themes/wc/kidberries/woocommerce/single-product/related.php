<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;
$crossids = get_post_meta( $product->id, '_crosssell_ids' );
if (!empty($crossids)) {
	$related = $crossids[0];
} else { 
	$related = $product->get_related();
};


if ( sizeof( $related ) == 0 ) return;

$args = apply_filters('woocommerce_related_products_args', array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows' 		=> 1,
	'posts_per_page' 		=> $posts_per_page,
	'orderby' 				=> $orderby,
	'post__in' 				=> $related,
	'post__not_in'			=> array($product->id)
) );

$products = new WP_Query( $args );

$woocommerce_loop['columns'] 	= $columns;

if ( $products->have_posts() ) : ?>

	<div class="box-collateral box-up-sell">

		<h2>Вы возможно также заинтересованы в следующих товарах</h2>

		<table class="products-grid" id="upsell-product-table">
			<tr>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php woocommerce_get_template_part( 'content', 'related-product' ); ?>

			<?php endwhile; // end of the loop. ?>

			</tr>
		</table>
	</div>

<?php endif;

wp_reset_postdata();
