<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Eny_Media
 */
get_header();
$home_url = home_url();
global $pages;
if (empty($pages)) {
    $pages = array();
}
$q = get_query_var("q");
$my_cat_array = array();
$my_cats = get_categories();
$cg_tree[0] = array(
    'name' => "「{$q}」の検索結果"
);
$current_hierarchy = 0;
$esc_q = $wpdb->esc_like($q);
$query = <<<SQL
  SELECT DISTINCT t.term_id, t.name
  FROM
    {$wpdb->terms} AS t
		RIGHT OUTER JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id
  WHERE
    t.name like '%{$esc_q}%'
		AND tt.taxonomy = 'category'
SQL;
$category_results = $wpdb->get_results($query);
$category_results_array = array();
for ($i = 0; $i < count($category_results); $i++) {
    array_push($category_results_array, array(
        'name' => $category_results[$i]->name,
        'id' => $category_results[$i]->term_id
    ));
}
$product_limit = 9;
$article_limit = 8;
$query = <<<SQL
SELECT DISTINCT p.ID, p.post_title
FROM {$wpdb->posts} AS p
WHERE
	p.post_status = 'publish'
	AND p.post_type = 'eny_product'
	AND (
		p.post_title like '%{$esc_q}%'
		OR p.post_content like '%{$esc_q}%'
	)
ORDER BY p.post_date DESC
LIMIT 0, {$product_limit}
SQL;
$product_results = $wpdb->get_results($query);
$query = <<<SQL
SELECT count(DISTINCT p.ID)
FROM {$wpdb->posts} AS p
WHERE
	p.post_status = 'publish'
	AND p.post_type = 'eny_product'
	AND (
		p.post_title like '%{$esc_q}%'
		OR p.post_content like '%{$esc_q}%'
	)
SQL;
$product_count = $wpdb->get_results($query);
$key = "count(DISTINCT p.ID)";
$product_count = $product_count[0]->$key;
$query = <<<SQL
SELECT DISTINCT p.ID, p.post_title
FROM {$wpdb->posts} AS p
WHERE
	p.post_status = 'publish'
	AND p.post_type = 'post'
	AND (
		p.post_title like '%{$esc_q}%'
		OR p.post_content like '%{$esc_q}%'
	)
ORDER BY p.post_date DESC
LIMIT 0, {$article_limit}
SQL;
$article_results = $wpdb->get_results($query);
$query = <<<SQL
SELECT count(DISTINCT p.ID)
FROM {$wpdb->posts} AS p
WHERE
	p.post_status = 'publish'
	AND p.post_type = 'post'
	AND (
		p.post_title like '%{$esc_q}%'
		OR p.post_content like '%{$esc_q}%'
	)
SQL;
$article_count = $wpdb->get_results($query);
$key = "count(DISTINCT p.ID)";
$article_count = $article_count[0]->$key;
?>

<main class="container cat-archive">
  <?php include 'template-parts/breadcrumbs.php' ?>

  <?php if ((count($category_results) > 0) || $product_count || $article_count) : ?>

  <?php if (count($category_results) > 0) : ?>
  <section class="cat-archive__cats">
    <h1 class="cat-archive__cats__title">
      「<?php echo $q ?>」に関するカテゴリー
    </h1>

    <ul class="cat-summary__cat__lists">
      <div>
        <?php foreach ($category_results_array as $item) : ?>
        <li>
          <a class="link" href="<?php echo $home_url . '/categories/' . $item['id'] ?>">
            <?php include 'template-parts/card-category-term.php'; ?>
          </a>
        </li>
        <?php endforeach; ?>
      </div>
      <span class="cat-summary__cat__lists__spacer"></span>
    </ul>
  </section>
  <?php endif; ?>

  <section class="cat-archive__articles">

    <?php if ($product_count) : ?>
    <div class="u-margin-bottom-lg">
      <h1 class="cat-archive__articles__title">
        「<?php echo $q ?>」に関する商品
        <span>(<?php echo $product_count ?>件)</span>
      </h1>

      <ul class="cat-archive__articles__lists row">
        <?php foreach ($product_results as $item) : ?>
        <li class="col-lg-6">
          <?php include 'template-parts/card-item.php'; ?>
        </li>
        <?php endforeach ?>
      </ul>

      <?php if ($product_count > $product_limit) : ?>
      <a class="cat-archive__articles__button u-margin-top-none" href="<?php echo $home_url . '/search?q=' . $q . '&post_type=eny_product' ?>">
        <button class="button button--solid" type="button">
          <p>もっと見る</p>
        </button>
      </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($article_count) : ?>
    <div class="u-margin-bottom-lg">
      <h1 class="cat-archive__articles__title">
        「<?php echo $q ?>」に関する記事
        <span>(<?php echo $article_count ?>件)</span>
      </h1>

      <ul class="cat-archive__articles__lists row">
        <?php if (count($article_results) > 0) : global $post; ?>
        <?php for ($i = 0; $i < count($article_results); $i++) :
          $post = $article_results[$i]
        ?>
        <li class="col-lg-6">
          <?php include 'template-parts/card-article.php' ?>
        </li>
        <?php endfor; ?>
        <?php endif; ?>
      </ul>

      <?php if ($article_count > $article_limit) : ?>
      <a class="cat-archive__articles__button u-margin-top-none" href="<?php echo $home_url . '/search?q=' . $q . '&post_type=post' ?>">
        <button class="button button--solid" type="button">
          <p>もっと見る</p>
        </button>
      </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </section>

  <?php else : ?>
  <div class="art-container">
    <div class="display-header">
      「<?php echo $q ?>」に関する結果が見つかりませんでした。
    </div>
  </div>
  <?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
