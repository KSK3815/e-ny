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

$page = $_GET['page'] ? : 1;
$product_limit = 30;
$product_offset = ($page-1)*$product_limit;

$sort_param = $_GET['sort']? : "new";
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

$q = get_query_var("q");
$my_cat_array = array();
$my_cats = get_categories();

$cg_tree[0] = array(
    'name' => "「{$q}」の検索結果"
);
$current_hierarchy = 0;

$esc_q = $wpdb->esc_like($q);

$query = <<<SQL
SELECT DISTINCT p.ID, p.post_title
FROM {$wpdb->posts} AS p
  LEFT OUTER JOIN {$wpdb->prefix}most_popular AS mp ON p.ID = mp.post_id
WHERE
	p.post_status = 'publish'
	AND p.post_type = 'eny_product'
	AND (
		p.post_title like '%{$esc_q}%'
		OR p.post_content like '%{$esc_q}%'
	)
{$sort_query}
LIMIT {$product_offset}, {$product_limit}
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

$total_page = ceil($product_count / $product_limit);
?>

<main class="container">
  <?php include 'template-parts/breadcrumbs.php' ?>

  <div class="results">

    <?php if ($product_count): ?>
    <div class="art-container">

      <div class="display-header">
        「<?php echo $q ?>」に関する商品
        <span>(<?php echo $product_count ?>件)</span>
        <?php include 'template-parts/select-sort.php' ?>
      </div>

      <div class="products row">
        <?php foreach ($product_results as $item): ?>
        <?php include 'template-parts/card-item.php'; ?>
        <?php endforeach ?>
      </div>

      <?php if ($product_count > $product_limit) :?>
      <div class="nav-links">
        <?php
              echo paginate_links(array(
                'base' => $home_url . '/search' . '%_%',
                'format' => '?page=%#%',
                'current' => $page,
                'total' => $total_page,
                'prev_next'    => false,
                'mid_size'  => 1,
              ));
            ?>
      </div>
      <?php endif; ?>

    </div>
    <?php endif; ?>

  </div>

</main>

<?php
get_footer();
