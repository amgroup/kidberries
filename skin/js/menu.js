jQuery('#nav > li').each(function(index){
	jQuery(this).addClass('menu-button-'+(index+1));
	jQuery(this).find('a').first().wrap('<div class="menu-button-layer" />');
	jQuery(this).find('span').first().wrapInner('<b />');
});
