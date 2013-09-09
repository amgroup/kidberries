<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
	
	$classes[] .= ' item';
?>

<?
$mode = $_REQUEST['mode'];
if ( $mode != 'list' ) {
?>

<li <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
	<div class="main-block-2 skew-block">
								<div class="corners-top"><div><div>&nbsp;</div></div></div>       
									<div class="content-box">
										
											
													
															
																<div class="corner-right-bot">                        <div class="product-box">
                            <div class="price-block"><div class="price-box">

                        
        </div></div>
		
		<a href="<?the_permalink();?>" class="product-image">

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?></a>

		<h3 class="product-name"></h3>
<div class="actions">

<?woocommerce_template_loop_add_to_cart();?>
                                                                    
                                                                <br class="clear" />
																
                            </div>
		
	
</div>










<div class='main2-shadow'><div class="corner-right-bot">
<a href="<?php the_permalink(); ?>"><div class="price-block">
							
							
							<div class="price-box">


							

				<?if ($product->is_on_sale() ) {?>
								
								

									<span class="old-price2 price" id="old-price-848-new">
										<span class="price currency"><?echo $product->regular_price;?></span>
									</span>
									
									<span class="special-price price">
										<span class="price digit"><?echo $product->price;?></span>
										<span class="price currency"> руб.</span>                
									</span>
								<?} else { ?>
								
								<span class="regular-price price" id="product-price-860-new">
									<span class="price digit"><?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?></span>
									<span class="price currency"> руб.</span>                
								</span>
								<?}?>			






							
						 
        </div></div></a>
		
		<a href="<?the_permalink();?>" class="product-image">

		</a>

		<h3 class="product-name"><a href="<?the_permalink();?>" title='<?the_title();?>'><?php the_title(); ?></a></h3>
<div class="actions">

<?woocommerce_template_loop_add_to_cart();?>
                                                                    
                                                                <br class="clear" />
																
                            </div>
		
</div></div>
                    </div>
															
														
												
										             
									</div>
									<div class="corners-bot"><div><div>&nbsp;</div></div></div>
								</div></li><?
	if ($woocommerce_loop['loop'] == 4 || $woocommerce_loop['loop'] == 8 || $woocommerce_loop['loop'] == 12 || $woocommerce_loop['loop'] == 16 ||$woocommerce_loop['loop'] == 20) {?></ul><ul class='products-grid'><?}
	?>
	
<?} else { ?>

<li class="item">
	<div class="main-block skew-block">
		<div class="corners-top"><div><div>&nbsp;</div></div></div>       
		<div class="content-box">
			<div class="border-bot">
				<div class="border-left">
					<div class="border-right">
						<div class="corner-left-top">
							<div class="corner-right-top">
								<div class="corner-left-bot">
									<div class="corner-right-bot">
										<div class="full-width">
											<div class="left">

												<a href="<?the_permalink();?>" class="product-image">
													<?do_action( 'woocommerce_before_shop_loop_item_title' );?>
												</a>
                                                
                                                    <p><?woocommerce_template_loop_add_to_cart();?></p>
													
                                            </div>
                    <div class="product-shop">
                        <div class="f-fix">
                                                        <h2 class="product-name"><a href="<?the_permalink();?>" title="<?the_title()?>"><?the_title()?></a></h2>
                                                        

        
    <div class="price-box">
                                
								
								
								<?if ($product->is_on_sale() ) {?>
								
								<p class="old-price">
									<span class="price-label">Обычная цена:</span>
									<span class="price" id="old-price-585">
										<span class="price digit"><?echo $product->regular_price;?></span>                  
										              
									</span>
								</p>

								<p class="special-price">
									<span class="price-label">Сегодня стоит:</span>
									<span class="price" id="product-price-585">
										<span class="price digit"><?echo $product->price;?></span>                  
										
									</span>
								</p>
								<?} else { ?>
								
								<p class="special-price">
									<span class="price" id="product-price-585">
										<span class="price digit"><?echo $product->price;?></span>                  
									</span>
								</p>
								<?}?>
								
								
								
                    
    
        </div>

                        
                            <div class="desc std">
                                <?the_content();?>                                 <a href="<?the_permalink()?>" title="<?the_title();?>" class="link-learn">Узнать больше</a>
                            </div>                        
                        </div>
                    </div>
                    <div class="clear"></div>
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
								</div> 
        </li>


<?}?>	