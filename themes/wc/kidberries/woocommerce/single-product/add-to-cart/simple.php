<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product;

if ( ! $product->is_purchasable() ) return;
?>
<!-- simple product form content -->
<?if ($product->is_in_stock() ) { ?>

<td>
	<div class="quantity">
		<?php
		if ( ! $product->is_sold_individually() )
		woocommerce_quantity_input( array(
		'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
		'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
		) );
		?>
	</div>
</td>

<td class="buttons">
	<button type="submit" title="В корзину" class="button" onclick="productAddToCartForm.submit(this)"><span><span>В корзину</span></span></button>
</td>
<?} else {?>
		
<td></td>
<td class="buttons"></td>

<?}?>

<td rowspan="2" class="status">
	<div class="availability">
	
	<?if ($product->is_in_stock() ) {?>
	
		<span class="in-stock">Есть в наличии</span>
		
	<?} else {?>
	
		<span class="out-of-stock">Нет в наличии</span>
	
	<?}?>
	</div>
</td>
<!-- /simple product form content -->
