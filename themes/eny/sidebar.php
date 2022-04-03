<div id="side">
	<div class="popular">
		<h2 class="bar article">人気記事一覧</h2>

		<?php
		if(is_single() || is_category() ): {
			// カテゴリー誤取得修正20180322 seki
			if ( !$term_taxonomy_id = get_category( get_query_var( 'cat' ), false )->cat_ID ) {
				$term_taxonomy_id = get_the_category()[0]->cat_ID;
			}
			$data = $wpdb->get_results( "
				SELECT {$wpdb->prefix}popularpostsdata.pageviews,{$wpdb->prefix}popularpostsdata.postid,wp_posts.post_title,wp_posts.ID,wp_term_relationships.object_id,wp_term_relationships.term_taxonomy_id
				FROM {$wpdb->prefix}popularpostsdata
				left join wp_posts on {$wpdb->prefix}popularpostsdata.postid = wp_posts.ID
				left join wp_term_relationships on wp_posts.ID = wp_term_relationships.object_id
				WHERE wp_posts.post_status = 'publish'
				and wp_posts.post_type = 'post'
				and wp_term_relationships.term_taxonomy_id = ".$term_taxonomy_id."
				ORDER BY  {$wpdb->prefix}popularpostsdata.pageviews DESC
				limit 10" );
			foreach ($data as $value) {
				?>
				<a href="<?php echo home_url();?>/<?php echo $value->ID;?>" class='block hover'>
					<div class='list_area'>
						<div class='list_left'>
							<img src="<?php echo get_template_directory_uri(); ?>/images/spacer_pickup_sq.png?<?php echo date("YmdHis", filemtime(dirname(__FILE__).'/images/spacer_pickup_sq.png')) ?>" style="background-image: url(<?php echo get_thumbnail_src($value->ID, 'large');?>)" class="cover">
						</div>
						<div class='list_right'>
							<h3 class="fonts hover"><?php echo $value->post_title;?></h3>
						</div>
						<div class='clear'></div>
					</div>
				</a>

				<?php
			}
		}else: {
			$data = $wpdb->get_results( "
				SELECT {$wpdb->prefix}popularpostsdata.pageviews,{$wpdb->prefix}popularpostsdata.postid,wp_posts.post_title,wp_posts.ID
				FROM {$wpdb->prefix}popularpostsdata
				left join wp_posts on {$wpdb->prefix}popularpostsdata.postid = wp_posts.ID
				WHERE wp_posts.post_status = 'publish'
				and wp_posts.post_type = 'post'
				ORDER BY  {$wpdb->prefix}popularpostsdata.pageviews DESC
				limit 10" );
			foreach ($data as $value) {
				?>
				<a href="<?php echo home_url();?>/<?php echo $value->ID;?>" class='block hover'>
					<div class='list_area'>
						<div class='list_left'>
							<img src="<?php echo get_template_directory_uri(); ?>/images/spacer_pickup_sq.png?<?php echo date("YmdHis", filemtime(dirname(__FILE__).'/images/spacer_pickup_sq.png')) ?>" style="background-image: url(<?php echo get_thumbnail_src($value->ID, 'large');?>)" class="cover">
						</div>
						<div class='list_right'>
							<h3 class="fonts hover"><?php echo $value->post_title;?></h3>
						</div>
						<div class='clear'></div>
					</div>
				</a>
				<?php
			}
		}
		endif; ?>

  </div>
</div>
<?php $args = array(
        'posts_per_page'   => 5,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'meta_key' => 'pharmacist',
        'meta_value' => 1,
          );
$pharmacist_posts = new WP_Query($args);
if ($pharmacist_posts->have_posts()) : while ($pharmacist_posts->have_posts()) : $pharmacist_posts->the_post();
?>
<h2 class="bar article">おすすめ記事</h2>
<a href='<?php the_permalink(); ?>' class='block hover'>
	<div class='list_area' style='font-size:13px'>
		<div class='list_left' style='width:40%;'>
			<img class='cover' src='<?php echo get_template_directory_uri(); ?>/images/spacer.png' style='background-image: url("<?php the_post_thumbnail_url(); ?>")'>
		</div>
		<div class='list_right' style='width:57%;'>
			<h3 class='fonts hover'><?php the_title(); ?></h3>
		</div>
		<div class='clear'></div>
	</div>
</a>
<?php endwhile; endif; wp_reset_postdata(); ?>
