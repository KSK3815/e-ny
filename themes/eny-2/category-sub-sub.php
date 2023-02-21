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


      <section class="cat-archive__articles">
        <?php
          $product_limit = 10;
          $args = array(
              'post_type' => 'eny_product',
              'term_id' => $cg_tree[0]['cr'][0]['cr'][0]['id']
          );
          $count = count_posts_in_term($args);
          if ($count > 0) :
              $args = array(
                  'term_id' => $cg_tree[0]['cr'][0]['cr'][0]['id'],
                  'post_type' => 'eny_product',
                  'limit' => $product_limit,
              );
              $results = get_posts_in_term($args);
          ?>
        <h1 class="cat-archive__articles__title">
          <?php echo $cg_tree[0]['cr'][0]['cr'][0]['name']; ?>の商品一覧
          <span>
            <?php echo "(" . $count . "件)"; ?>
          </span>
        </h1>

        <ul class="cat-archive__articles__lists row">
          <?php foreach ($results as $result) : $item = $result; ?>
          <li class="col-lg-6">
            <?php include 'template-parts/card-item.php'; ?>
          </li>
          <?php endforeach ?>
        </ul>

        <?php if ($count > $product_limit) : ?>
        <a class="cat-archive__articles__button" href="<?php echo $home_url . '/categories/' . $cg_tree[0]['cr'][0]['cr'][0]['id'] . '/products/' ?>">
          <button class="button button--solid" type="button">
            <p>もっと見る</p>
          </button>
        </a>
        <?php endif; ?>

        <?php else : ?>
        <h1 class="cat-archive__articles__title">
          <?php echo $cg_tree[0]['cr'][0]['cr'][0]['name']; ?>の商品一覧
          <span>
            (0件)
          </span>
        </h1>
        <div class="cat-archive__articles__lists">
          <p>只今、商品がございません。</p>
        </div>
        <?php endif; ?>
      </section>


      <section class="cat-archive__articles">
        <?php if (have_posts()) : ?>
        <h1 class="cat-archive__articles__title">
          <?php echo get_category($cat)->name; ?>の記事一覧
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

        <?php if ($myCount > get_option('posts_per_page')) : ?>
        <a class="cat-archive__articles__button" href="<?php echo $home_url . '/categories/' . $cg_tree[0]['cr'][0]['cr'][0]['id'] . '/articles' ?>">
          <button class="button button--solid" type="button">
            <p>もっと見る</p>
          </button>
        </a>
        <?php endif; ?>

        <?php else : ?>
        <h1 class="cat-archive__articles__title">
          <?php echo $cg_tree[0]['cr'][0]['cr'][0]['name']; ?>の記事一覧
          <span>
            (0件)
          </span>
        </h1>
        <div class="cat-archive__articles__lists">
          <p>只今、記事がございません。</p>
        </div>
        <?php endif; ?>
      </section>

    </div>
  </div>

</main><!-- #main -->

<?php
get_footer();
