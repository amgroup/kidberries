<?php
/**
 * Variable product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product, $post, $currency_symbol;

?>
<!-- variable product form content -->

<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
<input type="hidden" name="variation_id" value="" />

<?php do_action('woocommerce_before_add_to_cart_form');?>
<table>
	<tr class="variations">
		<td class="variation-price-cont">
			<div class="price-box">
				<p class="single_price_wrap">
					<span class="price">
						<span class="price currency">от</span> 
						<span class="price digit"><?php echo get_post_meta( $product->id, '_min_variation_price', true );?></span>
						<span class="price currency"><?php echo $currency_symbol; ?></span>
					</span>
				</p>
			</div>
			<div class="single_variation_wrap" style="display:none;">
				<div class="single_variation"></div>
			</div>
		</td>
		<td class="variation-variants-cont">
			<?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
				<div>
					<select onchange="variant_changed(this)" id="<?php echo esc_attr( sanitize_title($name) ); ?>" name="attribute_<?php echo sanitize_title($name); ?>">
						<option value="" class="choose"><?php echo $woocommerce->attribute_label( $name ); ?>&hellip;</option>

						<?php
							if ( is_array( $options ) ) {

								if ( empty( $_POST ) )
									$selected_value = ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) ? $selected_attributes[ sanitize_title( $name ) ] : '';
								else
									$selected_value = isset( $_POST[ 'attribute_' . sanitize_title( $name ) ] ) ? $_POST[ 'attribute_' . sanitize_title( $name ) ] : '';

								// Get terms if this is a taxonomy - ordered
								if ( taxonomy_exists( sanitize_title( $name ) ) ) {

									$orderby = $woocommerce->attribute_orderby( $name );

									switch ( $orderby ) {
										case 'name' :
											$args = array( 'orderby' => 'name', 'hide_empty' => false, 'menu_order' => false );
										break;
										case 'id' :
											$args = array( 'orderby' => 'id', 'order' => 'ASC', 'menu_order' => false );
										break;
										case 'menu_order' :
											$args = array( 'menu_order' => 'ASC' );
										break;
									}

									$terms = get_terms( sanitize_title( $name ), $args );

									foreach ( $terms as $term ) {
										if ( ! in_array( $term->slug, $options ) )
											continue;

										echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $selected_value, $term->slug, false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
									}
								} else {

									foreach ( $options as $option ) {
										echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
									}
								}
							}
						?>
					</select>
				</div>
			<?php endforeach; ?>
		</td>
        <td class="variation-buttons-cont" >
            <div class="single_variation_wrap" style="display:none;">
                <div class="variations_button">
                    <?php woocommerce_quantity_input(); ?>
                    <button type="submit" title="В корзину" class="button green">В корзину</button>
                    <div class="buttons_or">или</div>
                    <button type="submit" title="Купить в один клик" class="button red" onclick="buyitnow()">Купить</button>
                    <?php do_action('woocommerce_after_add_to_cart_button'); ?>
                </div>
            </div>
	    </td>
	<tr>
</table>
<?php do_action('woocommerce_after_add_to_cart_form'); ?>	
			
	
<script type="text/javascript">
	variant_changed = function(e) {
		e = jQuery(e);
		if( '' !== e.val() ) e.addClass('complete');
		else  e.removeClass('complete');
	}

    jQuery(function(){
        jQuery('.variations_form').block({message: null, overlayCSS: {background: 'transparent url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } } ).wc_variation_form();

        jQuery.post( woocommerce_params.ajax_url, {action : 'get_data_product_variations', 'product_id':'<?php echo $product->id; ?>'}, function(data) {
            jQuery('.variations_form').attr('data-product_variations',data).trigger('reset').stop(true).removeClass('updating').css('opacity', '1').unblock();
        });
    });
</script>

<!-- /variable product form content -->

