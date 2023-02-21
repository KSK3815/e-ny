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
$current_cat = get_category($cat);
$term_hierarchy = get_ancestors(get_queried_object()->term_id, 'category');
$current_hierarchy = count($term_hierarchy);
$cg_tree = get_category_tree($cat);

$sort_param = $_GET['sort'] ?: "new";
$sort_query = "";
switch ($sort_param) {
  case 'new':
    $sort_query = "ORDER BY p.post_date DESC";
    break;
  case 'popular_dayly':
    $sort_query = "ORDER BY 1_day_stats IS NULL ASC, 1_day_stats DESC, post_date_gmt DESC";
    break;
  case 'popular_weekly':
    $sort_query = "ORDER BY 7_day_stats IS NULL ASC, 7_day_stats DESC, post_date_gmt DESC";
    break;
  case 'popular_monthly':
    $sort_query = "ORDER BY 30_day_stats IS NULL ASC, 30_day_stats DESC, post_date_gmt DESC";
    break;
  case 'popular_all_time':
    $sort_query = "ORDER BY all_time_stats IS NULL ASC, all_time_stats DESC, post_date_gmt DESC";
    break;
}

$page = $_GET['page'] ?: 1;
$limit = 30;
$offset = ($page - 1) * $limit;


// 全記事取得
$query = <<<SQL
  SELECT DISTINCT p.ID, p.post_title
  FROM
    {$wpdb->posts} AS p
    LEFT OUTER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
    LEFT OUTER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    LEFT OUTER JOIN {$wpdb->prefix}most_popular AS mp ON p.ID = mp.post_id
  WHERE
    p.post_status = 'publish' AND
    p.post_type = 'eny_product' AND
    tt.term_id = {$current_cat->term_id}
  {$sort_query}
SQL;
$current_category_posts = $wpdb->get_results($query);
$current_category_post_ids = implode(',', array_column($current_category_posts, "ID"));
$current_category_post_query = $current_category_post_ids ? "AND p.ID IN ({$current_category_post_ids})" : "";
// 全記事取得


$maker_params = $_GET['makers'] ?: array();
$maker_ids = implode(',', $maker_params);

if ($maker_ids) {

  // 適した記事取得
    $query = <<<SQL
  SELECT DISTINCT p.ID, p.post_title
  FROM
    {$wpdb->posts} AS p
    LEFT OUTER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
    LEFT OUTER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    LEFT OUTER JOIN {$wpdb->prefix}most_popular AS mp ON p.ID = mp.post_id
  WHERE
    tt.term_id IN ({$maker_ids})
    {$current_category_post_query}
  {$sort_query}
  LIMIT {$offset}, {$limit}
SQL;
    $queried_posts = $wpdb->get_results($query);
    $queried_post_ids = implode(',', array_column($queried_posts, "ID"));
    // 適した記事取得
    $results = $queried_posts;

    $query = <<<SQL
  SELECT count(DISTINCT p.ID)
  FROM
    {$wpdb->posts} AS p
    LEFT OUTER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
    LEFT OUTER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
  WHERE
    tt.term_id IN ({$maker_ids})
    {$current_category_post_query}
SQL;
    $queried_posts_count = $wpdb->get_results($query);
    $key = "count(DISTINCT p.ID)";
    $count = $queried_posts_count[0]->$key;
} else {
    $results = array_slice($current_category_posts, $offset, $limit);
    $count = count($current_category_posts);
}

$total_page = ceil($count / $limit);

// メーカー一覧取得
$query = <<<SQL
  SELECT DISTINCT t.name, t.term_id
  FROM
    {$wpdb->posts} AS p
    LEFT OUTER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
    LEFT OUTER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    LEFT OUTER JOIN {$wpdb->terms} AS t ON tt.term_id = t.term_id
  WHERE
    tt.taxonomy = 'eny_product_maker'
    {$current_category_post_query}
SQL;
$makers = $wpdb->get_results($query);
// メーカー一覧取得


?>

<main class="container">

  <?php include 'template-parts/breadcrumbs.php' ?>

  <div class="row post-archive flex-column-reverse flex-lg-row">

    <div class="col-lg-3">
      <?php include 'template-parts/navigation.php' ?>
      <?php include 'template-parts/maker-list.php' ?>
    </div>

    <div class="col-lg-9">
      <section class="post-archive__articles">
        <?php if ($count > 0) : ?>
        <div class="post-archive__articles__title">
          <h1>
            <?php echo $current_cat->name; ?>の商品一覧
            <span><?php echo "(" . $count . "件)"; ?></span>
          </h1>
          <div class="post-archive__articles__title__menus">
            <?php include 'template-parts/select-sort.php' ?>
            <?php include 'template-parts/select-maker.php' ?>
          </div>
        </div>

        <ul class="post-archive__articles__lists row">
          <?php
          if (count($results) > 0) :
          foreach ($results as $result) : $item = $result;
          ?>

          <li class="col-lg-6">
            <?php include 'template-parts/card-item.php'; ?>
          </li>

          <?php
          endforeach;
          ?>
          <?php else : ?>
          <li>商品が見つかりませんでした</li>
          <?php endif; ?>
        </ul>

        <div class="nav-links">
          <?php
          echo paginate_links(array(
            'base' => $home_url . '/categories/' . $cat . '/products/' . '%_%',
            'format' => '?page=%#%',
            'current' => $page,
            'total' => $total_page,
            'prev_next'    => false,
            'mid_size'  => 1,
          ));
          ?>
        </div>
        <?php
          else :
            get_template_part('template-parts/content', 'none');
          endif;
          ?>
      </section>
    </div>
  </div>


</main><!-- #main -->

<?php
get_footer();
