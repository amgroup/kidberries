<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $checkout_step;

$woocommerce->show_messages();
?>


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


<p class="to_cart"><a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="btn btn-link"><i class="glyphicon glyphicon-circle-arrow-left"></i><i class="glyphicon glyphicon-shopping-cart"></i>  Вернуться в корзину и <strong>изменить заказ</strong></a></p>

<?php if ( $woocommerce->cart->get_discounts_after_tax() ) : ?>
	<p class="to_cart"><a class="btn btn-link" href="<?php echo add_query_arg( 'remove_discounts', '2', $woocommerce->cart->get_cart_url() ) ?>">Вернуться в корзину и <strong>отменить все купоны</strong></a></p>
<?php elseif ( $woocommerce->cart->get_discounts_before_tax() ) : ?>
	<p class="remove_coupon"><a class="btn btn-link" href="<?php echo add_query_arg( 'remove_discounts', '1', $woocommerce->cart->get_cart_url() ) ?>">Вернуться в корзину и <strong>отменить все купоны</strong></a></p>
<?php endif; ?>

<form name="checkout" method="post" class="checkout" action="<?php echo esc_url( $get_checkout_url ); ?>?completed">

    <h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>
    <?php do_action( 'woocommerce_checkout_order_review' ); ?>

	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
		
            <table class="checkout-fields-cont">
                <tr>
                    <td class="empty border"></td>
                    <td colspan="2"><?php do_action( 'woocommerce_checkout_order_notes' ); ?></td>
                <tr id="customer_details">
                    <td class="empty border step"><span><?php echo $checkout_step++; ?></span></td>
		            <td class="billing-fields"><?php do_action( 'woocommerce_checkout_billing' ); ?></td>
		            <td class="shipping-fields"><?php do_action( 'woocommerce_checkout_shipping' ); ?></td>
        		</tr>
		    </table>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

    <table class="checkout-submit-cont">
        <tr>
            <td class="empty border step last"><span><?php echo $checkout_step++; ?></span></td>
            <td class="submit-order">
                <button type="submit" title="Закончить оформление" name="woocommerce_checkout_place_order" id="place_order"  class="button red" value="<?php echo apply_filters('woocommerce_order_button_text', __( 'Place order', 'woocommerce' )); ?>" >
                        Завершить
                </button>
            </td>
        </tr>
    </table>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
</div>

