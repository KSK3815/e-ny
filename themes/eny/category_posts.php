<?php

foreach (get_the_category() as $value) {
	$cateid = $value->cat_ID;
	$catename = $value->cat_name;
}
// 中カテゴリー(人気記事x10)
$cate_m_posts = [];
$exclude_m = $post->ID;

if ( !empty($cateid) ) {
	$and_tax = " and wp_term_relationships.term_taxonomy_id = ".$cateid;
} else {
	$and_tax = "";
}
if ( isset($cateid) ) {
	$cate_m_args = [
		'post_type' => 'post',
		'orderby' => 'rand',
		'cat' => $cateid,
		'numberposts' => 10,
		'exclude' => $post->ID
	];
	$cate_m_posts = $wpdb->get_results( "
				SELECT {$wpdb->prefix}popularpostsdata.pageviews,{$wpdb->prefix}popularpostsdata.postid,wp_posts.post_title,wp_posts.post_content,wp_posts.ID,wp_term_relationships.object_id,wp_term_relationships.term_taxonomy_id
				FROM {$wpdb->prefix}popularpostsdata
				left join wp_posts on {$wpdb->prefix}popularpostsdata.postid = wp_posts.ID
				left join wp_term_relationships on wp_posts.ID = wp_term_relationships.object_id
				WHERE wp_posts.post_status = 'publish'
				and wp_posts.post_type = 'post'{$and_tax}
				and wp_posts.ID != {$post->ID}
				ORDER BY  {$wpdb->prefix}popularpostsdata.pageviews DESC
				limit 10" );
	foreach ($cate_m_posts as $value) {
		$exclude_m .= ','.$value->ID;
	}
}

?>
<?php
if(count($cate_m_posts)>0 ):
?>
<h2 class="bar article"><b><?php echo $catename; ?></b>の人気記事</h2>
<?php
foreach($cate_m_posts as $post) :
	$title = $post->post_title;
	$link = get_the_permalink($post->ID);
	$img = get_thumbnail_src($post->ID, 'large');
	$description = mb_substr(strip_tags(strip_shortcodes( $post->post_content )), 0, 120, 'UTF-8');
?>
<a href='<?php the_permalink(); ?>' class='block hover' title='<?php the_title_attribute(); ?>'>
	<div class='list_area'>
		<div class='list_left'>
			<img class='cover' src='<?php echo get_template_directory_uri(); ?>/images/spacer_sq.png' style='background-image: url(<?php the_post_thumbnail_url(); ?>)'>
		</div>
		<div class='list_right'>
			<h3 class='fontl'><?php the_title(); ?></h3>
			<p class='fontss pc'><?php echo $description; ?></p>
		</div>
		<div class='clear'></div>
	</div>
</a>
<?php endforeach; ?>
<?php
wp_reset_postdata();
endif;
?>