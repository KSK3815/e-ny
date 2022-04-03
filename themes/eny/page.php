<?php get_header();?>

<body id="content">
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WQ7JKXS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<div id="container">
		<div class="area">
			<?php include_once("inc_header.php"); ?>


			<div id="main" class="content" style="padding-left:0px; padding-right:0px;">
				<?php while (have_posts()) : the_post(); ?>
				<div class="hentry" id="main_content">
					<div class="content_left_sub" id="main_sub">
						<h1 class="entry-title"><?php the_title(); ?>
						</h1>
						<?php the_content(); ?>
					</div>
				</div>
				<?php endwhile; ?>
			</div>


		</div><!-- area -->
	</div><!-- container -->
	<?php get_footer();?>
</body>

</html>