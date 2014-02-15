<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

$woocommerce->show_messages();
?>

<!-------------------------------------------------------------------------------------->
								
<ul class="checkout-types">
	<li>
		<button type="button" title="Оформить заказ" class="button green right" onclick="javascript:jQuery('form .button.checkout-button').click();">
			Оформить заказ
		</button>
	</li>
</ul>
<p>&nbsp;<br>&nbsp;<br>&nbsp;</p>
<br class="clear">

<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post">
	<fieldset>
	
<table id="shopping-cart-table" class="data-table cart-table">
	
	<colgroup>
		<col width="1">
		<col>
		
		<col width="1">
        
        <col width="1">
        <col width="1">
	</colgroup>
	
	<thead>
		<tr class="first last">
			<th rowspan="1">&nbsp;</th>
            <th rowspan="1"><span class="nobr">Название товара</span></th>
            
            <th class="a-center" colspan="1"><span class="nobr">Цена за шт.</span></th>
            
            <th class="a-center" colspan="1">Итого</th>
            <th rowspan="1" class="a-center">&nbsp;</th>
        </tr>
	</thead>

	<tfoot>
		<tr class="first last">
			<td colspan="50" class="a-right last">
				<?php if ( $woocommerce->cart->coupons_enabled() ) : ?>
					<div class="form-horizontal checkout_coupon">
					    <div class="form-row control-group validate-required" id="coupon_code_field">
					        <label for="coupon_code" class="control-label">
								<?php if ( $woocommerce->cart->get_discounts_after_tax() ) : ?>
									<a href="<?php echo add_query_arg( 'remove_discounts', '2', $woocommerce->cart->get_cart_url() ) ?>">Отменить все купоны</a>
									<input type="submit" class="btn btn-info" name="apply_coupon" value="Применить ещё">
								<?php elseif ( $woocommerce->cart->get_discounts_before_tax() ) : ?>
									<a href="<?php echo add_query_arg( 'remove_discounts', '1', $woocommerce->cart->get_cart_url() ) ?>">Отменить все купоны</a>
									<input type="submit" class="btn btn-info" name="apply_coupon" value="Применить ещё">
								<?php else : ?>
									<input type="submit" class="btn btn-info" name="apply_coupon" value="Применить">
								<?php endif; ?>
					        </label>
					        <div class="control-field">
					            <input type="text" class="input-text" name="coupon_code" id="coupon_code" placeholder="Купон" value="">
					        </div>
					    </div>
					</div>
				<?php endif; ?>
				
				

				<!--button type="submit" class="btn btn-link btn-update" style="float: right;" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>">
					<?php _e( 'Update Cart', 'woocommerce' ); ?>
				</button-->
				<input type="submit" class="checkout-button button" name="proceed" value="<?php _e( 'Buy it', 'woocommerce' ); ?>" style='display: none;'" />

				<?php do_action('woocommerce_proceed_to_checkout'); ?>

				<?php $woocommerce->nonce_field('cart') ?>
			</td>
		</tr>
	</tfoot>
    
	<tbody>
	
	<?php
		if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( $_product->exists() && $values['quantity'] > 0 ) {
					?>
	
		<tr class="first last odd">
			
			<td>
			
			<?php
								$thumbnail = apply_filters( 'woocommerce_in_cart_product_thumbnail', $_product->get_image(), $values, $cart_item_key );

								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo $thumbnail;
								else
									printf('<div class="product_in_cart"><a href="%s">%s</a></div>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail );
			?>
			
			</td>
			
			<td style='text-align: left;'>
				<h2 class="product-name">
                    <?php
								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo apply_filters( 'woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key );
								else
									printf('<a class="product_name_cart" href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key ) );

								// Meta data
								echo '<div class="item data"> ' . $woocommerce->cart->get_item_data( $values, true ) . '</div>';

                   				// Backorder notification
                   				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
                   					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
							?>
                </h2>
			</td>
			
			

            <td class="a-right">
				<span class="cart-price">
                    <span class="price">
						<span class="price digit"><?php
								$product_price = get_option('woocommerce_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();
								echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $values, $cart_item_key );
							?></span>
						<span class="price currency"></span>
					</span>            
				</span>
            </td>

           

			<td class="a-right">
				<span class="cart-price">        
					<span class="price">
						<span class="price digit"><?php
								if ( isset( $values['line_total_discounted'] ) && $values['line_total_discounted'] ) {
									$product_price = $values['line_total_discounted'];
								}
								echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price * $values['quantity'] ), $values, $cart_item_key );
//								echo apply_filters( 'woocommerce_cart_item_subtotal', $woocommerce->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key );
							?></span>
						<span class="price currency"></span>
					</span> 
				</span>
            </td>

            <td class="a-center last">
				<table class="product-actions">
					<tr>
			<?php
		
		
			if ( $_product->is_sold_individually() ) {
									//ничего не делаем 
								} else {

									$product_quantity = sprintf( '<input type="hidden" id="%s" name="cart[%s][qty] input-text qty" value="1" class="input-text qty text"/>', $cart_item_key, $cart_item_key );
									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
									?>
										<!--td><a href="#" onclick="document.getElementById('<?echo $cart_item_key;?>').value='2'" type="submit" class="plus-sign" name="plusone" ><i class="glyphicon glyphicon-plus-sign"> </i></a></td-->
									<?
								}


							
			
								?><td><?echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="minus-sign" title="%s"><i class="glyphicon glyphicon-remove-circle"> </i></a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
								?></td></tr></table><?
						?>
			
			</td>
		</tr>
		
		<?php
				}
			}
		}

		?>
		
	</tbody>
</table>

	</fieldset>
</form>

<div class="cart-collaterals">

	<?php //do_action('woocommerce_cart_collaterals'); ?>

	<?php woocommerce_cart_totals(); ?>

	<?php woocommerce_shipping_calculator(); ?>

</div><p>&nbsp;<br>&nbsp;</p>
<ul class="checkout-types">
	<li>
		<button type="button" title="Оформить заказ" class="button green right" onclick="javascript:jQuery('form .button.checkout-button').click();">
			Оформить заказ
		</button>
	</li>
</ul>
<p>&nbsp;<br>&nbsp;</p>
