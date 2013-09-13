<?php
/**
 * WooCommerce Upsale Products Widget
 *
 * @author 		WooThemes
 * @category 	Widgets
 * @package 	WooCommerce/Widgets
 * @version 	1.6.4
 * @extends 	WP_Widget
 */

 
/////////////////////////////////////////////////////////////////
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kidberries_Widget_Upsell_Products extends WP_Widget {

	/**
	 * constructor
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		$this->id_base = 'kidberries_upsell_products';
		$this->name    = __( 'Kidberries Upsell Products', 'woocommerce' );
		$this->widget_options = array(
			'classname'   => 'kidberries widget_upsell_products',
			'description' => __( 'Display a list of upsell products on your site.', 'woocommerce' ),
		);

		parent::__construct( $this->id_base, $this->name, $this->widget_options );
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		global $woocommerce, $product;

		// Use default title as fallback
		$title = ( '' === $instance['title'] ) ? __('Upsell Products', 'woocommerce' ) : $instance['title'];
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		// Setup product query
		$ids = get_post_meta( $product->id, '_upsell_ids');
		$query_args = array(
			'post__in'       => $ids[0],
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $instance['number'],
			'orderby'        => 'rand',
			'no_found_rows'  => 1
		);		
		
		$query_args['meta_query'] = array();

		if ( ! $instance['show_variations'] ) {
			$query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
			$query_args['post_parent'] = 0;
		}

	    $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();

		$query = new WP_Query( $query_args );

		if ( $query->have_posts() ) {
			echo $args['before_widget'];

			if ( '' !== $title ) {
				echo $args['before_title'], $title, $args['after_title'];
			} ?>

			<ol id="upsell_products_widget" class="mini-products-list">
				<?php while ($query->have_posts()) : $query->the_post(); global $product; ?>
					<li class="item marginbot">
				
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="product-image">
					<div class='product_in_cart'><?php the_post_thumbnail(array(60,60)); ?></div>
					
					<span class="price">	
						<span class="price digit"><?echo $product->price;?></span>	
						<span class="price currency">руб.</span>	
					</span>
				</a>
				
				<div class="product-details">
					
				
					
					<p class="product-name">
						<a href="<?php the_permalink(); ?>"><?the_title();?></a>
						

					</p>

					
                </div>
</li>
					
					
				<?php endwhile; ?>
			</ol>

			<?php
			echo $args['after_widget'];
		}
	}

	/**
	 * update function.
	 *
	 * @see WP_Widget->update
	 * @access public
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array(
			'title'           => strip_tags($new_instance['title']),
			'number'          => min(15, max(1, (int) $new_instance['number'])),
			'show_variations' => ! empty($new_instance['show_variations'])
		);

		return $instance;
	}

	/**
	 * form function.
	 *
	 * @see WP_Widget->form
	 * @access public
	 * @param array $instance
	 * @return void
	 */
	function form( $instance ) {
		// Default values
		$title           = isset( $instance['title'] ) ? $instance['title'] : '';
		$number          = isset( $instance['number'] ) ? (int) $instance['number'] : 5;
		$show_variations = ! empty( $instance['show_variations'] );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title:', 'woocommerce' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ?>" name="<?php echo esc_attr( $this->get_field_name('title') ) ?>" type="text" value="<?php echo esc_attr( $title ) ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ) ?>"><?php _e( 'Number of products to show:', 'woocommerce' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ) ?>" name="<?php echo esc_attr( $this->get_field_name('number') ) ?>" type="text" value="<?php echo esc_attr( $number ) ?>" size="3" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_variations' ) ) ?>" name="<?php echo esc_attr( $this->get_field_name('show_variations') ) ?>" <?php checked( $show_variations ) ?> />
			<label for="<?php echo $this->get_field_id( 'show_variations' ) ?>"><?php _e( 'Show hidden product variations', 'woocommerce' ) ?></label>
		</p>

		<?php
	}

}
