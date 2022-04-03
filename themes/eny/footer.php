<footer id="footer">
<div class="area">
		<ul>
			<?php foreach (get_categories() as $category_value) : ?>
			<li><a href="<?php echo home_url();?>/category/<?php echo $category_value->slug; ?>">
			<?php echo $category_value->cat_name; ?></a></li>
			<?php endforeach; ?>
		</ul>
		<ul>
			<li><a href="<?php echo home_url(); ?>/運営者情報">運営者情報</a></li>
		</ul>
	<div class="clear"></div>
	<br/>
	<p class="fontss">Copyright © <?php bloginfo( 'name' ); ?> All Rights Reserved.</p>
	</div>
</footer>
<script>
	(function () {
		var webfonts2 = document.getElementById('fontawesome');
		webfonts2.rel = 'stylesheet';
	})();
</script>
<script type="text/javascript">
$(".search_font").click(function(){
  $("#search_form").toggleClass("is_active");
  $(".hammenu").removeClass("active");
  $(".topnavi").removeClass("is_active");
  $("body").removeClass("noscroll");
});
$('.hammenu').on('click', function(e){
  $(this).toggleClass('active');
  $(".topnavi").toggleClass("is_active");
  $("#search_form").removeClass("is_active");
  $("body").toggleClass("noscroll");

});
</script>
<?php wp_footer() ?>