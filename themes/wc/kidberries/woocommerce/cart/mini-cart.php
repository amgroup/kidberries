<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product;
?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<ul class="cart_list product_list_widget <?php echo $args['list_class']; ?>">

	<?php if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) : ?>
	
<br>

<div class="summary">
	<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>"  alt="<?php _e('View your shopping cart', 'woocommerce'); ?>" title="<?php _e('View your shopping cart', 'woocommerce'); ?>">
		<p class="amount">В корзине <strong><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woocommerce'), $woocommerce->cart->cart_contents_count);?></strong>.</p>
		<p>Перейти в корзину</p>
	</a>
	<br/>

	
	<p class="subtotal">
		<span class="label">На сумму (со скидками):</span>
		<span class="price">
			<span class="price digit"><?php echo $woocommerce->cart->get_cart_total(); ?></span>
		</span>                                    
	</p>
</div>
	
<div class="actions">
	<form action="<?php echo $woocommerce->cart->get_checkout_url(); ?>">
		<button type="submit" title="Заказать" class="button white">Заказать</button>
	</form>
</div>
	
<p class="block-subtitle">Последние товары в заказе</p>
	
<ol id="cart-sidebar" class="mini-products-list mini-cart">

		<?php foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) :

			$_product = $cart_item['data'];

			// Only display if allowed
			if ( ! apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) || ! $_product->exists() || $cart_item['quantity'] == 0 )
				continue;

			// Get price
			$product_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();

			$product_price = apply_filters( 'woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $cart_item, $cart_item_key );
			?>

			<li class="item">
				
				<a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>" title="<?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product ); ?>" class="product-image">
					<div class='product_in_cart'><?php echo $_product->get_image(array(60,60)); ?></div>
					<span class="price"><span class="price digit"><?php echo $product_price; ?></span></span>
				</a>
				
				<div class="product-details">
<?//					echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" onclick="return confirm('."'Вы действительно хотите удалить этот товар из корзины?'".');" class="btn-remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );?>
					<p class="product-name">
						<a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>"><?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product ); ?></a>
					</p>
					<?php //echo apply_filters( 'woocommerce_widget_cart_item_quantity', '' . sprintf( '<span class="price"><span class="price digit">%s</span></span>', $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                </div>
</li>
			
			

		<?php endforeach; ?>
</ol>
	<?php else : ?>
<br>
		<li class="empty"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></li>

	<?php endif; ?>

</ul><!-- end product list -->



<?php do_action( 'woocommerce_after_mini_cart' ); ?>
