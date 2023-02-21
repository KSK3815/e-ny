<?php
  $home_url = home_url();
  $cat = get_the_category();
?>
<div class="related-article">
  <a class="related-article__thumbnail" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
    <?php if ($thumbnail =  get_the_post_thumbnail($post->ID)) :
      echo $thumbnail;
    else : ?>
    <img src="<?php echo get_bloginfo('stylesheet_directory').'/assets/default.png' ?>" alt="">
    <?php endif ?>
  </a>
  <div class="related-article__details">
    <a class="related-article__details__title" href="<?php the_permalink() ?>" rel="bookmark">
      <h2><?php the_title(); ?>
      </h2>
    </a>
    <a class="related-article__details__category" href="<?php echo $home_url . '/categories/' . $cat[0]->term_id; ?>">
      <p><?php echo $cat[0]->name; ?>
      </p>
    </a>
    <p class="related-article__details__summary">
      <?php
        $remove_array = ["\r\n", "\r", "\n", " ", "　"];
        $content = wp_trim_words(strip_shortcodes(get_post($post->ID)->post_content), 56, '……');
        $content = str_replace($remove_array, '', $content);
        echo $content;
      ?>
    </p>
  </div>
</div>
