<?
define( 'THEME_DIR', get_template_directory() );

load_textdomain( 'woocommerce', THEME_DIR . "/languages/woocommerce-ru_RU.mo" );
load_plugin_textdomain( 'woocommerce', false, THEME_DIR . "/languages/" );

function kidberries_get_price() {
    global $post;

    if ( is_product() ) {
      return get_product($post)->get_price() . " " . get_woocommerce_currency_symbol(get_woocommerce_currency());
    }
    return;
}

function redirect_to_checkout() {
	global $woocommerce;

	if ( $_POST['redirect'] == 'checkout' && sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
		$checkout_url = $woocommerce->cart->get_checkout_url();
		return $checkout_url;
	}
}
add_filter ('add_to_cart_redirect', 'redirect_to_checkout');


function selfURL(){
    if(!isset($_SERVER['REQUEST_URI']))    $suri = $_SERVER['PHP_SELF'];
    else $suri = $_SERVER['REQUEST_URI'];
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp=strtolower($_SERVER["SERVER_PROTOCOL"]);
    $pr =    substr($sp,0,strpos($sp,"/")).$s;
    $pt = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $pr."://".$_SERVER['SERVER_NAME'].$pt.$suri;
}

if ( ! function_exists( 'kidberries_form_field' ) ) {
	/**
	 * Outputs a checkout/address form field.
	 *
	 * @access public
	 * @subpackage	Forms
	 * @param mixed $key
	 * @param mixed $args
	 * @param string $value (default: null)
	 * @return void
	 */
	function kidberries_form_field( $key, $args, $value = null ) {
		global $woocommerce;

		$defaults = array(
			'type'              => 'text',
			'label'             => '',
			'placeholder'       => '',
			'maxlength'         => false,
			'required'          => false,
			'class'             => array(),
			'label_class'       => array(),
            'control_class'     => array(),
			'return'            => false,
			'options'           => array(),
			'custom_attributes' => array(),
			'validate'          => array(),
			'default'		    => '',
		);
        
        $args['class'][]         = 'control-group';
        $args['label_class'][]   = 'control-label';
        $args['control_class'][] = 'control-field';

		$args = wp_parse_args( $args, $defaults  );

		if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			//$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>';
		} else {
			$required = '';
		}

		$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

		if ( is_null( $value ) )
			$value = $args['default'];

		// Custom attribute handling
		$custom_attributes = array();

		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) )
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value )
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';

		if ( ! empty( $args['validate'] ) )
			foreach( $args['validate'] as $validate )
				$args['class'][] = 'validate-' . $validate;

		switch ( $args['type'] ) {
		case "country" :

			if ( sizeof( $woocommerce->countries->get_allowed_countries() ) == 1 ) {

				$field = '<div class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

				if ( $args['label'] )
					$field .= '<label class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']  . '</label>';

				$field .= '<strong>' . current( array_values( $woocommerce->countries->get_allowed_countries() ) ) . '</strong>';

				$field .= '<div class="' . implode( ' ', $args['control_class'] ) .'"><input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . current( array_keys( $woocommerce->countries->get_allowed_countries() ) ) . '" ' . implode( ' ', $custom_attributes ) . ' /></div>';

				$field .= '</div>' . $after;

			} else {

				$field = '<div class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">
						<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required  . '</label>
						<div class="' . implode( ' ', $args['control_class'] ) .'"><select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" class="chzn-select country_to_state country_select" ' . implode( ' ', $custom_attributes ) . '>
							<option value="">'.__( 'Select a country&hellip;', 'woocommerce' ) .'</option>';

				foreach ( $woocommerce->countries->get_allowed_countries() as $ckey => $cvalue )
					$field .= '<option value="' . $ckey . '" '.selected( $value, $ckey, false ) .'>'.__( $cvalue, 'woocommerce' ) .'</option>';

				$field .= '</select></div>';

				$field .= '<noscript><input type="submit" name="woocommerce_checkout_update_totals" value="' . __( 'Update country', 'woocommerce' ) . '" /></noscript>';

				$field .= '</div>' . $after;

			}

			break;
		case "state" :

			/* Get Country */
			$country_key = $key == 'billing_state'? 'billing_country' : 'shipping_country';

			if ( isset( $_POST[ $country_key ] ) ) {
				$current_cc = woocommerce_clean( $_POST[ $country_key ] );
			} elseif ( is_user_logged_in() ) {
				$current_cc = get_user_meta( get_current_user_id() , $country_key, true );
				if ( ! $current_cc) {
					$current_cc = apply_filters('default_checkout_country', ($woocommerce->customer->get_country()) ? $woocommerce->customer->get_country() : $woocommerce->countries->get_base_country());
				}
			} elseif ( $country_key == 'billing_country' ) {
				$current_cc = apply_filters('default_checkout_country', ($woocommerce->customer->get_country()) ? $woocommerce->customer->get_country() : $woocommerce->countries->get_base_country());
			} else {
				$current_cc = apply_filters('default_checkout_country', ($woocommerce->customer->get_shipping_country()) ? $woocommerce->customer->get_shipping_country() : $woocommerce->countries->get_base_country());
			}

			$states = $woocommerce->countries->get_states( $current_cc );

			if ( is_array( $states ) && empty( $states ) ) {

				$field  = '<div class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field" style="display: none">';

				if ( $args['label'] )
					$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';
				$field .= '<div class="' . implode( ' ', $args['control_class'] ) .'"><input type="hidden" class="hidden" name="' . esc_attr( $key )  . '" id="' . esc_attr( $key ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . $args['placeholder'] . '" /></div>';
				$field .= '</div>' . $after;

			} elseif ( is_array( $states ) ) {

				$field  = '<div class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

				if ( $args['label'] )
					$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';
				$field .= '<div class="' . implode( ' ', $args['control_class'] ) .'"><select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" class="state_select" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . $args['placeholder'] . '">
					<option value="">'.__( 'Select a state&hellip;', 'woocommerce' ) .'</option>';

				foreach ( $states as $ckey => $cvalue )
					$field .= '<option value="' . $ckey . '" '.selected( $value, $ckey, false ) .'>'.__( $cvalue, 'woocommerce' ) .'</option>';

				$field .= '</select></div>';
				$field .= '</div>' . $after;

			} else {

				$field  = '<div class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

				if ( $args['label'] )
					$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';
				$field .= '<div class="' . implode( ' ', $args['control_class'] ) .'"><input type="text" class="input-text" value="' . $value . '"  placeholder="' . $args['placeholder'] . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' /></div>';
				$field .= '</div>' . $after;

			}

			break;
		case "textarea" :

			$field = '<div class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

			if ( $args['label'] )
				$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required  . '</label>';

			$field .= '<div class="' . implode( ' ', $args['control_class'] ) .'"><textarea name="' . esc_attr( $key ) . '" class="input-text" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" cols="5" rows="2" ' . implode( ' ', $custom_attributes ) . '>'. esc_textarea( $value  ) .'</textarea></div>
				</div>' . $after;

			break;
		case "checkbox" :

			$field = '<div class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">
					<div class="' . implode( ' ', $args['control_class'] ) .'"><input type="' . $args['type'] . '" class="input-checkbox" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="1" '.checked( $value, 1, false ) .' /></div>
					<label for="' . esc_attr( $key ) . '" class="checkbox ' . implode( ' ', $args['label_class'] ) .'" ' . implode( ' ', $custom_attributes ) . '>' . $args['label'] . $required . '</label>
				</div>' . $after;

			break;
		case "password" :

			$field = '<div class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

			if ( $args['label'] )
				$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';

			$field .= '<div class="' . implode( ' ', $args['control_class'] ) .'"><input type="password" class="input-text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' /></div>
				</div>' . $after;

			break;
		case "text" :

			$field = '<div class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

			if ( $args['label'] )
				$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';

			$field .= '<div class="' . implode( ' ', $args['control_class'] ) .'"><input type="text" class="input-text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' /></div>
				</div>' . $after;

			break;
		case "select" :

			$options = '';

			if ( ! empty( $args['options'] ) )
				foreach ( $args['options'] as $option_key => $option_text )
					$options .= '<option value="' . esc_attr( $option_key ) . '" '. selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) .'</option>';

				$field = '<div class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

				if ( $args['label'] )
					$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';

				$field .= '<div class="' . implode( ' ', $args['control_class'] ) .'"><select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" class="select" ' . implode( ' ', $custom_attributes ) . '>
						' . $options . '
					</select></div>
				</div>' . $after;

			break;
		default :

			$field = apply_filters( 'woocommerce_form_field_' . $args['type'], '', $key, $args, $value );

			break;
		}

		if ( $args['return'] ) return $field; else echo $field;
	}
}

if ( ! function_exists( 'woocommerce_content' ) ) {

	/**
	 * Output WooCommerce content.
	 *
	 * This function is only used in the optional 'woocommerce.php' template
	 * which people can add to their themes to add basic woocommerce support
	 * without hooks or modifying core templates.
	 *
	 * @access public
	 * @return void
	 */
	function woocommerce_content() {

		if ( is_singular( 'product' ) ) {

			while ( have_posts() ) : the_post();

			?>
			<div class="main-container col2-right-layout">
                    <div class="main">
                        
                        <div class="col-main">
						
<div class="product-view">
	<div class="main-block skew-block">
								<div class="corners-top"><div><div>&nbsp;</div></div></div>       
									<div class="content-box">
										<div class="border-bot">
											<div class="border-left">
												<div class="border-right">
													<div class="corner-left-top">
														<div class="corner-right-top">
															<div class="corner-left-bot">
																<div class="corner-right-bot">        <div class="product-essential">
			<?
			
				woocommerce_get_template_part( 'content', 'single-product' );
?>
				</div></div></div></div></div></div></div></div></div><div class="corners-bot"><div><div>&nbsp;</div></div></div></div></div></div>
				<div class="col-right  sidebar">
<?get_sidebar();?>
</div>

<?
			endwhile;

		} else {

			?>
			<div class="main-container col2-left-layout">
                    <div class="main">
                                                <div class="col-main">
                                                        
														
														
														
<!--	--------------------------	--------------СПИСОК ТОВАРОВ --->
														
														
<div class="page-title category-title">
	<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
	<?php
		if ( is_tax( 'product_cat' ) ) {
			$term = get_term_by('slug', esc_attr( get_query_var('product_cat') ), 'product_cat');
			$advertisement = html_entity_decode( get_woocommerce_term_meta( $term->term_id, 'advertisement', true ) );
			if( $advertisement )
				echo '<div class="category advertisement">' . $advertisement . '</div>';
		}
	?>
</div>
														<div class="new-product">
 
                                    


			<?php do_action( 'woocommerce_archive_description' ); ?>

			<?php if ( have_posts() ) : ?>
<div class="toolbar">
	<div class="main-block skew-block">
								<div class="corners-top"><div><div>&nbsp;</div></div></div>       
									<div class="content-box">
										<div class="border-bot">
											<div class="border-left">
												<div class="border-right">
													<div class="corner-left-top">
														<div class="corner-right-top">
															<div class="corner-left-bot">
																<div class="corner-right-bot">            <div class="pager">
                
                                            <?woocommerce_result_count()?>                                    
        
                
        
                
    
    
    
        <div class="limiter pages">
        
        <?woocommerce_pagination()?>

    </div>
    
    
        
            </div>
        
                        <div class="sorter">
                                <p class="view-mode">
                                                            <label>Вид:</label>
															
															<?$self=selfURL();
															$mode=$_REQUEST['mode']; 
															if ($mode != 'list') {
															?>
															
																<strong title="Сетка" class="grid">Сетка</strong>&nbsp;
																<a href="<?echo $self;
																if (count($_REQUEST) == 0) {echo '?'; } else {echo '&';};
																echo 'mode=list';?>" title="Список" class="list">Список</a>
															<? } else { ?>
																<a href="<?echo $self;
																if (count($_REQUEST) == 0) {echo '?'; } else {echo '&';};
																echo 'mode=grid';?>" title="Список" class="list">Сетка</a>&nbsp;
																<strong title="Сетка" class="grid">Список</strong>
															<?};?> 
								</p>

                            
                <div class="sort-by">
                    <label>Тип сортировки</label>
                    <?woocommerce_catalog_ordering()?>
                                          
                                    </div>
            </div>
                </div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>                
									</div>
									<div class="corners-bot"><div><div>&nbsp;</div></div></div>
								</div></div>
<br>

				<?php if ($mode != 'list') {echo '<ul class="products-grid">';} else {echo '<ul class="products-list">';};?>

					<?php woocommerce_product_subcategories(); ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php woocommerce_get_template_part( 'content', 'product' ); ?>

					<?php endwhile; // end of the loop. ?>

				</ul>

<div class="toolbar">
	<div class="main-block skew-block">
								<div class="corners-top"><div><div>&nbsp;</div></div></div>       
									<div class="content-box">
										<div class="border-bot">
											<div class="border-left">
												<div class="border-right">
													<div class="corner-left-top">
														<div class="corner-right-top">
															<div class="corner-left-bot">
																<div class="corner-right-bot">            <div class="pager">
                
                                            <?woocommerce_result_count()?>                                    
        
                
        
                
    
    
    
        <div class="limiter pages">
        
        <?woocommerce_pagination()?>

    </div>
    
    
        
            </div>
        
                        <div class="sorter">
                                <p class="view-mode">
                                                            <label>Вид:</label>
															
															<?$self=selfURL();
															$mode=$_REQUEST['mode']; 
															if ($mode != 'list') {
															?>
															
																<strong title="Сетка" class="grid">Сетка</strong>&nbsp;
																<a href="<?echo $self;
																if (count($_REQUEST) == 0) {echo '?'; } else {echo '&';};
																echo 'mode=list';?>" title="Список" class="list">Список</a>
															<? } else { ?>
																<a href="<?echo $self;
																if (count($_REQUEST) == 0) {echo '?'; } else {echo '&';};
																echo 'mode=grid';?>" title="Список" class="list">Сетка</a>&nbsp;
																<strong title="Сетка" class="grid">Список</strong>
															<?};?> 
								</p>

                            
                <div class="sort-by">
                    <label>Тип сортировки</label>
                    <?woocommerce_catalog_ordering()?>
                                          
                                    </div>
            </div>
                </div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>                
									</div>
									<div class="corners-bot"><div><div>&nbsp;</div></div></div>
								</div></div>

			<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

				<?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>

			<?php endif;
			
			?>
<script type="text/javascript">
            jQuery(document).ready(function(){
return;
                var prices = jQuery('.new-product .price-block .price.digit');
                prices.each(function(){
                    var this_price = jQuery(this).html();
                    var currency = this_price.slice(0, 1);                    
                    this_price = Math.round(this_price.slice(1));
                    jQuery(this).html(this_price);
                    jQuery(this).parent().parent().parent().find('.currency').html(currency);
                });                
            });
        </script>
 </div>
                        </div><div class="col-left sidebar">
<?get_sidebar();?>
</div><?
			
			
			
			
		}
		
		
		

		
		
	}
}

if ( ! function_exists( 'woocommerce_output_related_products' ) ) {

	/**
	 * Output the related products.
	 *
	 * @access public
	 * @subpackage	Product
	 * @return void
	 */
	function woocommerce_output_related_products() {
		woocommerce_related_products( 3, 3 );
	}
}


if (function_exists('add_theme_support')) {
    add_theme_support('menus');
}

register_nav_menus( array(
   'top' => __( 'Верхнее меню'),
    ) );

function add_first_and_last($items) {
    // first class on parent most level
    $items[1]->classes[] = 'first';
    // separate parents and children
    $parents = $children = array();
    foreach($items as $k => $item){
        if($item->menu_item_parent == '0'){
            $parents[] = $k;
        } else {
            $children[$item->menu_item_parent] = $k;
        }
    }
    // last class on parent most level
    $last = end(array_keys($parents));
    foreach ($parents as $k => $parent) {
        if ($k == $last) {
            $items[$parent]->classes[] = 'last';
        }
    }
    // last class on children levels
    foreach($children as $child){
        $items[$child]->classes[] = 'last';
    }
    // first class on children levels
    $r_items = array_reverse($items, true);
    foreach($r_items as $k => $item){
        if($item->menu_item_parent !== '0'){
            $children[$item->menu_item_parent] = $k;
        }
    }
    foreach($children as $child){
        $items[$child]->classes[] = 'first';
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'add_first_and_last');

// ВИДЖЕТ НАД ПОИСКОМ
    register_sidebar( array(
        'name' => __( 'ВЕРХНИЙ ВИДЖЕТ', 'TODO' ),
        'id' => 'top-widget-area',
        'description' => __( 'РАСПОЛОЖЕН НАД ФОРМОЙ ПОИСКА', 'twentyten' ),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ) );


// ВИДЖЕТЫ В САЙДБАРЕ - синий ровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 1 голубой', 'TODO' ),
        'id' => 'primary-widget-area',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-list block-compare" id="%1$s">
								<div class="sideblock-2 skew-block">
									<div class="top-corner"></div>
									<div class="content-box">
										<div class="bot-bg">
											<div class="left-bg">
												<div class="right-bg">
													<div class="left-top">
														<div class="right-top">
															<div class="left-bot">
																<div class="right-bot">
																	<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );

// ВИДЖЕТЫ В САЙДБАРЕ - зеленый ровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 2 зеленый', 'TODO' ),
        'id' => 'primary-widget-area2',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-cart">
    <div class="sideblock-1 skew-block">
								<div class="top-corner"></div>
								<div class="content-box">
									<div class="bot-bg">
										<div class="left-bg">
											<div class="right-bg">
												<div class="left-top">
													<div class="right-top">
														<div class="left-bot">
															<div class="right-bot">
																<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );
	
	
// ВИДЖЕТЫ В САЙДБАРЕ - оранжевый неровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 3 оранжевый', 'TODO' ),
        'id' => 'primary-widget-area3',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-list block-viewed">
    <div class="sideblock-3 skew-block">
								<div class="top-corner"></div>
								<div class="content-box">
									<div class="bot-bg">
										<div class="left-bg">
											<div class="right-bg">
												<div class="left-top">
													<div class="right-top">
														<div class="left-bot">
															<div class="right-bot">
																<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );
	

// ВИДЖЕТЫ В САЙДБАРЕ - синий ровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 4 голубой', 'TODO' ),
        'id' => 'primary-widget-area4',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-list block-compare" id="%1$s">
								<div class="sideblock-2 skew-block">
									<div class="top-corner"></div>
									<div class="content-box">
										<div class="bot-bg">
											<div class="left-bg">
												<div class="right-bg">
													<div class="left-top">
														<div class="right-top">
															<div class="left-bot">
																<div class="right-bot">
																	<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );

// ВИДЖЕТЫ В САЙДБАРЕ - зеленый ровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 5 зеленый', 'TODO' ),
        'id' => 'primary-widget-area5',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-cart">
    <div class="sideblock-1 skew-block">
								<div class="top-corner"></div>
								<div class="content-box">
									<div class="bot-bg">
										<div class="left-bg">
											<div class="right-bg">
												<div class="left-top">
													<div class="right-top">
														<div class="left-bot">
															<div class="right-bot">
																<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );
	
	
// ВИДЖЕТЫ В САЙДБАРЕ - оранжевый неровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 6 оранжевый', 'TODO' ),
        'id' => 'primary-widget-area6',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-list block-viewed">
    <div class="sideblock-3 skew-block">
								<div class="top-corner"></div>
								<div class="content-box">
									<div class="bot-bg">
										<div class="left-bg">
											<div class="right-bg">
												<div class="left-top">
													<div class="right-top">
														<div class="left-bot">
															<div class="right-bot">
																<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );
	
	


	
// ВИДЖЕТЫ В САЙДБАРЕ - синий ровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 7 голубой', 'TODO' ),
        'id' => 'primary-widget-area7',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-list block-compare" id="%1$s">
								<div class="sideblock-2 skew-block">
									<div class="top-corner"></div>
									<div class="content-box">
										<div class="bot-bg">
											<div class="left-bg">
												<div class="right-bg">
													<div class="left-top">
														<div class="right-top">
															<div class="left-bot">
																<div class="right-bot">
																	<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );

// ВИДЖЕТЫ В САЙДБАРЕ - зеленый ровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 8 зеленый', 'TODO' ),
        'id' => 'primary-widget-area8',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-cart">
    <div class="sideblock-1 skew-block">
								<div class="top-corner"></div>
								<div class="content-box">
									<div class="bot-bg">
										<div class="left-bg">
											<div class="right-bg">
												<div class="left-top">
													<div class="right-top">
														<div class="left-bot">
															<div class="right-bot">
																<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );
	
	
// ВИДЖЕТЫ В САЙДБАРЕ - оранжевый неровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 9 оранжевый', 'TODO' ),
        'id' => 'primary-widget-area9',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-list block-viewed">
    <div class="sideblock-3 skew-block">
								<div class="top-corner"></div>
								<div class="content-box">
									<div class="bot-bg">
										<div class="left-bg">
											<div class="right-bg">
												<div class="left-top">
													<div class="right-top">
														<div class="left-bot">
															<div class="right-bot">
																<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );
	
	// ВИДЖЕТЫ В САЙДБАРЕ - синий ровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 10 голубой', 'TODO' ),
        'id' => 'primary-widget-area10',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-list block-compare" id="%1$s">
								<div class="sideblock-2 skew-block">
									<div class="top-corner"></div>
									<div class="content-box">
										<div class="bot-bg">
											<div class="left-bg">
												<div class="right-bg">
													<div class="left-top">
														<div class="right-top">
															<div class="left-bot">
																<div class="right-bot">
																	<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );

// ВИДЖЕТЫ В САЙДБАРЕ - зеленый ровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 11 зеленый', 'TODO' ),
        'id' => 'primary-widget-area11',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-cart">
    <div class="sideblock-1 skew-block">
								<div class="top-corner"></div>
								<div class="content-box">
									<div class="bot-bg">
										<div class="left-bg">
											<div class="right-bg">
												<div class="left-top">
													<div class="right-top">
														<div class="left-bot">
															<div class="right-bot">
																<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );
	
	
// ВИДЖЕТЫ В САЙДБАРЕ - оранжевый неровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 12 оранжевый', 'TODO' ),
        'id' => 'primary-widget-area12',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-list block-viewed">
    <div class="sideblock-3 skew-block">
								<div class="top-corner"></div>
								<div class="content-box">
									<div class="bot-bg">
										<div class="left-bg">
											<div class="right-bg">
												<div class="left-top">
													<div class="right-top">
														<div class="left-bot">
															<div class="right-bot">
																<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );
	
	

// ВИДЖЕТЫ В САЙДБАРЕ - синий ровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 13 голубой', 'TODO' ),
        'id' => 'primary-widget-area13',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-list block-compare" id="%1$s">
								<div class="sideblock-2 skew-block">
									<div class="top-corner"></div>
									<div class="content-box">
										<div class="bot-bg">
											<div class="left-bg">
												<div class="right-bg">
													<div class="left-top">
														<div class="right-top">
															<div class="left-bot">
																<div class="right-bot">
																	<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );

// ВИДЖЕТЫ В САЙДБАРЕ - зеленый ровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 14 зеленый', 'TODO' ),
        'id' => 'primary-widget-area14',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-cart">
    <div class="sideblock-1 skew-block">
								<div class="top-corner"></div>
								<div class="content-box">
									<div class="bot-bg">
										<div class="left-bg">
											<div class="right-bg">
												<div class="left-top">
													<div class="right-top">
														<div class="left-bot">
															<div class="right-bot">
																<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );
	
	
// ВИДЖЕТЫ В САЙДБАРЕ - оранжевый неровный
    register_sidebar( array(
        'name' => __( 'САЙДБАР 15 оранжевый', 'TODO' ),
        'id' => 'primary-widget-area15',
        'description' => __( 'ВИДЖЕТЫ', 'twentyten' ),
        'before_widget' => '<div class="block block-list block-viewed">
    <div class="sideblock-3 skew-block">
								<div class="top-corner"></div>
								<div class="content-box">
									<div class="bot-bg">
										<div class="left-bg">
											<div class="right-bg">
												<div class="left-top">
													<div class="right-top">
														<div class="left-bot">
															<div class="right-bot">
																<div class="ie-fix">  ',
        'after_widget' => '</div>
															</div>
														</div>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="bot-corner"></div>
							</div></div>',
        'before_title' => '<div class="block-title"><strong><span>',
        'after_title' => '</span></strong></div>',
    ) );


// ВИДЖЕТ В ФУТЕРЕ
    register_sidebar( array(
        'name' => __( 'НИЖНИЙ ВИДЖЕТ', 'TODO' ),
        'id' => 'down-widget-area',
        'description' => __( 'РАСПОЛОЖЕН В ФУТЕРЕ ПО ЦЕНТРУ', 'twentyten' ),
        'before_widget' => '<div class="links-block">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ) );

	
add_action( 'widgets_init', 'override_woocommerce_widgets', 15 );
 
function override_woocommerce_widgets() {
  // Ensure our parent class exists to avoid fatal error (thanks Wilgert!)
 
//  if ( class_exists( 'WC_Widget_Recent_Reviews' ) ) {
//    unregister_widget( 'WC_Widget_Recent_Reviews' );
//  }

//  if ( class_exists( 'WC_Widget_Recently_Viewed' ) ) {
//    unregister_widget( 'WC_Widget_Recently_Viewed' );
//  }


  include_once( 'widgets/recent-reviews.php' );
  register_widget( 'Kidberries_WC_Widget_Recent_Reviews' );

  include_once( 'widgets/recently-viewed.php' );
  register_widget( 'Kidberries_WC_Widget_Recently_Viewed' );

  include_once( 'widgets/upsell-products.php' );
  register_widget( 'Kidberries_Widget_Upsell_Products' );

  include_once( 'widgets/subcategories.php' );
  register_widget( 'Kidberries_Widget_Subcategories' );
}


function kidberries_search_form( ) {

    $form = '<form role="search" method="get" id="searchform" action="' . esc_url(home_url( '/' )) . '" >
	<div class="form-search">
	    <label class="screen-reader-text" for="s">' . __( 'Search Products:' , 'woothemes' ) . '</label>
		<input id="search" type="text" results=5 autosave="'. esc_url(home_url( '/' )) .'" class="input-text" placeholder="'. esc_attr__( 'Поиск продуктов', 'woothemes' ) .'" value="' . get_search_query() . '" name="s" id="s" />
        <button type="submit" class="button" id="searchsubmit" value="'. esc_attr__( 'Search', 'woothemes' ) .'" />
		    <input type="hidden" name="post_type" value="product" />
    </div>



    </form>';

	
    return $form;
}

function kidberries_variation() {
  wp_deregister_script( 'add-to-cart-variation' );
  wp_register_script( 'wc-add-to-cart-variation', get_template_directory_uri() . '/woocommerce/assets/js/frontend/add-to-cart-variation.js', array('jquery') ); // назначть новый скрипт
}
add_action( 'wp_enqueue_scripts', 'kidberries_variation' );


/* Именяем форматирование цены в get_price_html() для обычной цены.

	было:
	
2&nbsp;400&nbsp;руб</span>

	станет:

<div class="price-box">
	<p class="special-price">
		<span class="price">
			<span class="price digit"></span>
			<span class="price currency"></span>
		</span>
	</p>
</div>
*/

function kidberries_get_price_html_simple( $price ) {
	
	$i = mb_strlen($price);
	$i = $i - 24;
	$price =  mb_substr($price, 0, $i);
	$price .= '</span><span class="price currency">руб.</span></span></p></div>'; 
	$price = str_replace('<span class="amount">', '<div class="price-box"><p class="special-price"><span class="price">
									<span class="price digit">', $price);
	$price = str_replace('&nbsp;', ' ', $price);
	$price = str_replace('&nbsp;', ' ', $price);								


	return $price;
}

add_filter( 'woocommerce_variation_price_html', 'kidberries_get_price_html_simple', 100, 2 );

?>