<?php
/**
 * Layered Navigation Widget
 *
 * @author 		WooThemes
 * @category 	Widgets
 * @package 	WooCommerce/Widgets
 * @version 	1.6.4
 * @extends 	WP_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kidberries_Widget_Layered_Nav extends WP_Widget {

	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_name;

	/**
	 * constructor
	 *
	 * @access public
	 * @return void
	 */
	function Kidberries_Widget_Layered_Nav() {

		/* Widget variable settings. */
		$this->woo_widget_cssclass 		= 'kidberries widget_layered_nav';
		$this->woo_widget_description	= __( 'Shows a custom attribute in a widget which lets you narrow down the list of products when viewing product categories.', 'woocommerce' );
		$this->woo_widget_idbase 		= 'kidberries_layered_nav';
		$this->woo_widget_name 			= __( 'Kidberries Layered Nav', 'woocommerce' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

		/* Create the widget. */
		$this->WP_Widget( 'woocommerce_layered_nav', $this->woo_widget_name, $widget_ops );
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
		global $_chosen_attributes, $woocommerce, $_attributes_array, $wpdb, $wp_query;

		extract( $args );

		if ( ! is_post_type_archive( 'product' ) && ! is_tax( array_merge( $_attributes_array, array( 'product_cat', 'product_tag' ) ) ) )
			return;

		$current_term 	= $_attributes_array && is_tax( $_attributes_array ) ? get_queried_object()->term_id : '';
		$current_tax 	= $_attributes_array && is_tax( $_attributes_array ) ? get_queried_object()->taxonomy : '';

		$title 			= apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$taxonomy 		= $woocommerce->attribute_taxonomy_name($instance['attribute']);
		$query_type 	= isset( $instance['query_type'] ) ? $instance['query_type'] : 'and';
		$display_type 	= isset( $instance['display_type'] ) ? $instance['display_type'] : 'list';

		if ( ! taxonomy_exists( $taxonomy ) )
			return;

		if ( is_tax('product_cat') ) {
			echo $cat->term_id;
			$cat = $wp_query->get_queried_object();
		}

		$terms = $wpdb->get_results( $wpdb->prepare("
            SELECT
              sum(pm.meta_value::numeric) AS count,
              t.term_id,
              t.name
            FROM (
              WITH RECURSIVE tree ( term_taxonomy_id, term_id, parent, level,path ) AS (
                SELECT
                  tt.term_taxonomy_id,
                  tt.term_id,
                  tt.parent,
                  1 AS level,
                  tt.term_id::text AS path
                FROM
                  wp_term_taxonomy tt
                WHERE
                  " . (is_tax('product_cat') ? "tt.term_id = $cat->term_id AND" : "" ) . "
                  tt.taxonomy = 'product_cat'
                UNION
                SELECT
                  tt2.term_taxonomy_id,
                  tt2.term_id,
                  tt2.parent,
                  level+1 AS level,
                  (tree.path || '/' || tt2.term_id::text) AS path
                FROM
                  wp_term_taxonomy tt2,
                  tree 
                WHERE
                  tree.term_id = tt2.parent AND
                  tt2.taxonomy = 'product_cat'
              )
              SELECT DISTINCT
                treerel.object_id
              FROM
                tree,
                wp_term_relationships treerel
              WHERE
                tree.term_taxonomy_id = treerel.term_taxonomy_id
            ) o,
              wp_term_relationships  tr,
              wp_term_taxonomy tt,
              wp_terms t,
              wp_posts p,
              wp_postmeta pm  
            WHERE
              tr.object_id        = o.object_id AND
              tt.term_taxonomy_id = tr.term_taxonomy_id AND
              tt.taxonomy         = %s AND
              t.term_id           = tt.term_id AND
              p.post_status       = 'publish' AND
              tr.object_id        IN (p.post_parent, p.\"ID\") AND
              pm.post_id          IN (p.post_parent, p.\"ID\") AND
              pm.meta_key         = '_stock' AND fn.is_positive( pm.meta_value::numeric )
            GROUP BY
              t.term_id,
              t.name
            ORDER BY
              t.name",	$taxonomy
		));
		
		if ( count( $terms ) > 0 ) {

			ob_start();

			$found = false;

			echo $before_widget . $before_title . $title . $after_title;

			// Force found when option is selected - do not force found on taxonomy attributes
			if ( ! $_attributes_array || ! is_tax( $_attributes_array ) )
				if ( is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) )
					$found = true;

			$max_count = 0;
			$list = array();


			foreach ( $terms as $term ) {
				// Get count based on current view - uses transients
				$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_id ) );

				if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

					$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

					set_transient( $transient_name, $_products_in_term );
				}

				$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );

				// If this is an AND query, only show options with count > 0
				if ( $query_type == 'and' ) {

					// skip the term for the current archive
					if ( $current_term == $term->term_id )
						continue;

					if ( $current_term !== $term->term_id )
						$found = true;

				// If this is an OR query, show all options so search can be expanded
				} else {

					// skip the term for the current archive
					if ( $current_term == $term->term_id )
						continue;

					$found = true;

				}

				$arg = 'filter_' . sanitize_title( $instance['attribute'] );

				$current_filter = ( isset( $_GET[ $arg ] ) ) ? explode( ',', $_GET[ $arg ] ) : array();

				if ( ! is_array( $current_filter ) )
					$current_filter = array();

				$current_filter = array_map( 'esc_attr', $current_filter );

				if ( ! in_array( $term->term_id, $current_filter ) )
					$current_filter[] = $term->term_id;

				// Base Link decided by current page
				if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
					$link = home_url();
				} elseif ( is_post_type_archive( 'product' ) || is_page( woocommerce_get_page_id('shop') ) ) {
					$link = get_post_type_archive_link( 'product' );
				} else {
					$link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
				}

				// All current filters
				if ( $_chosen_attributes ) {
					foreach ( $_chosen_attributes as $name => $data ) {
						if ( $name !== $taxonomy ) {

							//exclude query arg for current term archive term
							while ( in_array( $current_term, $data['terms'] ) ) {
								$key = array_search( $current_term, $data );
								unset( $data['terms'][$key] );
							}

							if ( ! empty( $data['terms'] ) )
								$link = add_query_arg( sanitize_title( str_replace( 'pa_', 'filter_', $name ) ), implode(',', $data['terms']), $link );

							if ( $data['query_type'] == 'or' )
								$link = add_query_arg( sanitize_title( str_replace( 'pa_', 'query_type_', $name ) ), 'or', $link );
						}
					}
				}

				// Min/Max
				if ( isset( $_GET['min_price'] ) )
					$link = add_query_arg( 'min_price', $_GET['min_price'], $link );

				if ( isset( $_GET['max_price'] ) )
					$link = add_query_arg( 'max_price', $_GET['max_price'], $link );

				// Current Filter = this widget
				if ( isset( $_chosen_attributes[ $taxonomy ] ) && is_array( $_chosen_attributes[ $taxonomy ]['terms'] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) ) {

					$class = 'class="chosen"';

					// Remove this term is $current_filter has more than 1 term filtered
					if ( sizeof( $current_filter ) > 1 ) {
						$current_filter_without_this = array_diff( $current_filter, array( $term->term_id ) );
						$link = add_query_arg( $arg, implode( ',', $current_filter_without_this ), $link );
					}

				} else {

					$class = '';
					$link = add_query_arg( $arg, implode( ',', $current_filter ), $link );

				}

				// Search Arg
				if ( get_search_query() )
					$link = add_query_arg( 's', get_search_query(), $link );

				// Post Type Arg
				if ( isset( $_GET['post_type'] ) )
					$link = add_query_arg( 'post_type', $_GET['post_type'], $link );

				// Query type Arg
				if ( $query_type == 'or' && ! ( sizeof( $current_filter ) == 1 && isset( $_chosen_attributes[ $taxonomy ]['terms'] ) && is_array( $_chosen_attributes[ $taxonomy ]['terms'] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) ) )
					$link = add_query_arg( 'query_type_' . sanitize_title( $instance['attribute'] ), 'or', $link );


				$term->item = '<li><a ' . ($option_is_set?'class="checked" ':'') . 'href="' . esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ) . '">';

				$term->item .= '<span class="text">' . $term->name . '</span>';

				$term->item .= '</a></li>';

				//$term->item .= ' <small class="count">' . $term->count . '</small>';

				$max_count = $term->count > $max_count ? $term->count : $max_count;
			}

			if( ! empty($terms) ) {
				echo '<style>
					.widget.kidberries.layered-nav { margin-top: 20px; }
                    .widget.kidberries.layered-nav li { padding: 0; }
					.widget.kidberries.layered-nav li .text { color: whitesmoke; border-radius: 40px; padding: 3px 12px; text-shadow: 0 1px 0 rgba(0, 0, 0, 0.73); }
					.widget.kidberries.layered-nav li a { text-decoration: none; }
					.widget.kidberries.layered-nav li a:hover .text { box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.1),inset -1px -1px 0px rgba(0, 0, 0, 0.13); }
										
					.widget.kidberries.layered-nav li .checked .text { color: indigo; box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.1),inset 1px 1px 0px rgba(0, 0, 0, 0.4); text-shadow: 0 -1px 0 rgba(180, 180, 180, 0.73); }
					.widget.kidberries.layered-nav li .checked:hover .text { color: #A350E0; box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.1),inset 1px 1px 0px rgba(0, 0, 0, 0.4); text-shadow: 0 -1px 0 rgba(180, 180, 180, 0.73); }
				</style>';

				echo '<ul class="widget kidberries layered-nav" style="font-size:20px;">';
				foreach( $terms as $item ) {
					$size = ($item->count / $max_count);
					if( $size == 1 ) $size = 100;
					elseif( $size > .8 ) $size = 90;
					elseif( $size > .6 ) $size = 95;
					elseif( $size > .5 ) $size = 80;
					elseif( $size > .4 ) $size = 85;
					elseif( $size > .3 ) $size = 70;
					else $size = 60;
					
					//$size = 100; //($item['count'] / $max_count) * 100;
					echo '<h3 class="item" style="font-size:' . (int) $size . '%;">' . $item->item . '</h3> ';
				}
				echo '</ul>';
			}

			echo $after_widget;

			if ( ! $found )
				ob_end_clean();
			else
				echo ob_get_clean();
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
		global $woocommerce;

		if ( empty( $new_instance['title'] ) )
			$new_instance['title'] = $woocommerce->attribute_label( $new_instance['attribute'] );

		$instance['title'] 		= strip_tags( stripslashes($new_instance['title'] ) );
		$instance['attribute'] 		= stripslashes( $new_instance['attribute'] );
		$instance['query_type'] 	= stripslashes( $new_instance['query_type'] );
		$instance['display_type'] 	= stripslashes( $new_instance['display_type'] );

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
		global $woocommerce;

		if ( ! isset( $instance['query_type'] ) )
			$instance['query_type'] = 'and';

		if ( ! isset( $instance['display_type'] ) )
			$instance['display_type'] = 'list';
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'woocommerce' ) ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php if ( isset( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'attribute' ); ?>"><?php _e( 'Attribute:', 'woocommerce' ) ?></label>
		<select id="<?php echo esc_attr( $this->get_field_id( 'attribute' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'attribute' ) ); ?>">
			<?php
			$attribute_taxonomies = $woocommerce->get_attribute_taxonomies();
			if ( $attribute_taxonomies )
				foreach ( $attribute_taxonomies as $tax )
					if ( taxonomy_exists( $woocommerce->attribute_taxonomy_name( $tax->attribute_name ) ) )
						echo '<option value="' . $tax->attribute_name . '" ' . selected( ( isset( $instance['attribute'] ) && $instance['attribute'] == $tax->attribute_name ), true, false ) . '>' . $tax->attribute_name . '</option>';
			?>
		</select></p>

		<p><label for="<?php echo $this->get_field_id( 'display_type' ); ?>"><?php _e( 'Display Type:', 'woocommerce' ) ?></label>
		<select id="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_type' ) ); ?>">
			<option value="list" <?php selected( $instance['display_type'], 'list' ); ?>><?php _e( 'List', 'woocommerce' ); ?></option>
			<option value="dropdown" <?php selected( $instance['display_type'], 'dropdown' ); ?>><?php _e( 'Dropdown', 'woocommerce' ); ?></option>
		</select></p>

		<p><label for="<?php echo $this->get_field_id( 'query_type' ); ?>"><?php _e( 'Query Type:', 'woocommerce' ) ?></label>
		<select id="<?php echo esc_attr( $this->get_field_id( 'query_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'query_type' ) ); ?>">
			<option value="and" <?php selected( $instance['query_type'], 'and' ); ?>><?php _e( 'AND', 'woocommerce' ); ?></option>
			<option value="or" <?php selected( $instance['query_type'], 'or' ); ?>><?php _e( 'OR', 'woocommerce' ); ?></option>
		</select></p>
		<?php
	}
}