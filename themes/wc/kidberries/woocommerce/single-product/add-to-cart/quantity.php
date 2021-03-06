<?php
/**
 * Single product quantity inputs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<?php /*/ ?>
<div class="quantity">
<label for="qty"><span>Кол-во:</span>
<input type="number" step="<?php echo esc_attr( $step ); ?>" id="qty" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( $max_value ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" size="4" class="input-text qty" title="<?php _ex( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" class="input-text qty text" maxlength="12" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
</div>
<?php /*/ ?>
<input type="hidden" id="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" />
