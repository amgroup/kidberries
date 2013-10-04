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

global $woocommerce, $product, $woocommerce_loop, $currency_symbol;

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
	
	$classes[] = 'item';
?>

<?
$mode = $_REQUEST['mode'];
if ( $mode != 'list' ) {
?>

<li <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
        <div class="main-block-2 skew-block">
            <div class="corners-top">
                <div>
                    <div>&nbsp;</div>
                </div>
            </div>       
            <div class="content-box">
                <div class="corner-right-bot">
                    <div class="product-box">
    
                        <a href="<?the_permalink();?>" class="product-image"><?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?></a>
                        <h3 class="product-name"></h3>
                        <div class="actions">
                            <?woocommerce_template_loop_add_to_cart();?>
                            <br class="clear" />
                        </div>
                    </div>

                    <div class='main2-shadow'>
                        <div class="corner-right-bot">
                            <a href="<?php the_permalink(); ?>">
                                <div class="price-block">
                                    <?php if ( $product->is_on_sale() ) : ?>
                                        <div class="price-box onsale">
                                            <span class="old-price price" id="old-price-<?php echo $product->id; ?>-new">
                                                <span class="price digit"><?echo $product->regular_price;?></span>
                                            </span>
                                            
                                            <span class="special-price price">
                                                <span class="price digit"><?echo $product->price;?></span>
                                                <span class="price currency"> <?php echo $currency_symbol; ?></span>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div class="price-box regular">
                                            <span class="regular-price price" id="product-price-<?php echo $product->id; ?>-new">
                                                <span class="price digit"><?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?></span>
                                                <span class="price currency"> <?php echo $currency_symbol; ?></span>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>

                            <a href="<?the_permalink();?>" class="product-image">
                                <?php
                                    if( ($term = get_the_terms( $product->id, 'pa_designed')) !== false ) {
                                        $designed = each($term)['value'];
                                        $path     = '/skin/images/flags/codes/' . $designed->slug . '-32.png';
                                        if( is_file( THEME_DIR . $path ) ) {
                                            $alt = htmlspecialchars( $woocommerce->attribute_label( $designed->taxonomy ) . ' - ' . $designed->name );
                                            $src = get_template_directory_uri() . $path;

                                            echo '<img class="designed country flag" src="' . $src . '" alt="' . $alt . '" title="' . $alt . '" />';

                                            foreach( preg_split("/[\s,]+/", $designed->name) as $name_part ) {
                                                echo '<p class="designed country name">' . htmlspecialchars($name_part) . '</p>';
                                            }
                                        }
                                    }

                                    if( ($term = get_the_terms( $product->id, 'pa_produced')) !== false ) {
                                        $produced = each($term)['value'];
                                        $path     = '/skin/images/flags/codes/' . $produced->slug . '-32.png';
                                        if( is_file( THEME_DIR . $path ) ) {
                                            $alt = htmlspecialchars( $woocommerce->attribute_label( $produced->taxonomy ) . ' - ' . $produced->name );
                                            $src = get_template_directory_uri() . $path;

                                            echo '<img class="produced country flag" src="' . $src . '" alt="' . $alt . '" title="' . $alt . '" />';

                                            foreach( preg_split("/[\s,]+/", $produced->name) as $name_part ) {
                                                echo '<p class="produced country name">' . htmlspecialchars($name_part) . '</p>';
                                            }
                                        }
                                    }
                                ?>
                            </a>
                            <?php if ( ! $product->is_in_stock() ) : ?>
                                <p class="not_in_stock">Нет в наличии</p>
                            <?php endif;?>
                            <h3 class="product-name"><a href="<?the_permalink();?>" title="<?php the_title();?>"><?php the_title(); ?></a></h3>
                            <div class="actions">
                                <?php woocommerce_template_loop_add_to_cart();?>
                                <br class="clear" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="corners-bot">
                <div>
                    <div>&nbsp;</div>
                </div>
            </div>
        </div>
    </li>
<?php if ($woocommerce_loop['loop'] == 4 || $woocommerce_loop['loop'] == 8 || $woocommerce_loop['loop'] == 12 || $woocommerce_loop['loop'] == 16 ||$woocommerce_loop['loop'] == 20) { echo '</ul><ul class="products-grid">'; } ?>
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
                                                    <?php if ( ! $product->is_in_stock() ) : ?>
                                                        <p class="not_in_stock">Нет в наличии</p>
                                                    <?php endif;?>
                                                    <a href="<?the_permalink();?>" class="product-image">
                                                        <?do_action( 'woocommerce_before_shop_loop_item_title' );?>
                                                    </a>
                                                    <p><?woocommerce_template_loop_add_to_cart();?></p>
                                                </div>
                                                <div class="product-shop">
                                                    <div class="f-fix">
                                                        <h2 class="product-name"><a href="<?the_permalink();?>" title="<?the_title()?>"><?the_title()?></a></h2>

                                                        <?php if ($product->is_on_sale() ) : ?>
                                                            <div class="price-box onsale">
                                                                <p class="old-price">
                                                                    <span class="price-label">Обычная цена:</span>
                                                                    <span class="price" id="old-price-<?php echo $product->id; ?>">
                                                                        <span class="price digit"><?echo $product->regular_price;?></span>
                                                                        <span class="price currency"> <?php echo $currency_symbol; ?></span>
                                                                    </span>
                                                                </p>
                                                                <p class="special-price">
                                                                    <span class="price-label">Сегодня стоит:</span>
                                                                    <span class="price" id="product-price-<?php echo $product->id; ?>">
                                                                        <span class="price digit"><?echo $product->price;?></span>
                                                                        <span class="price currency"> <?php echo $currency_symbol; ?></span>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        <?php else : ?>
                                                            <div class="price-box regular">
                                                                <p class="special-price">
                                                                    <span class="price" id="product-price-<?php echo $product->id; ?>">
                                                                        <span class="price digit"><?echo $product->price;?></span>
                                                                        <span class="price currency"> <?php echo $currency_symbol; ?></span>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="desc std">
                                                            <?the_content();?>
                                                            <a href="<?the_permalink()?>" title="<?the_title();?>" class="link-learn">Узнать больше</a>
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