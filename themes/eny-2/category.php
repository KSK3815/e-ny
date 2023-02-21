<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eny_Media
 */


get_header();

$home_url = home_url();

$args = array(
  'term_id' => $cat,
  'taxonomy' => 'category'
);
$current_hierarchy = get_current_hierarchy($args);
$cg_tree = get_category_tree($cat);
?>

<main class="container">

  <?php include 'template-parts/breadcrumbs.php' ?>
  <div class="row cat-archive flex-column-reverse flex-lg-row">

    <div class="col-lg-3">
      <?php include 'template-parts/navigation.php' ?>
    </div>

    <div class="col-lg-9">

      <?php if ($cg_tree[0]['cr']) : ?>
      <section class="cat-archive__cats">
        <h1 class="cat-archive__cats__title">
          <?php echo $cg_tree[0]['name']; ?>
        </h1>

        <ul class="cat-summary__cat__lists">
          <div>
            <?php foreach ($cg_tree[0]['cr'] as $second) : ?>
            <li>
              <a href="<?php echo $home_url . '/categories/' . $second['id'] ?>">
                <?php
                $item = $second;
                include 'template-parts/card-category-term.php';
                ?>
              </a>
            </li>
            <?php endforeach; ?>
            <span class="cat-summary__cat__lists__spacer"></span>
          </div>
        </ul>
      </section>
      <?php endif; ?>

      <section class="cat-archive__articles">
        <?php if (have_posts()) : ?>
        <h1 class="cat-archive__articles__title">
          <?php echo $cg_tree[0]['name']; ?>の記事一覧
          <span>
            (<?php $myCount = $wp_query->found_posts;
                echo $myCount ?>件)
          </span>
        </h1>

        <ul class="cat-archive__articles__lists">
          <?php while (have_posts()) : the_post(); ?>
          <li>
            <?php include 'template-parts/card-article.php' ?>
          </li>
          <?php endwhile; ?>
        </ul>

        <?php if ($myCount > 8) : ?>
        <a class="cat-archive__articles__button" href="<?php echo $home_url . '/categories/' . $cg_tree[0]['id'] . '/articles' ?>">
          <button class="button button--solid" type="button">
            <p>もっと見る</p>
          </button>
        </a>
        <?php endif; ?>

        <?php else : ?>
        <p class="">
          <?php echo $cg_tree[0]['name']; ?>の記事一覧
          <span>
            (0件)
          </span>
        </p>
        <div class="">
          <p>只今、記事がございません。</p>
        </div>
        <?php endif; ?>
      </section>
    </div>
  </div>

</main><!-- #main -->

<?php
get_footer();
