<div class="footer-container">
    <div class="page">
        <div class="footer">
          <?php if ( is_active_sidebar( 'down-widget-area' ) ) : ?>
				<?php dynamic_sidebar( 'down-widget-area' ); ?>
			<?php endif; ?>
          <br class="clear" />
          <address>Детки-Ягодки™ &copy; 2013</address>
        </div>
    </div>
</div>
<script type="text/javascript">Cufon.now();</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter22524397 = new Ya.Metrika({id:22524397,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/22524397" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<?wp_footer();?>
</body>
</html>