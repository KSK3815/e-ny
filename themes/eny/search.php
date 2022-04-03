<?php get_header();?>

<body id="content">
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WQ7JKXS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<div id="container">
		<div class="area">
			<?php include_once("inc_header.php"); ?>
			<div id="main" class="content_left">

				<?php include_once("inc_header.php"); ?>
				<?php // breadcrumb();?>
				<h1 class="entry-title article">「<?php the_search_query(); ?>」の検索結果</h1>
				<?php while (have_posts()) {
    the_post();
    echo "<a href='".get_the_permalink()."' class='block hover'>
					<div class='list_area'>
						<div class='list_left'>
							<img class='cover' src='".get_template_directory_uri()."/images/spacer.png' style='background-image: url(".get_thumbnail_src($post->ID, 'large').")'>
						</div>
						<div class='list_right'>
							<h3 class='fontm'>".get_the_title()."</h3>
							<p class='fontss pc'>".mb_substr(strip_tags(strip_shortcodes($post->post_content)), 0, 120, 'UTF-8')."</p>
						</div>
						<div class='clear'></div>
					</div>
				</a>";
}
            yutopro_pagenavi();
            ?>
			</div><!-- content_left -->

			<div class="clear sp"></div>

			<div class="content_right">
				<?php get_sidebar(); ?>
			</div><!-- content_right -->
			<div class="clear"></div>

		</div><!-- area -->
	</div><!-- container -->
	<?php get_footer();?>
</body>

</html>