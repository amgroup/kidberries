<?php
/**
 * Display single product reviews (comments)
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.3
 */
global $woocommerce, $product, $post;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php if ( comments_open() ) : ?>
	<span id="reviews">
		<?php if ( have_comments() ) : ?>
			<span class="add_review">
				<a href="#review_form" class="inline show_review_form button" title="<?php _e( 'Add Your Review', 'woocommerce' ); ?>">
					<?php _e( 'Add Review', 'woocommerce' ); ?>
				</a>
			</span>
		<?php else: ?>
			<span class="noreviews">
				<?php _e( 'There are no reviews yet, would you like to <a href="#review_form" class="inline show_review_form">submit yours</a>?', 'woocommerce' ); ?>
			</span>
		<?php endif; ?>
	</span>
<?php endif; ?>

<div id="review_form_wrapper" style="display:none;">
	<div id="review_form">

<?php
	$comment_form = array(
		'title_reply' => 'Напишите ваш собственный отзыв',
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'fields' => array(
			'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'woocommerce' ) . '</label> ' . '<span class="required">*</span>' .
			            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'woocommerce' ) . '</label> ' . '<span class="required">*</span>' .
			            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></p>',
		),
		'label_submit' => __( 'Submit Review', 'woocommerce' ),
		'logged_in_as' => '',
		'comment_field' => ''
	);

	if ( get_option('woocommerce_enable_review_rating') == 'yes' ) {

		$comment_form['comment_field'] = '<p class="comment-form-rating"><label for="rating">' . __( 'Rating', 'woocommerce' ) .'</label><select name="rating" id="rating">
			<option value="">'.__( 'Rate&hellip;', 'woocommerce' ).'</option>
			<option value="5">'.__( 'Perfect', 'woocommerce' ).'</option>
			<option value="4">'.__( 'Good', 'woocommerce' ).'</option>
			<option value="3">'.__( 'Average', 'woocommerce' ).'</option>
			<option value="2">'.__( 'Not that bad', 'woocommerce' ).'</option>
			<option value="1">'.__( 'Very Poor', 'woocommerce' ).'</option>
		</select></p>';

	}

	$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . __( 'Your Review', 'woocommerce' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>' . $woocommerce->nonce_field('comment_rating', true, false);

	comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
?>
	</div>
</div>
