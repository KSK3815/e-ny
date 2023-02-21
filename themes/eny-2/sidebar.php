<?php
$sql = "
    SELECT
      p.*
    FROM
      {$wpdb->prefix}most_popular mp
      INNER JOIN {$wpdb->prefix}posts p ON mp.post_id = p.ID
    WHERE
      p.post_type = 'post' AND
      p.post_status = 'publish'
    ORDER BY mp.7_day_stats IS NULL ASC, mp.7_day_stats DESC, p.post_date_gmt DESC
    LIMIT 10
  ";
$posts = $wpdb->get_results($sql);
$home_url = home_url();
global $post;
?>

<h1 class="pop-article-ttl">人気記事</h1>

<?php
if (count($posts) > 0) :
    for ($i = 0; $i < 7; $i++) :
        $post = $posts[$i];
        setup_postdata($post);
        $categories = get_the_category(get_the_ID());
        $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_in_this_term[0]->ID), array(100, 100)) ?: array(get_template_directory_uri().'/assets/default.png');
        ?>

      <article class="pop-article">
        <a href="<?php the_permalink($id); ?>" class="pop-article__img">
          <img src="<?php echo $thumbnail[0] ?>" alt="">

            <?php if ($i < 3) : ?>
              <div class="pop-article__img__rank pop-article__img__rank--top">
                  <?php echo $i + 1 ?>
              </div>
            <?php else : ?>
              <div class="pop-article__img__rank">
                  <?php echo $i + 1 ?>
              </div>
            <?php endif; ?>
        </a>

        <div class="pop-article__content">
          <a class="pop-article__content__ttl" href="<?php the_permalink($id); ?>">
            <h1><?php echo get_the_title() ?>
            </h1>
          </a>
          <a class="pop-article__content__cat" href="<?php echo $home_url . '/categories/' . $categories[0]->term_id ?>">
              <?php echo $categories[0]->name ?>
          </a>
        </div>
      </article>

    <?php
    endfor;
    wp_reset_postdata();
endif;
?>

<a class="u-text-decoration-none" href="<?php echo $home_url . '/articles'; ?>">
  <button class="button button--secondary" type="button">
    <p>もっと見る</p>
  </button>
</a>