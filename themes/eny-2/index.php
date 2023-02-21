<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eny_Media
 */

get_header();

$home_url = home_url();

$cg_tree = get_category_tree();
?>

<div class="first-view">
  <div class="container">
    <img class="first-view__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo-light.svg" alt="">
    <div class="first-view__contents">
      <h1 class="first-view__contents__copy">
        暮らしに寄り添った<span></span>モノ選びメディア
      </h1>

      <div class="first-view__contents__search">
        <form id="index-search" method="get" class="" action="<?php echo home_url('/') . 'search'; ?>">
        <input id="index-search-input" name="q" type="text" placeholder="商品を探す" value="<?php echo htmlspecialchars($q); ?>">
        <input id="index-search-submit" type="submit" value="検索">
        </form>
      </div>
    </div>
  </div>
</div>

<?php
$args = array(
  'posts_per_page' => 5,
  'meta_key' => 'meta-checkbox',
  'meta_value' => 'yes',
);
$featured = new WP_Query($args);
?>
<div class="features">
  <?php while ($featured->have_posts()): $featured->the_post();?>
    <div class="featured-article">
      <a href="<?php the_permalink();?>">
      <?php if ($thumbnail = get_the_post_thumbnail($post->ID)):
        echo $thumbnail;
    else: ?>
    <img src="<?php echo get_bloginfo('stylesheet_directory') . '/assets/default.png' ?>" alt="">
    <?php endif?>
    </a>
    <?php $lowest_terms = get_lowest_terms(get_the_ID(), 'category');?>
    <a class="cat-link" href="<?php echo $home_url . '/categories/' . $lowest_terms[0]->term_id ?>">
      <p><?php echo $lowest_terms[0]->name; ?></p>
    </a>
    <h3>
      <a class="title-link" href="<?php the_permalink();?>">
        <?php the_title();?>
      </a>
    </h3>
  </div>
  <?php endwhile;?>
</div>



<div class="container">
	<div class="row flex-column-reverse flex-lg-row">
    <section class="col-lg-8 cat-summary">
      <h1 class="cat-summary__title">カテゴリー一覧</h1>

      <?php foreach ($cg_tree as $first): ?>
      <section class="cat-summary__cat">
        <div class="cat-summary__cat__head">
          <h1><?php echo $first['name'] ?></h1>
          <a href="<?php echo $home_url . '/categories/' . $first['id'] ?>">
            <?php echo $first['name'] ?>ページへ
            <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>

        <ul class="cat-summary__cat__lists">
          <div>
            <?php if ($first['cr']): foreach ($first['cr'] as $second): ?>
            <li>
              <a href="<?php echo $home_url . '/categories/' . $second['id'] ?>">
                <?php
                $item = $second;
                include 'template-parts/card-category-term.php';
                ?>
              </a>
            </li>
			<?php endforeach;endif;?>
			<span class="cat-summary__cat__lists__spacer"></span>
          </div>
        </ul>
      </section>
      <?php endforeach;?>

    </section>

    <section class="col-lg-4 pop-articles">
      <?php get_sidebar();?>
    </section>
	</div>
</div>

<?php
get_footer();