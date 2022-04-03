<?php
$members = get_users([
  'role'   => 'author',
  'orderby'=>'post_count',
  'order'  =>DESC,
  'count_total'=>true,
  'number'     => 6,
]);


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

				<div class="memberlists">
					<div class="heading">
						<div class="headtitle">
							専門家リスト
						</div>
					</div>
					<?php
                    foreach ($members as $member) :
                      $description = mb_substr(strip_tags(strip_shortcodes(get_the_author_meta('description', $member->data->ID))), 0, 50, 'UTF-8');
                    ?>
					<a href='<?php echo home_url();?>/experts/<?php echo $member->data->ID; ?>' class='block hover'>
						<div class='memberlist'>
							<div class="avatar">
								<?php echo get_avatar($member->data->ID); ?>
							</div>
							<div class="author-detail">
								<p class="author-name"><strong><?php echo get_the_author_meta('display_name', $member->data->ID); ?></strong></p>
								<p class="author-desc"><?php echo $description; ?>...</p>
							</div>
						</div>
						<i class="fa fa-angle-right"></i>
					</a>
					<?php endforeach; ?>
				</div>




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