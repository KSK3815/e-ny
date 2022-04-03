<div class="popular">
  <h2 class="bar article">人気記事一覧</h2>

  <?php
  if(is_single() || is_category() ): {
    while ( have_posts() ) : the_post();
    $data = $wpdb->get_results( "
      SELECT {$wpdb->prefix}popularpostsdata.pageviews,{$wpdb->prefix}popularpostsdata.postid,wp_posts.post_title,wp_posts.ID,wp_term_relationships.object_id,wp_term_relationships.term_taxonomy_id
      FROM {$wpdb->prefix}popularpostsdata
      left join wp_posts on {$wpdb->prefix}popularpostsdata.postid = wp_posts.ID
      left join wp_term_relationships on wp_posts.ID = wp_term_relationships.object_id
      WHERE wp_posts.post_status = 'publish'
      and wp_posts.post_type = 'post'
      and wp_term_relationships.term_taxonomy_id = ".get_the_category()[0]->cat_ID."
      ORDER BY  {$wpdb->prefix}popularpostsdata.pageviews DESC
      limit 5" );
    endwhile;
    foreach ($data as $value) {
      ?>
      <a href="<?php echo home_url();?>/<?php echo $value->ID;?>" class='block hover'>
        <div class='list_area'>
          <div class='list_left'>
            <img src="<?php echo get_template_directory_uri(); ?>/images/spacer_pickup.png?<?php echo date("YmdHis", filemtime(dirname(__FILE__).'/images/spacer_pickup.png')) ?>" style="background-image: url(<?php echo get_thumbnail_src($value->ID, 'large');?>)" class="cover">
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
    limit 5" );
   foreach ($data as $value) {
    ?>
    <a href="<?php echo home_url();?>/<?php echo $value->ID;?>" class='block hover'>
      <div class='list_area'>
        <div class='list_left'>
            <img src="<?php echo get_template_directory_uri(); ?>/images/spacer_pickup.png?<?php echo date("YmdHis", filemtime(dirname(__FILE__).'/images/spacer_pickup.png')) ?>" style="background-image: url(<?php echo get_thumbnail_src($value->ID, 'large');?>)" class="cover">
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