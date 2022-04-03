<?php
$current_tags = get_the_tags();
if ( $current_tags ) :
        foreach ( $current_tags as $tag ) {
            $current_tag_list[] = $tag->term_id;
        }

        $args = array(
            'tag__in'        => $current_tag_list,
            'post__not_in'   => array( $post->ID ),
            'posts_per_page' => 5,
        );
        $related_posts = new WP_Query( $args );

        if ( $related_posts->have_posts() ) :
?>
<h2 class="bar article">関連記事</h2>
<?php while ($related_posts -> have_posts()) : $related_posts -> the_post(); ?>
<?php
	$description = mb_substr(strip_tags(strip_shortcodes( get_the_content() )), 0, 120, 'UTF-8');
?>
<a href='<?php the_permalink(); ?>' class='block hover' title='<?php the_title_attribute(); ?>'>
	<div class='list_area'>
		<div class='list_left'>
			<img class='cover' src='<?php echo get_template_directory_uri(); ?>/images/spacer.png' style='background-image: url(<?php the_post_thumbnail_url(); ?>)'>
		</div>
		<div class='list_right'>
			<h3 class='fontl'><?php the_title(); ?></h3>
			<p class='fontss pc'><?php echo $description; ?></p>
		</div>
		<div class='clear'></div>
	</div>
</a>
<?php endwhile; ?>
<?php
endif;
wp_reset_postdata();
endif;
?>