<?php
/**
 * Admin new order email
 *
 * @author Kidberries TEAM
 * @package kidberries/woocommerce/templates/sms
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

echo 'Новый заказ ' . strip_tags( $order->get_order_number() ). "\n";

$totals = $order->get_order_item_totals();

echo 'На сумму с учётом доставки: ' . strip_tags( $totals['order_total']['value'] ) . "\n";
if( isset($totals['shipping']['value']) ) { echo 'Доставка: ' . strip_tags( $totals['shipping']['value'] ) . "\n"; }

?>