<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked woocommerce_show_messages - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/corlletelab/imagezoom/cloud-zoom.1.0.2.js" ></script>

					<style type="text/css">
						.cloud-zoom-lens {
							border: 2px solid #f5bc2e;
							margin:-2px;	/* Set this to minus the border thickness. */
							background-color: #FFFFFF;	
						}
						.cloud-zoom-big {
							border: 3px solid #bd1f60;
						}
					</style>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/skin/css/corlletelab/imagezoom.css" media="all" />

<?global $product; ?>
<?//echo $product->get_price_html()?>


<table border="0">
	<tr>
		<td class="product-img-box">

									<?woocommerce_show_product_images();?>

        </td>

		<td class="product-shop">
		<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
            <div>
				<table border="0">
					<?php if( $product->is_in_stock()) :?>
					<tr>
						<td colspan="3" class="product delivery methods">
							<div class="delivery-box">
								<span class="title"><span class="big">Доставка:</span></span>
								<ul id="shipping_method"></ul>
								<small>* <em>стоимость доставки вашей корзины <strong>вместе с этим товаром</strong></em></small>
							</div>
							<script type="text/javascript">
								jQuery(document).ready( function() {
									var data = {
										action:         "get_dynamic_shipping",
										security:       woocommerce_params.update_shipping_method_nonce,
										product_id:     jQuery("form.cart").data("product_id"),
										variation_id:   jQuery("input[name=variation_id]").val()
									};
									jQuery("#shipping_method").block({message: null, overlayCSS: {background: "#fff url(" + woocommerce_params.ajax_loader_url + ") no-repeat center", backgroundSize: "16px 16px", opacity: 0.6}});
									jQuery.post( woocommerce_params.ajax_url, data, function(response) { jQuery("#shipping_method").replaceWith( response ); });
								});
							</script>
							<hr clear="all"/>

						</td>
					</tr>
					<?php endif; ?>
					<tr>

					

						<td class="product-name">
												
							<?woocommerce_template_single_title();?>
							<p class="product-sku">Код товара: <span class="sku" id='sku' data-o_sku="<?echo $product->sku;?>"><?echo $product->sku;?></span></p>
						</td>

						<td class="price-cont">
							<? if ($product->product_type != 'variable') { ?>
							<div class="price-box">
								<?if ($product->is_on_sale() ) {?>
								
								<p class="old-price">
									<span class="price-label">Обычная цена:</span>
									<span class="price" id="old-price-585">
										<span class="price digit"><?echo $product->regular_price;?></span>                  
										<span class="price currency">&nbsp;&nbsp;руб.</span></span>
								</p>

								<p class="special-price">
									<span class="price-label">Сегодня стоит:</span>
									<span class="price" id="product-price-585">
										<span class="price digit"><?echo $product->price;?></span>                  
										<span class="price currency">&nbsp;&nbsp;руб.</span></span>
								</p>
								<?} else { ?>
								
								<p class="special-price">
									<span class="price" id="product-price-585">
										<span class="price digit"><?echo $product->price;?></span>                  
										<span class="price currency">&nbsp;&nbsp;руб.</span></span>
								</p>
								<?}?>
							</div>
							<? } else { ?>
							<div class="price-box">
								<p class="special-price">
									<span class="price">
										<span class="price currency">от</span> 
										<span class="price digit"><?php echo get_post_meta( $product->id, '_min_variation_price', true );?></span>
										<span class="price currency">&nbsp;&nbsp;руб.</span></span></p>
							</div>
							<?}?>
						</td>

						<td class="add-to">
							<p class="email-friend">
								<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
								<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="facebook,vkontakte,twitter"></div>
							</p>
							
							<p class="no-rating">
<? if ( comments_open() ) : echo '<p class="add_review"><a href="#review_form" class="inline show_review_form button" title="' . __( 'Add Your Review', 'woocommerce' ) . '">' . __( 'Add Review', 'woocommerce' ) . '</a></p>'; endif;?>
							</p>
						

							<!--ul class="add-to-links">
								<li><a href="http://kidberries.com/wishlist/index/add/product/585/" onclick="productAddToCartForm.submitLight(this, 'http://kidberries.com/wishlist/index/add/product/585/'); return false;" class="link-wishlist">в желания</a></li>
								<li><span class="separator">|</span> <a href="http://kidberries.com/catalog/product_compare/add/product/585/uenc/aHR0cDovL2tpZGJlcnJpZXMuY29tL3Noa29sLW55ai1yYW5lYy1yanVremFrLXdoZWVscGFrLXplbml0aC1ibGFjay1uYS1rb2xlc2FoLTUtNy1rbGFzcy0yNS1saXRyLmh0bWw,/" class="link-compare">сравнить</a></li>
							</ul-->

						</td>
					</tr>

                    <tr class="splitter">
						<td colspan="3"></td>
					</tr>

					<tr>
						<td/>
						<td/>
						<td/>
					</tr>

					<tr class="add-to-cart">
							
							<?php woocommerce_template_single_add_to_cart();?>
							<!--button type="button" title="В корзину" class="button" onclick="productAddToCartForm.submit(this)"><span><span>В корзину</span></span></button>
							<!--button type="button" title="В корзину" class="button btn-cart" onclick="productAddToCartForm.submit(this)">В корзину</button-->
						
					</tr>

					<tr>
						<td></td>
						<td></td>
					</tr>

					<tr>
						<td colspan="3"></td>
					</tr>
				</table>
			</div>
		</form>
		</td>
    </tr>

    <tr>
		<td colspan="2" class="product-img-box more-views">

			<script type="text/javascript">
			  var assocIMG = { // Added
				}   
			</script>

			<script type="text/javascript">
				function jSelectImage(id) {
					hideSelected(id);
					
					if (assocIMG['big_image_'+id] && assocIMG['small_image_'+id]) {
						// Destroy the previous zoom
						jQuery('#image-zoom').data('zoom').destroy();
						// Change the biglink to point to the new big image.
						jQuery('#image-zoom').attr('href', assocIMG['big_image_'+id]);
						// Change the small image to point to the new small image.
						jQuery('#image-zoom img').attr('src', assocIMG['small_image_'+id]);
						// Init a new zoom with the new images.				
						jQuery('#image-zoom').CloudZoom();
						//console.log('yes');
					}
				}
				
				function hideSelected(id) {
					//console.log(id);
					var add = $$('.more-views li.add');
					if (add && add.length > 0) {
						$('show-all').show();
						add.each(function(item,i){
							item.hide();
							var className = 'item-'+id;
							
							if (item.hasClassName(className)){
								item.show();
							}
						})
					}
				}
				
				function showAll() {
					var add = $$('.more-views li.add');
					if (add && add.length > 0) {
						$('show-all').hide();
						add.each(function(item,i){
							item.show();				
						})
					}	
				}
				
			</script>

			<a id="show-all" class="f-right" style="display: none; font-size: 11px" href="#" onclick="javascript:showAll(); return false;" title="Show All">Show All</a>

			<h2>Другие картинки:</h2>    
    <?php do_action( 'woocommerce_product_thumbnails' ); ?>
			
        </td>
    </tr>

	<tr>
		<td class="short-description" colspan="2">
			<h2>Краткая информация</h2>
				<div class="std">
					<?
					
					global $woocommerce, $post;
					$heading = esc_html( apply_filters('woocommerce_product_description_heading', __( 'Product Description', 'woocommerce' ) ) );
					the_content(); 
					
					?>
					
				</div>
        </td>
    </tr>
</table>
<div class="clearer"></div></div>

<div class="product-collateral">
                <div class="box-collateral box-description">
                                    <h2>Подробности</h2>
    <div class="std">
	<p><?php the_title(); ?></p>
	<?woocommerce_template_single_excerpt();?>

	
</div></div>

<div class="box-collateral box-additional">

<?$heading = apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additional Information', 'woocommerce' ) );
?>

<h2><?php echo $heading; ?></h2>

<?php $product->list_attributes(); ?>
</div>

<?php call_user_func( 'comments_template', 'reviews' ) ?>

<br>
<?woocommerce_output_related_products();?>

<!--div itemscope="" itemtype="http://schema.org/Product" id="product-<?//php the_ID(); ?>" <?php //post_class(); ?>-->

	<?php
		/**
		 * woocommerce_show_product_images hook
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		//do_action( 'woocommerce_before_single_product_summary' );
		//woocommerce_show_product_images();
	?>

	<!--div class="summary entry-summary"-->

		<?php
			/**
			 * woocommerce_single_product_summary hook
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			
		?>

	<!--/div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 */
		//do_action( 'woocommerce_after_single_product_summary' );
	?>

<!--/div><!-- #product-<?php// the_ID(); ?> -->

<?php //do_action( 'woocommerce_after_single_product' ); ?>