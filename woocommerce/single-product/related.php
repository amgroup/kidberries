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
<script type="text/javascript">decorateTable('upsell-product-table')</script>
    <script type="text/javascript">    
		var grids = $$('.products-grid');
		grids.each(function(n){					
				var columns = n.select('td');					
				var max_height = columns[0].getHeight();
				var col_top_indent = parseFloat(columns[0].getStyle('padding-top'));
				var col_bot_indent = parseFloat(columns[0].getStyle('padding-bottom'));
				max_height = max_height - col_top_indent - col_bot_indent;					
				var boxes = n.select('td .product-box');										
				var box_top_indent = parseFloat(boxes[0].getStyle('padding-top'));
				var box_bot_indent = parseFloat(boxes[0].getStyle('padding-bottom'));
				boxes.each(function(b){														
					b.setStyle({
						height: max_height - box_top_indent - box_bot_indent + 'px'
					});					
				 });
			});	
	</script>
	</div>

<?php endif;

wp_reset_postdata();
