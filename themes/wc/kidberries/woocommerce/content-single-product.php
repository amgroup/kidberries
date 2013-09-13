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
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/skin/css/corlletelab/imagezoom.css" media="all" />
<style>

.product-essential h1, .product-essential h2, .product-essential h3, .product-essential h4 {
color:#336;
}

#sku {
color: #888;
font-weight: bold;
}

#shipping_method {
font-family: 'PT Sans', Arial;
font-size: 14px;
}

.simple.product .product-details > div {
margin-left: 20px;
}

.product-description, .product-information, .box-collateral {
padding-top: 30px;
font-family: 'PT Sans';
font-size: 14px;
font-style: normal;
line-height: 20px;
}

.product-description p, .product-information p {
margin-bottom: 8pt;
margin-top: 8pt;
}

.product-description h2, .product-information h2, .box-collateral h2 {
font-size: 18px;
font-style: bold;
}
.product-description ul, .product-information ul {
list-style: inherit;
padding: 8px 40px;
}

.product-description td, .product-information td, .product-description th, .product-information th {
padding: 10px 15px;
vertical-align: middle;
}

.product-description td p, .product-information td p {
margin: 0;
}

#upsell-product-table .price.digit, #upsell-product-table .price.currency {
display: inline;
}

.product-details.product-shop .price-box.onsale .old-price .price {
color: #BBB;
}


</style>

<?global $woocommerce, $product;

?>
<div class="product-name">
    <?php woocommerce_template_single_title(); ?>
    <p class="product-sku">
	Артикул: <span class="sku" id="sku" data-o_sku="<?echo $product->sku;?>"><?echo $product->sku;?></span>
    </p>
</div>

<!-- add-to-cart form -->
<form id="add_or_buy" action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
    <input type="hidden" name="redirect" id="redirect" value="" />
    <table class="simple product">
        <tr>
            <td class="product-img-box">
                <?woocommerce_show_product_images();?>
            </td>
            
            <td class="product-details product-shop">
                <div class="product delivery methods">
                    <div class="delivery-box">
                        <span class="title"><span class="big">Доставка:</span></span>
                        <ul id="shipping_method"></ul>
                        <small>* <em>стоимость доставки вашей корзины <strong>вместе с этим товаром</strong></em></small>
                    </div>
                </div>
                
                <div class="add-to-cart">
                    <?php woocommerce_template_single_add_to_cart();?>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="product-img-box more-views">
                <h2>Другие картинки:</h2>    
                <?php do_action( 'woocommerce_product_thumbnails' ); ?>
            </td>
        </tr>
    </table>
</form>

<hr/>
<div class="add-to">
    <p class="email-friend">
        <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
        <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="facebook,vkontakte,twitter"></div>
    </p>
    
    
    <?php if ( comments_open() ) : ?>
        <p class="rating">
            <p class="add_review">
                <a href="#review_form" class="inline show_review_form button" title="<?php _e( 'Add Your Review', 'woocommerce' ); ?>"><?php _e( 'Add Review', 'woocommerce' ); ?></a>
            </p>
            <?php call_user_func( 'comments_template', 'reviews' ) ?>
        </p>
    <?php endif;?>
</div>

<div class="product-description">
    <h2><?php echo esc_html( apply_filters('woocommerce_product_description_heading', __( 'Product Description', 'woocommerce' ) ) ); ?></h2>
    <div class="std">
        <?php the_content(); ?>
    </div>
</div>


<div class="product-information">
    <h2><?php echo esc_html( apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additional Information', 'woocommerce' ) ) ); ?></h2>
    <?php $product->list_attributes(); ?>
</div>

<?woocommerce_output_related_products();?>

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
    
    function buyitnow() { jQuery("#redirect").val( 'checkout' ); }
    
    var assocIMG = {};
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

</script>
<!-- /add-to-cart form -->
