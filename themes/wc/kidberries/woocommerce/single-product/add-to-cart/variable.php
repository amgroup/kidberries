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
<?php do_action('woocommerce_before_add_to_cart_form');?>

<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
<input type="hidden" name="variation_id" value="" />

<table class="variations_container" >
	<tr class="variations">
		<td class="variation-variants-cont">
			<?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
				<div>
					<select id="<?php echo esc_attr( sanitize_title($name) ); ?>" name="attribute_<?php echo sanitize_title($name); ?>">
						<option value="" class="choose"><?php echo $woocommerce->attribute_label( $name ); ?>&hellip;</option>

						<?php
							if ( is_array( $options ) ) {

								if ( ! empty( $_POST ) )
									$selected_value = isset( $_POST[ sanitize_title( $name ) ] ) ? $_POST[ sanitize_title( $name ) ] : '';
								if ( !( empty( $_GET ) || $selected_value ) )
									$selected_value = isset( $_GET[ sanitize_title( $name ) ] ) ? $_GET[ sanitize_title( $name ) ] : '';


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
			<button class="btn btn-default btm-xs btn-link reset_variations" style="width: 100%" disabled="disabled"><i class="glyphicon glyphicon-remove-circle" ></i> Очистить выбор</button>
		</td>
	<tr>
</table>

<?php do_action('woocommerce_after_add_to_cart_form'); ?>	
			
	
<script type="text/javascript">
    jQuery(document).ready(function($){
		$.extend({
		getUrlVars: function(){
		  var vars = [], hash;
		  var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		  for(var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		  }
		  return vars;
		},
		getUrlVar: function(name){
		  return $.getUrlVars()[name];
		}
		});
	
        $('.variations_container').block({message: null, overlayCSS: {background: 'transparent url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } });

        $.post( woocommerce_params.ajax_url, {action : 'get_data_product_variations', 'product_id':'<?php echo $product->id; ?>'}, function(data) {
        	$('.variations_form').attr( "data-product_variations", data );
            $('.variations_container').unblock();
        });
    });
</script>

<!-- /variable product form content -->

