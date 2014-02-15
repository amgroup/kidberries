<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product, $currency_symbol;

if ( ! $product->is_purchasable() ) return;
?>

<!-- simple product form content -->
<?php do_action('woocommerce_before_add_to_cart_form');?>
<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
<?php do_action('woocommerce_after_add_to_cart_form'); ?>	
<!-- /simple product form content -->
