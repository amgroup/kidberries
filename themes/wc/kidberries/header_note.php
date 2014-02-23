<div id="delivery_note" style="text-align:  center; color: #ddd;">
  <span style="font-weight: bold; color: #eee;">У</span>скоренная доставка по России от 3 до 11 дней. При оплате через
  <span style="font-weight: bold; color: #0079c1; font-style: italic; text-shadow: 0 1px 0px rgba(255, 255, 255, 0.7);"><span style="color: #00457c;">Pay</span>Pal</span> возможна доставка в <span style="font-weight: bold; color: #eee;">Б</span><span style="color: #FF6C6C;">елару</span><span style="color: #82D882;">сь</span> и <span style="color: yellow;">Укр</span><span style="color: #9292F3;">аину</span> (предварительно свяжитесь с нами по телефону)!
</div>

<script type="text/javascript">
    jQuery.ajax({
        url: "/wp-admin/admin-ajax.php?action=get_geoip_location",
        success: function(data) {
            if( data.cc == 'RU' && data.city != '' && data.city != 'Москва' ) {
                jQuery("#delivery_note").empty().append("<span style=\"color: #6CFF6C;\"><strong>" + data.city + "</span> &mdash; доставка от 3 до 6 дней</strong>. </span>");
            }
        }
    });
</script>

