<?php
$userID = get_query_var('meta_key');

$member = get_user_by('id', $userID);

if (!$member->data) {
    exit(header("Location: ".home_url()));
}


 ?>
<?php get_header();?>

<body id="content">
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WQ7JKXS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<div id="container">
		<div class="area">
			<?php include_once("inc_header.php"); ?>
			<div id="main" class="content_left" style="padding-left:0px; padding-right:0px;">

				<div class="author-area">
					<div class="author-img">
						<?php echo get_avatar($userID); ?>
					</div>
					<div class="author-detail">
						<p class="author-name"><?php echo get_the_author_meta('display_name', $userID); ?>
						</p>
						<p><?php echo get_the_author_meta('description', $userID); ?>
						</p>
					</div>
				</div>


				<div class="heading">
					<div class="headtitle">
						<?php echo get_the_author_meta('display_name', $userID); ?>の監修記事
					</div>
				</div>
				<?php
                $myposts = get_posts('author='.$userID.'&order=DESC&numberposts=40&paged='.$paged);
                foreach ($myposts as $post) :
                  $title = $post->post_title;
                  $link = get_the_permalink($post->ID);
                  $img = get_thumbnail_src($post->ID, 'large');
                  $cat = get_the_category();
                  $term_order_arr = [];
                  foreach ($cat as $cat_value) {
                      $term_order_arr[] = $cat_value->term_order;
                  }
                  array_multisort($term_order_arr, SORT_NUMERIC, $cat);
                  $cat_name = $cat[0]->cat_name;
                  $description = mb_substr(strip_tags(strip_shortcodes($post->post_content)), 0, 120, 'UTF-8');
                ?>
				<a href='<?php echo $link; ?>' class='block hover'>
					<div class='list_area'>
						<div class='list_left'>
							<img class='cover' src='<?php echo get_template_directory_uri();?>/images/spacer_sq.png' style='background-image: url(<?php echo $img; ?>)'>
							<span class='fontss'><?php echo $cat_name; ?></span>
						</div>
						<div class='list_right'>
							<h3 class='fontl'><?php echo $title; ?>
							</h3>
							<?php if (!wp_is_mobile()): ?>
							<p class='fonts'><?php echo $description; ?>....</p>
							<?php endif; ?>
						</div>
						<div class='clear'></div>
					</div>
				</a>
				<?php endforeach; ?>




			</div>
			<?php include("related.php"); ?>
			<div class="content_right">
				<?php get_sidebar(); ?>
			</div><!-- content_right -->

			<div class="clear"></div>

		</div><!-- area -->
	</div><!-- container -->
	<?php get_footer();?>
</body>

</html>