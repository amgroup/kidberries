<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

$woocommerce->show_messages();
?>

<div class="main-block skew-block">

	<div class="corners-top">
		<div>
			<div>&nbsp;</div>
		</div>
	</div>       

	<div class="content-box">
		<div class="border-bot">
			<div class="border-left">
				<div class="border-right">
					<div class="corner-left-top">
						<div class="corner-right-top">
							<div class="corner-left-bot">
								<div class="corner-right-bot">
<div class="onepage_checkout">
<?php
do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url() ); ?>



<form name="checkout" method="post" class="checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">

    <h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>
    <?php do_action( 'woocommerce_checkout_order_review' ); ?>

	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<div class="col1-set">
			<?php do_action( 'woocommerce_checkout_order_notes' ); ?>
		</div>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">

			<div class="col-1">

				<?php do_action( 'woocommerce_checkout_billing' ); ?>

			</div>

			<div class="col-2">

				<?php do_action( 'woocommerce_checkout_shipping' ); ?>

			</div>

		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

<div style="height: 70px;">
<button type="submit" title="Закончить оформление" name="woocommerce_checkout_place_order" id="place_order"  class="button btn-proceed-checkout btn-checkout btn-red right" value="<?php echo apply_filters('woocommerce_order_button_text', __( 'Place order', 'woocommerce' )); ?>" >
<span>
<span>Завершить</span>
</span>
</button>
</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
</div>

 </div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>                
									</div>
									<div class="corners-bot"><div><div>&nbsp;</div></div></div>
								</div>