<?php
/**
 * Login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

if (is_user_logged_in()) return;
?>
<form method="post" class="form-horizontal login" <?php if ( $hidden ) echo 'style="display:none;"'; ?>>
	<?php if ( $message ) echo wpautop( wptexturize( $message ) ); ?>

	<div class="form-row control-group validate-required" id="username_field">
	    <label for="username" class="control-label">Логин</label>
	    <div class="control-field">
		<input type="text" class="input-text" name="username" id="username" placeholder="Логин" value="">
	    </div>
	</div>

	<div class="form-row control-group validate-required" id="password_field">
	    <label for="password" class="control-label">Пароль</label>
	    <div class="control-field">
		<input type="password" class="input-text" name="password" id="password" placeholder="Пароль" value="">
	    </div>
	</div>


	<div class="form-row control-group validate-required" id="getpassword_field">
	    <label for="getpassword" class="control-label">
		
	    </label>
	    <div class="control-field">
		<button type="submit" class="btn btn-success" name="login"> Войти <i class="glyphicon glyphicon-user"></i></button>
		<a name="getpassword" id="getpassword" class="lost_password btn btn-link" href="<?php echo esc_url( wp_lostpassword_url( home_url() ) ); ?>"><?php _e( 'Lost Password?', 'woocommerce' ); ?></a>
	    </div>
	</div>
	<?php $woocommerce->nonce_field('login', 'login') ?>
	<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ) ?>" />

	<div class="clear"></div>
</form>