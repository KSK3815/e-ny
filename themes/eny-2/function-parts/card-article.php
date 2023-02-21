<?php  
  $home_url = home_url();
  // $cat = get_the_category();
  $this_card_cat = get_lowest_terms();
?>
<div class="related-article">
  <a class="thumbnail" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
    <?php if ( $thumbnail =  get_the_post_thumbnail( $post->ID ) ) :
      echo $thumbnail;
    else : ?>
      <img src="<?php echo get_bloginfo('stylesheet_directory').'/assets/default.png' ?>" alt="">
    <?php endif ?>
  </a>
  <div class="article-details">
    <a class="category" href="<?php echo $home_url . '/categories/' . end($this_card_cat)->term_id; ?>">
      <p><?php echo end($this_card_cat)->name; ?></p>
    </a>
    <a href="<?php the_permalink() ?>" rel="bookmark">
      <h2><?php the_title(); ?></h2>
    </a>
    <p class="summary">
      <?php 
        $remove_array = ["\r\n", "\r", "\n", " ", "　"];
        $content = wp_trim_words(strip_shortcodes(get_post($post->ID)->post_content), 66, '…' );
        $content = str_replace($remove_array, '', $content);
        echo $content;
      ?>
    </p>
  </div>
</div>