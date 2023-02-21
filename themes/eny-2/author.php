<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eny_Media
 */

get_header();

$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
$id = $curauth->ID;

$query = <<<SQL
    SELECT DISTINCT count(pm.meta_key)
    FROM
        {$wpdb->postmeta} AS pm
        RIGHT OUTER JOIN {$wpdb->posts} AS p ON pm.post_id = p.ID
    WHERE
        meta_key IN ( 'dw_reaction_love', 'dw_reaction_like', 'dw_reaction_haha', 'dw_reaction_wow', 'dw_reaction_sad', 'dw_reaction_angry' )
        AND p.post_author = {$id}
SQL;
$results = $wpdb->get_results($query);
$key = "count(pm.meta_key)";
$reaction_count = $results[0]->$key;

$home_url = home_url();

$posts_per_page = get_option('posts_per_page');
$paged = get_query_var('paged') ?: 1;
$all_posts = $wp_query->found_posts;
$total_page = ceil($all_posts / $posts_per_page);
?>

<main class="container">

  <div class="breadcrumbs">
    <a class="breadcrumbs__link" href="<?php echo $home_url ?>">TOP</a>
    <span class="breadcrumbs__divider">
      <i class="fas fa-angle-right"></i>
    </span>
    <a class="breadcrumbs__link" href="<?php echo $home_url ?>/authors/">ライター一覧</a>
    <span class="breadcrumbs__divider">
      <i class="fas fa-angle-right"></i>
    </span>
    <span class="breadcrumbs__link current"><?php echo get_the_author_meta('display_name', $id); ?></span>
  </div>

  <section class="author-profile">
    <div class="author-profile__img">
      <?php echo get_avatar($curauth->user_email, 256); ?>
      <?php if (in_array("公式ライター", get_field('eny_user_label', "user_{$id}") ? : array())) : ?>
      <img class="author-profile__img__badge" src="<?php echo get_template_directory_uri(); ?>/assets/badge-authorized.png" alt="">
      <?php endif; ?>
    </div>

    <p class="author-profile__position">
      <?php echo get_the_author_meta('position', $id); ?>
    </p>

    <h1 class="author-profile__name">
      <?php echo get_the_author_meta('display_name', $id); ?>
    </h1>

    <div class="author-profile__reactions">
      <img class="badge-auth" src="<?php echo get_template_directory_uri(); ?>/assets/reactions.svg" alt="">
      <p><?php echo $reaction_count ?>人が記事にリアクションしています</p>
    </div>

    <p class="author-profile__intro"><?php echo $curauth->user_description; ?>
    </p>

    <div class="author-profile__sns">
      <?php if (!empty(get_the_author_meta('twitter', $id))) : ?>
      <a href="https://twitter.com/<?php echo get_the_author_meta('twitter', $id); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/twitter.png" alt=""></a>
      <?php endif; ?>
      <?php if (!empty(get_the_author_meta('facebook', $id))) : ?>
      <a href="https://www.facebook.com/<?php echo get_the_author_meta('facebook', $id); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/facebook.png" alt=""></a>
      <?php endif; ?>
      <?php if (!empty(get_the_author_meta('instagram', $id))) : ?>
      <a href="https://www.instagram.com/<?php echo get_the_author_meta('instagram', $id); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/instagram.png" alt=""></a>
      <?php endif; ?>
      <?php if (!empty(get_the_author_meta('blog', $id))) : ?>
      <a href="<?php echo get_the_author_meta('blog', $id); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/blog.png" alt=""></a>
      <?php endif; ?>
    </div>
  </section>

  <section>
    <h1 class="u-page-title">
      執筆記事
      <span>
        (
        <?php
          $myCount = $wp_query->found_posts;
          echo $myCount
        ?>
        件)
      </span>
    </h1>

    <div class="row">
      <?php while (have_posts()) : the_post(); ?>
      <div class="col-lg-6 u-margin-bottom-md">
        <?php include 'template-parts/card-article.php' ?>
      </div>
      <?php endwhile; ?>
    </div>

    <?php if ($all_posts > $posts_per_page) : ?>
    <div class="nav-links">
      <?php
          echo paginate_links(array(
            'base' => $home_url . '/author/' . $author_name . '%_%',
            'format' => '/page/%#%/',
            'current' => $paged,
            'total' => $total_page,
            'prev_next'    => false,
            'mid_size'  => 1,
          ));
        ?>
    </div>
    <?php endif; ?>
  </section>

</main><!-- #main -->


<?php
get_footer();
