<?php
/**
 * Checkout billing information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

$shiptobilling = ( get_option('woocommerce_ship_to_same_address') == 'yes' ) ? 1 : 0;
$shiptobilling = apply_filters('woocommerce_shiptobilling_default', $shiptobilling);

if ( ! empty( $_POST ) ) {
    $shiptobilling = $checkout->get_value('shiptobilling') ? $checkout->get_value('shiptobilling') : $shiptobilling;
}

foreach ( array('billing','shipping') as $section ) {
	foreach ( array('address_2','company','state') as $field ) {
		unset( $checkout->checkout_fields[ $section ][ join('_', array( $section, $field ) ) ] );
	}
}
$checkout->checkout_fields['shipping']['shipping_phone'] = $checkout->checkout_fields['billing']['billing_phone'];

?>
<div class="billing_address" id="checkout_billing_address">
<?php if ( $woocommerce->session->chosen_shipping_type == 'pickup' ) : $shiptobilling = 1; ?>
	<h3>Ваши контактные данные</h3>
<?php elseif ( $woocommerce->cart->ship_to_billing_address_only() && $woocommerce->cart->needs_shipping() ) : ?>
	<h3><?php _e( 'Shipping', 'woocommerce' ); ?></h3>
<?php else : ?>
	<h3>
		<span><?php _e( 'My Address', 'woocommerce' ); ?></span>
	</h3>
<?php endif; ?>
	<input id="shiptobilling-checkbox" value="on" checked="checked" type="hidden" name="shiptobilling" />

<?php do_action('woocommerce_before_checkout_billing_form', $checkout ); ?>
<div class="form-horizontal">
<?php foreach ($checkout->checkout_fields['billing'] as $key => $field) : ?>
	<?php kidberries_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
<?php endforeach; ?>
</div>
<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>



<?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>

	<?php if ( $checkout->enable_guest_checkout ) : ?>

		<p class="form-row">
			<input class="input-checkbox" id="createaccount" <?php checked($checkout->get_value('createaccount'), true) ?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox">Стать постоянным покупателем</label>
		</p>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

	<div class="form-horizontal create-account"<?php if( ! $checkout->get_value('createaccount') ) echo ' style="display: none;"'; ?>">

		<p style="display: none;"><small>Вы сможете видеть историю заказов, проще делать новые и получать скидки.</small></p>

		<?php foreach ($checkout->checkout_fields['account'] as $key => $field) : ?>
		<?php unset( $field['label_class'] ); unset( $field['placeholder'] ); $field['class'] = array('form-row-wide')?>
			<?php kidberries_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
		<?php endforeach; ?>

		<div class="clear"></div>

	</div>

	<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>

<?php endif; ?>
</div>
