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
<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />

<?php do_action('woocommerce_before_add_to_cart_form');?>
<table>
	<tr class="variations">
		<td class="variation-price-cont">
			<div class="price-box">
				<p class="single_price_wrap">
                    <?php if ( $product->is_on_sale() ) : ?>
                        <div class="price-box onsale">
                            <span class="old-price price" id="old-price-<?php echo $product->id; ?>-new">
                                <span class="price digit"><?echo $product->regular_price;?></span>
                            </span>
                            
                            <span class="special-price price">
                                <span class="price digit"><?echo $product->price;?></span>
                                <span class="price currency"> <?php echo $currency_symbol; ?></span>
                            </span>
                        </div>
                    <?php else: ?>
                        <div class="price-box regular">
                            <span class="regular-price price" id="product-price-<?php echo $product->id; ?>-new">
                                <span class="price digit"><?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?></span>
                                <span class="price currency"> <?php echo $currency_symbol; ?></span>
                            </span>
                        </div>
                    <?php endif; ?>
				</p>
			</div>
            <div class="availability">
                <?php if ($product->is_in_stock()) : ?>
                    <span class="stock in-stock">Есть в наличии</span>
                <?php else : ?>
                    <span class="stock out-of-stock">Нет в наличии</span>
                <?php endif; ?>
            </div>
		</td>
		<td class="variation-variants-cont">
		</td>
        <td class="variation-buttons-cont simple" >
	    <?php if ( $product->is_in_stock() ) : ?>
            <div class="single_variation_wrap">
                <div class="variations_button">
                    <?php woocommerce_quantity_input(); ?>
                    <button type="submit" title="В корзину" class="button green">В корзину</button>
                    <div class="buttons_or">или</div>
                    <button type="submit" title="Купить в один клик" class="button red" onclick="buyitnow()">Купить</button>
                    <?php do_action('woocommerce_after_add_to_cart_button'); ?>
                </div>
            </div>
	    <?php endif; ?>
		</td>
	<tr>
</table>
<?php do_action('woocommerce_after_add_to_cart_form'); ?>	
<!-- /simple product form content -->
