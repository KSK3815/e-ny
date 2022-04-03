<div class="heading">
  <div class="headtitle">
    新着記事
  </div>
</div>
<?php
$myposts = get_posts('order=DESC&numberposts='.get_option('posts_per_page').'&paged='.$paged);
foreach($myposts as $post) {
  $title = $post->post_title;
  $link = get_the_permalink($post->ID);
  $img = get_thumbnail_src($post->ID, 'large');
  $cat = get_the_category();
  $term_order_arr = [];
  foreach( $cat as $cat_value ) {
    $term_order_arr[] = $cat_value->term_order;
  }
  array_multisort($term_order_arr, SORT_NUMERIC, $cat);
  $cat_name = $cat[0]->cat_name;
  $description = mb_substr(strip_tags(strip_shortcodes( $post->post_content )), 0, 120, 'UTF-8');
?>
  <a href='<?php echo $link; ?>' class='block hover'>
    <div class='list_area'>
      <div class='list_left'>
        <img class='cover' src='<?php echo get_template_directory_uri();?>/images/spacer_sq.png' style='background-image: url(<?php echo $img; ?>)'>
        <span class='fontss'><?php echo $cat_name; ?></span>
      </div>
      <div class='list_right'>
        <h3 class='fontl'><?php echo $title; ?></h3>
        <p class='fonts'><?php echo $description; ?>....</p>
      </div>
      <div class='clear'></div>
    </div>
  </a>
<?php
}
yutopro_pagenavi();
?>

