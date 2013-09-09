<?get_header()?>
<body class=" cms-index-index cms-home">
<div class="wrapper-top">
	<div class="wrapper-bot">
		<div class="wrapper">
			    <noscript>
        <div class="noscript">
            <div class="noscript-inner">
                <p><strong>Скорее всего в вашем браузере отключён JavaScript.</strong></p>
                <p>Вы должны включить JavaScript в вашем браузере, чтобы использовать функциональные возможности этого сайта.</p>
            </div>
        </div>
    </noscript>
            <div class="page">
                <div class="header-container">
    <div class="header">
	
	
	
<!--------------------------------------ЛОГОТИП-->

	
	
	
      <div class="image">
                <h1 class="logo"><strong>Магазин для мам, пап и их деток</strong><a href="<?php bloginfo('url'); ?>/" title="Магазин для мам, пап и их деток" class="logo"><img src="<?php bloginfo('template_url'); ?>/skin/images/logo_email.png" alt="Магазин для мам, пап и их деток" /></a></h1>
              </div>
			  
			  
<!--------------------------------------ТЕЛЕФОНЫ -->

			  
			  
			  
      <div class="phone">
        <!--div class="label">звонок по России бесплатный</div-->
        <!--div class="number"><span>8-<span>800</span>-</span>555-09-18</div-->
        <div class="label">Звоните нам (с 10:00 до 21:00)</div>
	<div class="number"><a href="tel:+74993906277" class="number">8 499 390-62-77</a></div>
	<div class="number"><a href="tel:+79258601068" class="number">8 925 860-1-068</a></div>
      </div>
      <div class="controls">
        <div class="quick-access">
                              <br class="clear" />
          <p class="welcome-msg"></p>
<!--------------------------------------ВЕРХНИЕ ССЫЛКИ-->

          <?php if ( is_active_sidebar( 'top-widget-area' ) ) : ?>
				<?php dynamic_sidebar( 'top-widget-area' ); ?>
			<?php endif; ?>
          <br class="clear" />

		  
		  
<!--------------------------------------ФОРМА ПОИСКА -->




          
<?echo kidberries_search_form()?>

        </div>
              </div>
    </div>
</div>



<!--------------------------------------НАВИГАЦИОННАЯ ПАНЕЛЬ -->

<?php $args = array(  
  'theme_location'  => 'top',  
  'menu'            => '',   
  'container'       => 'div',   
  'container_class' => 'nav-container',   
  'container_id'    => '',  
  'menu_class'      => false,   
  'menu_id'         => 'nav',  
  'echo'            => true,  
  'fallback_cb'     => 'wp_page_menu',  
  'before'          => '',  
  'after'           => '',  
  'link_before'     => '<span>',  
  'link_after'      => '</span>',  
  'items_wrap'      => '<ul id="%1$s" class=\"%2$s\">%3$s</ul>',  
  'depth'           => 0
);  
  
wp_nav_menu( $args );   ?>


<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/skin/js/menu.js"></script>





<!------------------------------------КОНЕЦ НАВИГАЦИИ-->


<br class="clear" />
                
                                    
<?php woocommerce_content(); ?>
                                
                   
                <script type="text/javascript">
            jQuery(document).ready(function(){
return;
                var prices = jQuery('.new-product .price-block .price.digit');
                prices.each(function(){
                    var this_price = jQuery(this).html();
                    var currency = this_price.slice(0, 1);                    
                    this_price = Math.round(this_price.slice(1));
                    jQuery(this).html(this_price);
                    jQuery(this).parent().parent().parent().find('.currency').html(currency);
                });                
            });
        </script>
 </div>
                        </div>
						
	<!--	--------------------------	--------------САЙДБАР --->					
						
                        
                    </div>
                </div>        
        	</div>
        </div>
    </div>
</div>

<!--	--------------------------	--------------ФУТЕР --->
<?get_footer()?>