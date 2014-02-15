<?php ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title><?php
    if( is_product() ) {
        echo "Купить ";
        wp_title('-', true, 'right');
        echo " за " . kidberries_get_price() . " - ";
        bloginfo('name');
    } else if( is_product_category() ) {
	$category = get_queried_object();
	echo esc_html( $category->name ) . ' - ';
	bloginfo('name');
	echo " - ";
	bloginfo('description');
    } else if( is_page( woocommerce_get_page_id( 'shop' ) ) ) {
        bloginfo('name');
        echo " - ";
	bloginfo('description');
    } else if( is_cart() || is_checkout() || is_account_page() ) {
        _e( get_the_title(), 'woocommerce');
    } else {
	_e( get_the_title(), 'woocommerce');
	echo ' - ';
        bloginfo('name');
    }
?></title>

<?php if( is_product() ) : ?>
	<link rel="canonical" href="<?php the_permalink(); ?>" />

	<meta name="Description" content="<?php echo kidberries_get_product_header_description(); ?>" />

	<meta name="Keywords" content="<?php echo kidberries_get_product_header_keywords(); ?>" />
<?php elseif( is_product_category() ) : ?>
	<link rel="canonical" href="<?php echo get_term_link( $category->slug, 'product_cat' ) . ( get_query_var('paged') ? 'page/' .  get_query_var('paged') . '/' : '' ); ?>" />

	<meta name="Description" content="<?php if ($category->description) echo strip_tags( kidberries_synonymize( $category->description ) ); else bloginfo('description');  ?>" />

	<meta name="Keywords" content="<?php echo kidberries_get_product_category_header_keywords( $category->name );?>" />
<?php else : ?>
	<meta name="Description" content="<?php bloginfo('description'); ?>" />

	<meta name="Keywords" content="Детки Ягодки,качественные,импортные,дешевые,дорогие,детские товары,деткая одежда,игрушки,развивающие,велосипед,самокат,скутер,ролики,коляска,санки,Европы,Германии,Англии,Великобритании,Новой Зеландии,Дании,для детей,для ребенка" />
<?php endif; ?>


<?php
    if( is_cart() || is_checkout() || is_account_page() )
        echo '<meta name="robots" content="NOINDEX,NOFOLLOW" />';
    else
        echo '<meta name="robots" content="INDEX,FOLLOW" />';
?>

<?php wp_head();?>
<link rel="search" type="application/opensearchdescription+xml" title="<?php bloginfo('template_url'); ?>" href="//<?php bloginfo('template_url'); ?>/open_search_ru.xml" />

<?php /*/ ?>
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/skin/images/kidberries-round16x16.ico" type="image/x-icon" />
<link rel="icon" href="<?php bloginfo('template_url'); ?>/skin/images/kidberries-round16x16.png" type="image/png" />
<?php /*/ ?>

<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/skin/images/kidberries-cart-favicon-16x16.ico" type="image/x-icon" />
<link rel="icon" href="<?php bloginfo('template_url'); ?>/skin/images/kidberries-cart-favicon-32x32.png" type="image/png" />


<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/skin/js/imagepreloader.js"></script>

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.header .links li').each(function(index){
			jQuery(this).addClass('link-'+(index+1));
		});		
		jQuery('.footer li').each(function(index){
			jQuery(this).addClass('link-'+(index+1));
		});
	});	

	preloadImages([
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_bg.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_bg_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_corner_left.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_corner_left_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_corner_right.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_corner_right_active.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_2_bg.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_2_bg_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_2_corner_left.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_2_corner_left_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_2_corner_right.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_2_corner_right_active.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_3_bg.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_3_bg_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_3_corner_left.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_3_corner_left_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_3_corner_right.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_3_corner_right_active.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_4_bg.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_4_bg_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_4_corner_left.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_4_corner_left_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_4_corner_right.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_4_corner_right_active.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_5_bg.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_5_bg_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_5_corner_left.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_5_corner_left_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_5_corner_right.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_5_corner_right_active.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_6_bg.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_6_bg_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_6_corner_left.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_6_corner_left_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_6_corner_right.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_6_corner_right_active.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_7_bg.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_7_bg_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_7_corner_left.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_7_corner_left_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_7_corner_right.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_7_corner_right_active.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_8_bg.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_8_bg_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_8_corner_left.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_8_corner_left_active.png',
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_8_corner_right.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/menu_button_8_corner_right_active.png',		
		'<?php bloginfo('template_url'); ?>/skin/images/list_button_active.png'
		]);
</script>
<!--[if lt IE 7]>
<script type="text/javascript">
//<![CDATA[
    var BLANK_URL = 'http://<?php bloginfo('template_url'); ?>/js/blank.html';
    var BLANK_IMG = 'js/spacer.gif'/*tpa=http://<?php bloginfo('template_url'); ?>/js/spacer.gif*/;
//]]>
</script>
<![endif]-->
<!--[if lt IE 7]>
<div style=' clear: both; height: 59px; padding:0 0 0 15px; position: relative;'> 
	<a href="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode  \n\nThis file was not retrieved by Teleport Pro, because it is addressed on a domain or path outside the boundaries set for its Starting Address.  \n\nDo you want to open it from the server?%27))window.location=%27http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode%27" tppabs="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode"><img src="../www.ie6countdown.com/index.htm" tppabs="http://www.theie6countdown.com/images/upgrade.jpg" border="0" height="42" width="820" alt="" /></a>
</div> 
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/skin/css/styles.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/skin/css/fonts.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/skin/css/print.css" media="print" />
<!--[if lt IE 8]>
<link rel="stylesheet" type="text/css" href="skin/css/styles-ie.css" media="all" />
<![endif]-->
<!--[if lt IE 7]>
<script type="text/javascript" src="js/lib/ds-sleight.js"></script>
<script type="text/javascript" src="skin/frontend/base/default/js/ie6.js"></script>
<![endif]-->
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/prototype/prototype.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/varien/menu.js"></script>
</head>