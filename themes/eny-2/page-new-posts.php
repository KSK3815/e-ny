<?php
/*
Template Name: new-posts
*/
?>

<?php

get_header();

// ① ↓ 今現在のページ位置を取得
$paged = (int) get_query_var('paged');

$args = array(
  'posts_per_page' => 24,
  'paged' => $paged,
  'orderby' => 'post_date',
  'order' => 'DESC',
  'post_type' => 'post',
  'post_status' => 'publish'
);
$the_query = new WP_Query($args);

$page_arg = array(
  'current' => max(1, $paged),
  'total' => $the_query->max_num_pages,
);

?>

<main class="container">

  <div class="breadcrumbs">
    <a class="breadcrumbs__link" href="<?php echo $home_url ?>">TOP</a>
    <span class="breadcrumbs__divider">
      <i class="fas fa-angle-right"></i>
    </span>
    <span class="breadcrumbs__link current">新着記事一覧</span>
  </div>

  <h1 class="u-page-title">
    新着記事一覧
  </h1>

  <ul class="row u-list-style-none">

    <?php
    if ($the_query->have_posts()) :
      while ($the_query->have_posts()) : $the_query->the_post();
        ?>
        <li class="col-lg-6 u-margin-bottom-md">
          <?php include 'template-parts/card-article.php' ?>
        </li>
    <?php
      endwhile;
    endif;
    ?>

  </ul>

  <div class="nav-links">
    <?php echo paginate_links($page_arg); ?>
  </div>
</main>

<?php
wp_reset_postdata();

get_footer();
