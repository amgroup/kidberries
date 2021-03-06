<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $attributes;

$alt = 1;
$attributes = $product->get_attributes();

if ( empty( $attributes ) && ( ! $product->enable_dimensions_display() || ( ! $product->has_dimensions() && ! $product->has_weight() ) ) ) return;
?>
<table class="data-table" id="product-attribute-specs-table">
	<col width="25%" />
	<col />

	<tbody>

	<?php foreach ($attributes as $attribute) :
		if ( ! isset( $attribute['is_visible'] ) || ! $attribute['is_visible'] ) continue;
		if ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) continue;

		$alt = $alt * -1;
		?>

		<tr class="<?php if ( $alt == 1 ) echo 'alt'; ?>">
			<th><?php echo $woocommerce->attribute_label( $attribute['name'] ); ?></th>
			<td>
				<?php if ( $attribute['is_changeable'] || ($attribute['is_variation'] && $attribute['is_visible'] ) ) : ?>
					<p class="changeable attribute name attribute_<?php echo $attribute['name']; ?>"><strong><i class="glyphicon glyphicon-info-sign"></i> Есть разные варианты: </strong>
					</p>
					<span class="changeable attribute description attribute_<?php echo $attribute['name']; ?>">
				<?php else: ?>
					<span>
				<?php endif; ?>
					<?php
						if ( $attribute['is_taxonomy'] ) {

							$values = woocommerce_get_product_terms( $product->id, $attribute['name'], 'names' );
							echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );

						} else {
							// Convert pipes to commas and display values
							$values = array_map( 'trim', explode( '|', $attribute['value'] ) );
							echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );

						}
					?>
					</span>
			</td>
		</tr>

	<?php endforeach; ?>

	<?php if ( $product->enable_dimensions_display() ) : ?>
		<?php if ( $product->has_weight() ) : $alt = $alt * -1; ?>

			<tr class="<?php if ( $alt == 1 ) echo 'alt'; ?>">
				<th><?php _e( 'Weight', 'woocommerce' ) ?></th>
				<td class="product_weight"><?php echo $product->get_weight() . ' ' . esc_attr( __(get_option('woocommerce_weight_unit'),'woocommerce') ); ?></td>
			</tr>

		<?php endif; ?>

		<?php if ($product->has_dimensions()) : $alt = $alt * -1; ?>

			<tr class="<?php if ( $alt == 1 ) echo 'alt'; ?>">
				<th><?php _e( 'Dimensions', 'woocommerce' ) ?></th>
				<td class="product_dimensions"><?php echo $product->get_dimensions(); ?></td>
			</tr>

		<?php endif; ?>

		<?php endif; ?>

	</tbody>
</table>
