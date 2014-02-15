<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

if ( ! $woocommerce->cart->coupons_enabled() )
	return;

$info_message = apply_filters('woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'woocommerce' ));
?>

<div class="woocommerce-info"><?php echo $info_message; ?> <a href="#" class="showcoupon"><?php _e( 'Click here to enter your code', 'woocommerce' ); ?></a>

<form class="checkout_coupon form-horizontal" method="post" style="display:none">

	<div class="form-row control-group validate-required" id="coupon_code_field">
	    <label for="coupon_code" class="control-label">
			<input type="submit" class="btn btn-info" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />
	    </label>
	    <div class="control-field">
			<input type="text" class="input-text" name="coupon_code" id="coupon_code" placeholder="Купон" value="" />
	    </div>
	</div>

	<div class="clear"></div>
</form></div>