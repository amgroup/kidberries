<?php
/**
 * Checkout shipping information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;
?>

<div class="shipping_address" id="checkout_shipping_address">
<?php if ( $woocommerce->session->chosen_shipping_type == 'pickup' && $woocommerce->session->chosen_shipping_method == 'shoplogistics_delivery' ) : ?>

	    <?php if( $woocommerce->customer->shipping_city || $woocommerce->customer->shipping_address ) : ?>
		<h3><?php _e( 'Shipping Address', 'woocommerce' ); ?></h3>
		<?php if(  $woocommerce->customer->shipping_city ) : ?>
			<h2>Пункт самовывоза - <?php echo $woocommerce->customer->shipping_city;?></h2>
		<?php endif; ?>
		<?php if( $woocommerce->customer->shipping_address && $woocommerce->session->shipping_method_instruction ) : ?>
			<p><?php echo implode("</p><p>", $woocommerce->session->shipping_method_instruction); ?></p>
		<?php endif; ?>
	    <?php endif; ?>
<?php endif; ?>

<?php if ( ($woocommerce->session->chosen_shipping_type != 'pickup') && ( $woocommerce->cart->needs_shipping() || get_option('woocommerce_require_shipping_address') == 'yes' ) && ! $woocommerce->cart->ship_to_billing_address_only() ) : ?>
	    <?php if( sizeof( $checkout->checkout_fields['shipping'] ) ) : ?>
		<h3><?php _e( 'Shipping Address', 'woocommerce' ); ?></h3>

		<?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>

		<div class="form-horizontal">
		<?php foreach ($checkout->checkout_fields['shipping'] as $key => $field) : ?>
			<?php kidberries_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
		<?php endforeach; ?>
        </div>

		<?php do_action('woocommerce_after_checkout_shipping_form', $checkout); ?>
	    <?php endif; ?>
<?php endif; ?>
</div>