<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author	WooThemes
 * @package	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo kidberries_get_product_breadcrumbs();

echo '<div class="category ext description">';
echo kidberries_generate_description();
echo '</div>';
echo '<br clear="all">';

?>

