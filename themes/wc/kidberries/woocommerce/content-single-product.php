<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
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
    global $woocommerce, $product, $post, $currency_symbol;

    if( $post->post_status != 'not_available' ) :
    do_action( 'woocommerce_before_single_product' );
?>

<?php /*/ ?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/corlletelab/imagezoom/cloud-zoom.1.0.2.js" ></script>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/skin/css/corlletelab/imagezoom.css" media="all" />
<?php /*/ ?>

<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/skin/js/woocommerce-product-thumbnails.js" ></script>

<div class="product-view" itemtype="http://schema.org/Product" itemscope="itemscope">
    <div class="before-product-name">
        <span class="product-sku">
            Артикул: <span class="sku" itemprop="productID" id="sku" data-o_sku="<?php echo $product->sku; ?>"><?php echo $product->sku; ?></span>
        </span>

        <span class="reviews" itemtype="http://schema.org/AggregateRating" itemscope="itemscope" itemprop="aggregateRating">
	    <meta content="1" itemprop="reviewCount" />
	    <meta content="5" itemprop="ratingValue" />

            <?php woocommerce_get_template( 'single-product-reviews/add-review-button.php' ); ?>

            <span class="email-friend">
                <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
                <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="facebook,vkontakte,twitter"></div>
            </span>
        </span>
    </div>

    <h1 itemprop="name" class="product-name"><?php echo esc_html( $post->post_title ); ?></h1>
    <meta content="<?php the_permalink();?>" itemprop="url" />

    <!-- add-to-cart form -->
    <form id="add_or_buy" action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>"  >
        <input type="hidden" name="redirect" id="redirect" value="" />


        <div class="product-img-box">
            <?php woocommerce_show_product_images(); ?>

            <div class="availability stock_container">
                <p class="stock in-stock">
                    <?php if ($product->is_in_stock()) : ?>
			<?php if ($product->is_expected() ) : ?>
                    	    <span class="stock expected">
				<?php echo kidberries_hr_date_interval( $product->expected(), 'Отгрузим через <span class="interval">%s</span>' ) . ' <span class="date">('. $product->expected() . ')</span>'; ?>
			    </span>
			<?php else : ?>
			    <span class="stock">Есть в наличии</span>
			<?php endif; ?>
                    <?php else : ?>
                        <span class="stock out-of-stock">Нет в наличии<?php echo $product->stock_status_date( ' <span class="date">(с %s)</span>' );?></span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="product-details product-shop">
            <div class="actions-box">
                <span class="price-box">
                    <span class="price" itemtype="http://schema.org/Offer" itemscope="itemscope" itemprop="offers">
			         <meta content="RUB" itemprop="priceCurrency" />
			         <link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

                        <?php if ( $product->is_on_sale() ) : ?>
                            <del><?php echo woocommerce_price( $product->regular_price ); ?></del>
                            <ins itemprop="price"><?php echo woocommerce_price( $product->price ); ?></ins>
                        <?php else: ?>
                            <?php if ( $product->product_type == 'variable' && $product->min_variation_price !== $product->max_variation_price) : ?>
                                <span itemprop="price variable">от <?php echo woocommerce_price( $product->min_variation_price ); ?></span>
                            <?php else: ?>
                                <span itemprop="price"><?php echo woocommerce_price( $product->price ); ?></span>
                            <?php endif;?>
                        <?php endif; ?>
                    </span>
                </span>

                <?php if ($product->is_in_stock()) : ?>
                    <button type="submit" class="buy btn btn-lg btn-success"><i class="glyphicon glyphicon-shopping-cart"></i> Добавить в корзину</button>
                <?php else : ?>
                    <button type="submit" disabled="disabled" class="buy btn btn-lg disabled"><i class="glyphicon glyphicon-ban-circle"></i> Нет в наличии</button>
                <?php endif; ?>
                <a style="display: none" rel="nofollow" class="checkout btn btn-lg btn-link" href="<?php echo $woocommerce->cart->get_checkout_url(); ?>">Оформить заказ &rarr;</a>
                <a rel="nofollow" class="delivery btn btn-lg btn-link" href="#tab-delivery">Узнать стоимость доставки <i class="delivery-icon-orange"></i></a>
            </div>

            <div class="product-variations">
                <?php woocommerce_template_single_add_to_cart();?>
            </div>


            <div class="product-thumbnails">
                <?php do_action( 'woocommerce_product_thumbnails' ); ?>
            </div>

        </div>

        <br clear="all"/>


        <?php woocommerce_get_template( 'content-single-product-ext-description.php' ); ?>

        <?php if ( ! $product->is_in_stock() ) woocommerce_output_related_products(); ?>


        <div class="woocommerce-tabs">
            <ul class="tabs">
                <li class="info_tab"><a href="#tab-info"><h1>Характеристики</h1></a></li>
                <li class="description_tab"><a href="#tab-description"><h1>Описание</h1></a></li>
                <li class="delivery_tab"><a href="#tab-delivery"><h1>Доставка</h1></a></li>
            </ul>
            
            <div class="panel entry-content product-information" id="tab-info" itemprop="description">
                <?php $product->list_attributes(); ?>
            </div>
            
            <div class="panel entry-content product-description" id="tab-description">
                <h2><?php echo esc_html( apply_filters('woocommerce_product_description_heading', __( 'Product Description', 'woocommerce' ) ) ); ?></h2>
                <div class="std">
                    <?php the_content(); ?>
                </div>

            </div>

            <div class="panel entry-content product-delivery" id="tab-delivery">
            <?php if ( $product->is_in_stock() ) : ?>
                <h2>Стоимость и условия доставки</h2>
                <p><i class="glyphicon glyphicon-info-sign"></i> Наш магазин находится в Москве и отгрузка заказов производятся из Москвы. Доставка товаров осуществляется сторонними службами по всей России, а при оплате пластиковой картой или с помощью платёжной системы PayPal возможна доставка в Украину и Беларусь.</p>
                <p>Заказы передаются в курьерскую, почтовую или в другую службу доставки каждый день, включая выходные и праздничные дни, на следующий после заказа день.</p>
                <br />

                <p><em>Ниже показаны варианты и стоимость доставки товаров в вашей корзине <strong>уже вместе с этим товаром.</strong></em></p>
                <div class="shipping">
                    <div id="product_shipping_methods">
                        <input type="hidden" name="product_id" value="<?php echo $post->ID; ?>" />
                        <div><?php woocommerce_get_template( 'shipping/methods.php' ); ?></div>
                    </div>
                </div>
            <?php endif; ?>
            </div>
        </div>

    </form>
    <!-- /add-to-cart form -->

    <?php if ( $product->is_in_stock() ) woocommerce_output_related_products(); ?>

    <?php //woocommerce_get_template( 'single-product-reviews/reviews-block.php' ); ?>
</div>


<script type="text/javascript">
    jQuery(document).ready( function($) {
        $.post( woocommerce_params.ajax_url, {action : 'get_data_product_variations', 'product_id':$("form#add_or_buy").data("product_id") }, function(data) {});

        var data = {
            action:     "is_product_in_cart",
            product_id: $("form#add_or_buy").data("product_id")
        };
        $.post( woocommerce_params.ajax_url, data, function(state) {
            if( state.cart.notempty )
                $('.checkout').show();
            if( state.cart[ $("form#add_or_buy").data("product_id") ] )
                $('form#add_or_buy button[type=submit]').html('<i class="glyphicon glyphicon-shopping-cart"></i> Купить еще <small class="extra label label-success">Уже в корзине</small>');
        });
    });


    function buyitnow() { jQuery("#redirect").val( 'checkout' ); }

    jQuery('.btn.delivery').click( function(e){
        e.preventDefault();
        jQuery('.woocommerce-tabs .tabs li').removeClass('active');
        jQuery('.woocommerce-tabs .tabs li.delivery_tab').addClass('active');
        jQuery('.panel.entry-content').hide();
        jQuery('.panel.entry-content.product-delivery').show();

        jQuery('html, body').animate({scrollTop: jQuery(".woocommerce-tabs").offset().top - jQuery(window).height()/2 + jQuery(".woocommerce-tabs").height()/2 }, 300);
    });

</script>
<?php else :
    $s = $_GET['s'];

    if( !$s ) {
        $l = split("-", $post->post_title );
        if( $l[0] ) $s = esc_attr( $l[0] );

	$attributes = $product->get_attributes();
	$name = 'pa_brand';
	if ( isset( $attributes[$name]['is_taxonomy']) ) {
		$values = woocommerce_get_product_terms( $product->id, $attributes[$name]['name'], 'names' );
		$s .= esc_attr( implode( ', ', $values ) );
	}
    }


?>

    <h1>К сожалению <?php the_title();?> <ins>устарел и больше не будет продаваться</ins>.</h1>
    <br/>
    <br/>
    <?php
        echo kidberries_get_product_breadcrumbs();
        woocommerce_output_related_products();
    ?>


    <div class="box-collateral">
        <h2>Или воспользуйтесь формой для поиска:</h2>
    </div>
    <form role="search" method="get" id="searchform" action="/">
	<div class="form-search">
	    <input style="margin-top: 1px; font-size: 18px; width: 780px; padding: 4px; border: 4px solid #F6BD2E; border-radius: 5px;" id="search" type="text" results="5" autosave="/" class="input-text" placeholder="Поиск товаров" value="<?php echo $s;?>" name="s" />
	    <input type="hidden" name="post_type" value="product" />
	    <button style="border: 4px solid #85C72C;" type="submit" class="btn">Найти</button>
	</div>
    </form>

<?php endif; ?>
